<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 14/01/19
 * Time: 15:13
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;

/**
 * Class DinosaurTest
 * @package Tests\AppBundle\Entity
 */
class DinosaurTest extends TestCase
{

    public function testSettingLength()
    {
        $dinosaur = new Dinosaur();

        $this->assertSame(0, $dinosaur->getLength());

        $dinosaur->setLength(9);

        $this->assertSame(9, $dinosaur->getLength());
    }

    public function testDinosaurHasNotShrunk()
    {
        $dinosaur = new Dinosaur();

        $dinosaur->setLength(15);

        $this->assertGreaterThan(12, $dinosaur->getLength(), 'Did you put it in the washing machine?');
    }

    public function testReturnsFullSpecificationOfDinosaurs()
    {
        $dinosaur = new Dinosaur();

        $this->assertSame('The Unknown non-carnivorous dinosaur is 0 meters long', $dinosaur->getSpecification());
    }

    public function testReturnsFullSpecificationForTyrannosaurus()
    {
        $dinosaur = new Dinosaur('Tyrannosaurus', true);
        $dinosaur->setLength(12);

        $this->assertSame('The Tyrannosaurus carnivorous dinosaur is 12 meters long', $dinosaur->getSpecification());
    }
}