<?php

namespace App\Service;

use App\Entity\Newsletter;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\EmailIsNotValidException;
use App\Exception\ExceededAttemptCountException;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\RequestStack;

class NewsletterService
{
    public function __construct(protected EntityManagerInterface $em, protected LockerService $locker, protected RequestStack $requestStack){}

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function signIn(string $email) : array
    {
        try {
            $this->processData($email);
            return [
                'status' => 200,
                'message' => 'Thank for your subscription. We sent you an email with special url to confirm',
            ];
        } catch (EmailAlreadyExistsException | EmailIsNotValidException | ExceededAttemptCountException $e) {
            return [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public function processData(string $email) : void
    {
        $this->validateAll($email);

        $newsletter = new Newsletter();
        $newsletter->setEmail($email);
        $newsletter->setIsConfirmed(0);
        $newsletter->setSignedAt(new \DateTime('now'));
        $newsletter->setHash(md5($email));
        $this->em->persist($newsletter);
        $this->em->flush();
    }

    private function validateAll(string $email) : void
    {
        if ($this->locker->isIpBanned($this->requestStack->getCurrentRequest()->getClientIp())) {
            throw new ExceededAttemptCountException('You were banned', 403);
        }

        if (!$this->validateEmail($email)) {
            $this->locker->increaseAttempt($this->requestStack->getCurrentRequest()->getClientIp());
            throw new EmailIsNotValidException('Provided email address is not valid.', 400);
        }

        if ($this->isEmailExists($email)) {
            $this->locker->increaseAttempt($this->requestStack->getCurrentRequest()->getClientIp());
            throw new EmailAlreadyExistsException('Email is already exists in our database.', 400);
        }
    }

    #[Pure]
    private function validateEmail(string $email) : bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    private function isEmailExists(string $email) : bool
    {
        return $this->em->getRepository(Newsletter::class)->findOneBy(['email' => $email]) ? true : false;
    }
}
