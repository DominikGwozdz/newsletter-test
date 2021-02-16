<?php

namespace App\Service;

use App\Entity\Locker;
use App\Exception\ExceededAttemptCountException;
use Doctrine\ORM\EntityManagerInterface;

class LockerService
{
    private const MAX_ATTEMPT_COUNT = 3;
    private const LOCKDOWN_MINUTES = 1;

    public function __construct(protected EntityManagerInterface $em){}

    public function increaseAttempt(string $ipAddress) : bool
    {
        /** @var Locker $locker */
        $locker = $this->em->getRepository(Locker::class)->findOneBy(['ip_address' => $ipAddress]);

        if ($locker) {
            $locker->setLockUntil(null);
            $locker->setIpAddress($ipAddress);
            $locker->setAttemptCount($locker->getAttemptCount() + 1);
            $this->em->persist($locker);
            $this->em->flush();

            if ($locker->getAttemptCount() > self::MAX_ATTEMPT_COUNT) {
                $datetime = new \DateTime('now');
                $datetime->modify('+ ' . self::LOCKDOWN_MINUTES . ' minutes');
                $locker->setLockUntil($datetime);
                $locker->setAttemptCount(0);
                $this->em->persist($locker);
                $this->em->flush();
            }
            return true;
        } else {
            $this->addNewLockerEntry($ipAddress);
            return true;
        }
    }

    public function isIpBanned(string $ipAddress) : bool
    {
        /** @var Locker $locker */
        $locker = $this->em->getRepository(Locker::class)->findOneBy(['ip_address' => $ipAddress]);
        if (!$locker) {
            return false;
        }

        return $locker->getLockUntil() > new \DateTime('now');
    }

    private function addNewLockerEntry(string $ipAddress): void
    {
        $locker = new Locker();
        $locker->setIpAddress($ipAddress);
        $locker->setAttemptCount(1);
        $this->em->persist($locker);
        $this->em->flush();
    }
}
