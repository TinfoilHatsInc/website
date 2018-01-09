<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:21
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\RecoverDisabledAccount;
use Doctrine\ORM\EntityManager;

class RecoverDisabledAccountHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(RecoverDisabledAccount $recoverDisabledAccount)
    {
        $user = $recoverDisabledAccount->getUser();
        $user->setIsEnabled(true);
        $user->setTokenCreatedAt(null);
        $user->setConfirmationToken(null);
        $this->em->flush();
    }
}