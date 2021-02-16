<?php

namespace App\Entity;

use App\Repository\LockerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LockerRepository::class)
 */
class Locker
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $ip_address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lock_until;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempt_count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getLockUntil(): ?\DateTimeInterface
    {
        return $this->lock_until;
    }

    public function setLockUntil(\DateTimeInterface|null $lock_until): self
    {
        $this->lock_until = $lock_until;

        return $this;
    }

    public function getAttemptCount(): ?int
    {
        return $this->attempt_count;
    }

    public function setAttemptCount(int $attempt_count): self
    {
        $this->attempt_count = $attempt_count;

        return $this;
    }
}
