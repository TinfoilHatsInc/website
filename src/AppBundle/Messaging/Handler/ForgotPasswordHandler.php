<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:25
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\User;
use AppBundle\Messaging\Command\ForgotPassword;
use AppBundle\Util\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class ForgotPasswordHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @var Router
     */
    private $router;

    public function __construct(EntityManager $em, \Swift_Mailer $mailer, TwigEngine $twigEngine, Router $router)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twigEngine = $twigEngine;
        $this->router = $router;
    }

    public function handle(ForgotPassword $forgotPassword)
    {
        $email = $forgotPassword->getEmail();
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if(!$user) {
            return;
        }

        $user->setTokenCreatedAt(new \DateTime('now'));
        TokenGenerator::generateToken($tokenForLink, $tokenHashForDatabase);
        //Store hashed token in database
        $user->setConfirmationToken($tokenHashForDatabase);
        $this->em->flush();

        $message = (new \Swift_Message())
            ->setTo($user->getEmail())
            ->setFrom('noreply@tinfoilhats.com')
            ->setSubject('Password Reset')
            ->setBody(
                $this->twigEngine->render(':email:reset_password.html.twig', [
                    'firstname' => $user->getFirstName(),
                    'lastname' => $user->getLastName(),
                    'resetLink' => $this->router->generate('reset_password', ['token' => $tokenForLink], UrlGeneratorInterface::ABSOLUTE_URL)
                ]), 'text/html'
            );
        $this->mailer->send($message);
    }
}