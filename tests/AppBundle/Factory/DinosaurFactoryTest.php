<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 14/01/19
 * Time: 15:51
 */

namespace Tests\AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

/**
 * Class DinosaurFactoryTest
 * @package Tests\AppBundle\Factory
 */
class DinosaurFactoryTest extends TestCase
{

    /**
     * @var DinosaurFactory
     */
    private $factory;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $lengthDeterminator;

    /**
     *
     */
    protected function setUp()
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);

    }


    /**
     *
     */
    public function testItGrowsALargeVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());

    }

    /**
     *
     */
    public function testItGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }

    /**
     *
     */
    public function testItGrowsABabyVelociraptor()
    {
        // Suppose we make use of a library that is not standard - it might not be installed.
        if (!class_exists('DinosaurNanny')) {
            $this->markTestSkipped('There is no Nanny to watch the Dinosaur Baby!');
        }
        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());


    }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromSpecification(string $spec, bool $expectedIsCarnivorous)
    {
        // Mock the getLengthFromSpecification to always return 20 (in this test instance)
        // We also specify this method MUST be called exactly one time, or tests will fail!
        // We also specify a parameter to be passed to the function. If it doesn't match, failure
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        /** @var Dinosaur $dinosaur */
        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match!');
        $this->assertSame(20, $dinosaur->getLength());
    }

    /**
     * @return array
     */
    public function getSpecificationTests()
    {
        return [
            ['large carnivorous dinosaur', true],
            ['give me all the cookies!!!!', false],
            ['large herbivore', false]
        ];
    }

    /**
     * @return array
     */
    public function getHugeDinosaurSpecTest()
    {
        return [
            ['huge dinosaur'],
            ['huge dino'],
            ['huge'],
            ['OMG'],
            ['😱'],
        ];
    }

}