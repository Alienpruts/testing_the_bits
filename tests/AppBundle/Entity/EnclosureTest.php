<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 14/01/19
 * Time: 17:34
 */

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Exception\DinosAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use PHPUnit\Framework\TestCase;

class EnclosureTest extends TestCase
{
    public function testItHasNoDinosaursByDefault()
    {
        $enclosure = new Enclosure();

        //$this->assertCount(0, $enclosure->getDinosaurs());
        $this->assertEmpty($enclosure->getDinosaurs());

    }

    public function testItAddsDinosaurs()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur());

        $this->assertCount(2, $enclosure->getDinosaurs());

    }

    public function testItDoesNotAllowCarnivorousDinosToMixWithHerbivores()
    {
        $enclosure = new Enclosure(true);

        // By default, Dinosaurs are herbivores.
        $enclosure->addDinosaur(new Dinosaur());

        // Expecting an Exception should be done BEFORE actually firing the code to be tested.
        $this->expectException(NotABuffetException::class);

        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));


    }

    /**
     * @expectedException \Appbundle\Exception\NotABuffetException
     */
    public function testItDoesNotAllowToAddHerbivoreDinosaursToCarnivorousEnclosures()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
        $enclosure->addDinosaur(new Dinosaur());

    }

    public function testItDoesNotAllowToAddDinosToUnsecureEnclosures()
    {
        $enclosure = new Enclosure();

        $this->expectException(DinosAreRunningRampantException::class);
        $this->expectExceptionMessage('Are you crazy?!?');

        $enclosure->addDinosaur(new Dinosaur());
    }


}