<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 6/02/19
 * Time: 10:38
 */

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EnclosureBuilderTest extends TestCase
{
    public function testItBuildsAndPersistsEnclosure()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $dinoFactory = $this->createMock(DinosaurFactory::class);

        $em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Enclosure::class));

        $em->expects($this->atLeastOnce())
            ->method('flush');

        $dinoFactory->expects($this->exactly(2))
            ->method('growFromSpecification')
            // technically, this is not needed, thanks to PHP7 return type Dinosaur on growFromSpecification. PHPUnit will
            // create Mock Dinosaur objects automatically. Try removing return type and running test.
            ->willReturn(new Dinosaur())
            ->with($this->isType('string'));


        $builder = new EnclosureBuilderService($em, $dinoFactory);
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());

        // Check the items in the Dinosaurs collection.
        //dump($enclosure->getDinosaurs()->toArray());

    }


}