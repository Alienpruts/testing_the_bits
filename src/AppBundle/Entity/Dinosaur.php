<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Dinosaur
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="dinosaurs")
 */
class Dinosaur
{

    const LARGE = 10;
    const HUGE = 20;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $length = 0;


    /**
     * @ORM\Column(type="string")
     */
    private $genus;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isCarnivorous;


    /**
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="dinosaurs")
     */
    private $enclosure;

    /**
     * Dinosaur constructor.
     * @param string $genus
     * @param bool $isCarnivorous
     */
    public function __construct($genus = 'Unknown', $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param $int
     */
    public function setLength($int)
    {
        $this->length = $int;
    }

    /**
     * @return mixed
     */
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * @return bool
     */
    public function isCarnivorous()
    {
        return $this->isCarnivorous;
    }

    /**
     * @return string
     */
    public function getSpecification()
    {
        return sprintf(
            'The %s %scarnivorous dinosaur is %d meters long',
            $this->genus,
            $this->isCarnivorous ? '' : 'non-',
            $this->length
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getisCarnivorous()
    {
        return $this->isCarnivorous;
    }

    /**
     * @param mixed $isCarnivorous
     */
    public function setIsCarnivorous($isCarnivorous)
    {
        $this->isCarnivorous = $isCarnivorous;
    }

    /**
     * @return Enclosure
     */
    public function getEnclosure(): Enclosure
    {
        return $this->enclosure;
    }

    /**
     * @param Enclosure $enclosure
     */
    public function setEnclosure(Enclosure $enclosure)
    {
        $this->enclosure = $enclosure;
    }



}