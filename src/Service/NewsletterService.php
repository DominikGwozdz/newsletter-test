<?php

namespace App\Service;

use App\Entity\Newsletter;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\EmailIsNotValidException;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class NewsletterService
{
    public function __construct(protected EntityManagerInterface $em){}

    #[ArrayShape(['status' => "int", 'message' => "string"])]
    public function signIn(string $email) : array
    {
        try {
            $this->processData($email);
            return [
                'status' => 200,
                'message' => 'Thank for your subscription. We sent you an email with special url to confirm',
            ];
        } catch (EmailAlreadyExistsException) {
            return [
                'status' => 400,
                'message' => 'Email is already exists in our database.',
            ];
        } catch (EmailIsNotValidException) {
            return [
                'status' => 400,
                'message' => 'Provided email address is not valid.',
            ];
        }
    }

    public function processData(string $email) : void
    {
        if (!$this->validateEmail($email)) {
            throw new EmailIsNotValidException('Provided email is not valid');
        }

        if ($this->isEmailExists($email)) {
            throw new EmailAlreadyExistsException('Email is already exists');
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
