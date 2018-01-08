<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 15:00
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\ResetPassword;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class ResetPasswordHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function handle(ResetPassword $resetPassword)
    {
        $user = $resetPassword->getUser();
        $plainPassword = $resetPassword->getNewPlainPassword();
        $password = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->eraseCredentials();
        $user->setTokenCreatedAt(null);
        $user->setConfirmationToken(null);
        $this->em->flush();
    }
}