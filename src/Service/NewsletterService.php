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
        } catch (EmailAlreadyExistsException | EmailIsNotValidException $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage(),
            ];
        } catch (ExceededAttemptCountException $e) {
            return [
                'status' => 401,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function processData(string $email) : void
    {
        if ($this->locker->isIpBanned($this->requestStack->getCurrentRequest()->getClientIp())) {
            throw new ExceededAttemptCountException('You were banned');
        }

        if (!$this->validateEmail($email)) {
            $this->locker->addLockerEntry($this->requestStack->getCurrentRequest()->getClientIp());
            throw new EmailIsNotValidException('Provided email address is not valid.');
        }

        if ($this->isEmailExists($email)) {
            $this->locker->addLockerEntry($this->requestStack->getCurrentRequest()->getClientIp());
            throw new EmailAlreadyExistsException('Email is already exists in our database.');
        }

        $newsletter = new Newsletter();
        $newsletter->setEmail($email);
        $newsletter->setIsConfirmed(0);
        $newsletter->setSignedAt(new \DateTime('now'));
        $newsletter->setHash(md5($email));
        $this->em->persist($newsletter);
        $this->em->flush();
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
