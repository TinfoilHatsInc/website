<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 14:02
 */

namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class InteractiveLoginListener
{
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
//        dump($event->getRequest()->headers->get('referer'));
        //TODO implement session cart transfer?
    }
}