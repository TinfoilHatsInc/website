<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 10:35
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\SendAccountRecoveryEmail;
use AppBundle\Util\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendAccountRecoveryEmailHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManager $em, Router $router, TwigEngine $twigEngine, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->router = $router;
        $this->twigEngine = $twigEngine;
        $this->mailer = $mailer;
    }

    public function handle(SendAccountRecoveryEmail $sendAccountRecoveryEmail)
    {
        $user = $sendAccountRecoveryEmail->getUser();
        $user->setTokenCreatedAt(new \DateTime('now'));
        TokenGenerator::generateToken($tokenForLink, $tokenHashForDatabase);
        $user->setConfirmationToken($tokenHashForDatabase);
        $this->em->flush();

        $message = (new \Swift_Message())
            ->setTo($user->getEmail())
            ->setFrom('noreply@tinfoilhats.com')
            ->setSubject('Recover Account | Tinfoil Hats, inc.')
            ->setBody(
                $this->twigEngine->render(':email:recover_account.twig', [
                    'firstname' => $user->getFirstName(),
                    'lastname' => $user->getLastName(),
                    'recoverLink' => $this->router->generate('recover_account', ['token' => $tokenForLink], UrlGeneratorInterface::ABSOLUTE_URL)
                ]), 'text/html'
            );
        $this->mailer->send($message);
    }
}