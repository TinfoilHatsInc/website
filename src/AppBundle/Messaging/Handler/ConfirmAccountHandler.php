<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 8-1-18
 * Time: 13:40
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\ConfirmAccount;
use Doctrine\ORM\EntityManager;

class ConfirmAccountHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(ConfirmAccount $confirmAccount)
    {
        $user = $confirmAccount->getUser();
        $user->setIsEnabled(true);
        $user->setTokenCreatedAt(null);
        $user->setConfirmationToken(null);
        $this->em->flush();
    }
}