<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 8-1-18
 * Time: 11:51
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Role;
use AppBundle\Messaging\Command\RegisterUser;
use AppBundle\Util\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RegisterUserHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var TwigEngine
     */
    private $twigEngine;
    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder, TwigEngine $twigEngine, Router $router, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->twigEngine = $twigEngine;
        $this->router = $router;
        $this->mailer = $mailer;
    }

    public function handle(RegisterUser $registerUser)
    {
        $user = $registerUser->getUser();
        $chubHash = hash('sha256', $user->getPlainPassword().$user->getEmail());
        $user->setChubHash(password_hash($chubHash, PASSWORD_BCRYPT));

        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->eraseCredentials();
        $user->setPassword($password);
        $user->eraseCredentials();
        $user->setIsEnabled(false);

        $user->setTokenCreatedAt(new \DateTime('now'));
        TokenGenerator::generateToken($tokenForLink, $tokenHashForDatabase);
        $user->setConfirmationToken($tokenHashForDatabase);

        $role = $this->em->getRepository(Role::class)->findOneBy([
            'name' => 'ROLE_CUSTOMER'
        ]);
        $user->setRoles([$role]);

        $this->em->persist($user);
        $this->em->flush();

        $message = (new \Swift_Message())
            ->setTo($user->getEmail())
            ->setFrom('noreply@tinfoilhats.com')
            ->setSubject('Account Registration | Tinfoil Hats, inc')
            ->setBody(
                $this->twigEngine->render(':email:confirm_account.html.twig', [
                    'firstname' => $user->getFirstName(),
                    'lastname' => $user->getLastName(),
                    'resetLink' => $this->router->generate('confirm_account', ['token' => $tokenForLink], UrlGeneratorInterface::ABSOLUTE_URL)
                ]), 'text/html'
            );
        $this->mailer->send($message);
    }
}