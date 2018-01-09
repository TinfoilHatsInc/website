<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 12:50
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Chub;
use AppBundle\Messaging\Command\RegisterChub;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class RegisterChubHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Session
     */
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function handle(RegisterChub $registerChub)
    {
        $user = $registerChub->getUser();
        $chub = new Chub();
        $chub->setUser($user);
        $this->em->persist($chub);
        $this->em->flush();
        $this->session->getFlashBag()->add('success', sprintf('New Chub successfully registered for %s with Chub ID %s', $user->getEmail(), $chub->getId()));
    }
}