<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 4/02/19
 * Time: 9:36
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="securities")
 */
class Security
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="securities")
     */
    private $enclosure;

    /**
     * Security constructor.
     * @param $name
     * @param $isActive
     * @param $enclosure
     */
    public function __construct(string $name, bool $isActive, Enclosure $enclosure)
    {
        $this->name = $name;
        $this->isActive = $isActive;
        $this->enclosure = $enclosure;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }


}