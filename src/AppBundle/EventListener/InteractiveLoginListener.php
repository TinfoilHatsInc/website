<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 14:02
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
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

    public function __construct($maxFailedLoginAttempts, EntityManager $em)
    {
        $this->maxFailedLoginAttempts = $maxFailedLoginAttempts;
        $this->em = $em;
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
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $user
        ]);

        if(!$user) {
            return;
        }

        $user->setFailedLoginAttempts($user->getFailedLoginAttempts() + 1);

        if($user->getFailedLoginAttempts() >= $this->maxFailedLoginAttempts) {
            $user->setIsEnabled(false);
        }

        $this->em->flush();
    }
}