<?php
/**
 * Created by PhpStorm.
 * User: john.ogrady
 * Date: 11/04/2017
 * Time: 23:04
 */

namespace tests;

use AppBundle\Entity\Roster;
use AppBundle\Controller\RosterController;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class RosterTest extends TestCase
{
    public function testCalculateTotalSalary()
    {
        // First, mock the object to be used in the test
        $roster = $this->createMock(Roster::class);
        $roster->expects($this->once())
            ->method('newActionfromServiceUser')
            ->will($this->r(1000));
        $roster->expects($this->once())
            ->method('getBonus')
            ->will($this->returnValue(1100));

        // Now, mock the repository so it returns the mock of the employee
        $rosterRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $rosterRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($roster));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($rosterRepository));

        $salaryCalculator = new SalaryCalculator($entityManager);
        $this->assertEquals(2100, $salaryCalculator->calculateTotalSalary(1));
    }
}