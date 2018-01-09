<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 14:02
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Messaging\Command\IncrementFailedLoginAttempts;
use AppBundle\Util\TokenGenerator;
use Doctrine\ORM\EntityManager;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class InteractiveLoginListener
{
    /**
     * @var int
     */
    private $maxFailedLoginAttempts;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct($maxFailedLoginAttempts, EntityManager $em, CommandBus $commandBus)
    {
        $this->maxFailedLoginAttempts = $maxFailedLoginAttempts;
        $this->em = $em;
        $this->commandBus = $commandBus;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
//        dump($event->getRequest()->headers->get('referer'));
        //TODO implement session cart transfer?

        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $user->setFailedLoginAttempts(0);
        $this->em->flush();
    }

    public function onSecurityAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $email = $event->getAuthenticationToken()->getUser();

        if(!is_string($email)) {
            return;
        }

        $this->commandBus->handle(new IncrementFailedLoginAttempts($email));
    }
}