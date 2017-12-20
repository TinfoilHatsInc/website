<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:21
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\RecoverDisabledAccount;

class RecoverDisabledAccountHandler
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(RecoverDisabledAccount $recoverDisabledAccount)
    {

    }
}