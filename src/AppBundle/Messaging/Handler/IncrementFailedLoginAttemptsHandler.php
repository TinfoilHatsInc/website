<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 10:12
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\User;
use AppBundle\Messaging\Command\IncrementFailedLoginAttempts;
use AppBundle\Messaging\Command\SendAccountRecoveryEmail;
use Doctrine\ORM\EntityManager;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

class IncrementFailedLoginAttemptsHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var integer
     */
    private $maxFailedLoginAttempts;

    public function __construct(EntityManager $em, CommandBus $commandBus, $maxFailedLoginAttempts)
    {
        $this->em = $em;
        $this->commandBus = $commandBus;
        $this->maxFailedLoginAttempts = $maxFailedLoginAttempts;
    }

    public function handle(IncrementFailedLoginAttempts $incrementFailedLoginAttempts)
    {
        /** @var User $user */
        $email = $incrementFailedLoginAttempts->getUserEmail();

        if(!is_string($email)) {
            return;
        }

        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if(!$user) {
            return;
        }

        $user->setFailedLoginAttempts($user->getFailedLoginAttempts() + 1);

        if($user->getFailedLoginAttempts() >= $this->maxFailedLoginAttempts) {
            $user->setIsEnabled(false);
            $this->commandBus->handle(new SendAccountRecoveryEmail($user));
        }
        $this->em->flush();
    }
}