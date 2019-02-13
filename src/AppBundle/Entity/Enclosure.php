<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 14/01/19
 * Time: 17:37
 */

namespace AppBundle\Entity;


use AppBundle\Exception\DinosAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Class Enclosure
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="enclosures")
 */
class Enclosure
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dinosaur", mappedBy="enclosure", cascade={"persist"})
     */
    private $dinosaurs;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Security", mappedBy="enclosure", cascade={"persist"})
     */
    private $securities;

    /**
     * Enclosure constructor.
     */
    public function __construct(bool $withBasicSecurity = false)
    {
        $this->dinosaurs = new ArrayCollection();
        $this->securities = new ArrayCollection();

        if ($withBasicSecurity) {
            $this->addSecurity(new Security('Fence', true, $this));
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getDinosaurs()
    {
        return $this->dinosaurs;

    }

    /**
     * @param Dinosaur $dino
     * @throws NotABuffetException
     * @throws DinosAreRunningRampantException
     */
    public function addDinosaur(Dinosaur $dino)
    {
        if (!$this->canAddDinosaur($dino)) {
            throw new NotABuffetException();
        }
        $this->dinosaurs[] = $dino;
    }

    /**
     * @param Dinosaur $dinosaur
     * @return bool
     * @throws DinosAreRunningRampantException
     */
    public function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        if (!$this->isSecurityActive()) {
            throw new DinosAreRunningRampantException('Are you crazy?!?');
        }
        // If Enclosure is empty, go. If first dino
        return \count($this->dinosaurs) === 0
            || $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();

    }

    /**
     * @return bool
     */
    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            /** @var Security $security */
            if ($security->isActive()) {
                return true;
            }
        }

        return false;
    }

    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    /**
     * @return mixed
     */
    public function getSecurities(): Collection
    {
        return $this->securities;
    }

    /**
     * @param mixed $securities
     */
    public function setSecurities($securities)
    {
        $this->securities = $securities;
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
     * @return int
     */
    public function dinosaurCount(): int
    {
        return $this->dinosaurs->count();
    }

}