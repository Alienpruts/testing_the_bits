<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 14/01/19
 * Time: 15:54
 */

namespace AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;

/**
 * Class DinosaurFactory
 * @package AppBundle\Factory
 */
class DinosaurFactory
{
    private $lengthDeterminator;

    /**
     * DinosaurFactory constructor.
     */
    public function __construct(DinosaurLengthDeterminator $dinosaurLengthDeterminator)
    {
        $this->lengthDeterminator = $dinosaurLengthDeterminator;
    }


    /**
     * @param $length
     * @return Dinosaur
     */
    public function growVelociraptor($length)
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    /**
     * @param $genus
     * @param $isCarnivorous
     * @param $length
     * @return Dinosaur
     */
    private function createDinosaur($genus, $isCarnivorous, $length)
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }

    /**
     * @param $spec
     * @return Dinosaur
     * @throws \Exception
     */
    public function growFromSpecification(string $spec): Dinosaur
    {
        $codeName = 'InG-' . random_int(1, 99999);
        $isCarnivorous = false;

        $length = $this->lengthDeterminator->getLengthFromSpecification($spec);
        // Use this to fail the test on the basis that 'foo' is not known in the mocked lengthDeterminator of the  test
        // and it does not know how to handle it.
        // You can also use it to test if test will fail when called more than once.
        //$length = $this->lengthDeterminator->getLengthFromSpecification('foo');

        if (stripos($spec, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        return $this->createDinosaur($codeName, $isCarnivorous, $length);

    }

}

