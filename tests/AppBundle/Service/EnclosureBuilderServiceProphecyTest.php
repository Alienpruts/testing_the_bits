<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 12/02/19
 * Time: 15:28
 */

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class EnclosureBuilderServiceProphecyTest extends TestCase
{
    public function testItBuildsAndPersistEnclosure()
    {
        $em = $this->prophesize(EntityManagerInterface::class);

        // Persist method should be called exactly ONE time
        $em->persist(Argument::type(Enclosure::class))
            ->shouldBeCalledTimes(1);

        // Flush method should be called AT LEAST once
        $em->flush()->shouldBeCalled();

        $dinoFactory = $this->prophesize(DinosaurFactory::class);
        $dinoFactory->growFromSpecification(Argument::type('string'))
            ->shouldBeCalledTimes(2)
            ->willReturn(new Dinosaur());

        $builder = new EnclosureBuilderService(
            // This tells Prophecy to turn the mock builder in to a true Mock object
            $em->reveal(),
            $dinoFactory->reveal()
        );

        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());


    }


}