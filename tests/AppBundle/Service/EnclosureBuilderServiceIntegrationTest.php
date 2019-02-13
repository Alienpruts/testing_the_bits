<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 12/02/19
 * Time: 16:00
 */

namespace AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();

        // Manual way of doing a purge of DB
//        $this->truncateEntities([
//            Enclosure::class,
//            Security::class,
//            Dinosaur::class,
//        ]);

        // using DataFixtures ORMPurger.
        $this->truncateEntitiesWithDataFixtures();
    }


    public function testItBuildsEnclosureWithTheDefaultSpecification()
    {
        /** @var EnclosureBuilderService $enclosureBuilderService */
        $enclosureBuilderService = self::$kernel->getContainer()->get('test.' . EnclosureBuilderService::class);

        $enclosureBuilderService->buildEnclosure();

        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $countSecurity = (int)$em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $countDinos = (int)$em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $countSecurity, 'Amount of security systems is not the same!');
        $this->assertSame(3, $countDinos, 'Amount of Dinosaurs is not the same!');
    }


    private function truncateEntities(array $entities)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $connection = $em->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $em->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function truncateEntitiesWithDataFixtures() {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


}