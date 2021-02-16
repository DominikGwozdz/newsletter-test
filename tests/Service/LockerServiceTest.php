<?php

namespace App\Tests\Service;

use App\Entity\Locker;
use App\Repository\LockerRepository;
use App\Service\LockerService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class LockerServiceTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $em;

    public function setUp(): void
    {
        $lockerMock = $this->getMockBuilder(Locker::class)->getMock();

        $lockerRepositoryMock = $this->getMockBuilder(LockerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $lockerRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($lockerMock);


        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $emMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($lockerRepositoryMock);

        $this->em = $emMock;
    }

    public function testIncreaseAttempt(): void
    {
        $ip_address = '192.167.2.32';

        $locker = new LockerService($this->em);
        $result = $locker->increaseAttempt($ip_address);
        $this->assertTrue($result);
    }

    public function testIsIpBanned(): void
    {
        $ip_address = '192.167.2.32';

        $locker = new LockerService($this->em);
        $result = $locker->isIpBanned($ip_address);
        $this->assertFalse($result);
    }
}
