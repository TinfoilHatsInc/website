<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:21
 */

namespace AppBundle\Messaging\Command;


use AppBundle\Entity\User;

class RecoverDisabledAccount
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $user;
    }
}