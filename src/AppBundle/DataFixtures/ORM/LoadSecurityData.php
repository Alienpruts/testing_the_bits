<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 13/02/19
 * Time: 9:07
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSecurityData extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $herbivorousEnclosure = $this->getReference('herbivorous-enclosure');

        $this->addSecurity($herbivorousEnclosure, 'Fence', true);

        $carnivorousEnclosure = $this->getReference('carnivorous-enclosure');

        $this->addSecurity($carnivorousEnclosure, 'Electric Fence', false);
        $this->addSecurity($carnivorousEnclosure, 'Guard Tower', false);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    private function addSecurity(Enclosure $enclosure, string $name, bool $isActive)
    {
        $enclosure->addSecurity(new Security($name, $isActive, $enclosure));
    }
}