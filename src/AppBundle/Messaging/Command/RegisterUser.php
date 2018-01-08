<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 8-1-18
 * Time: 11:51
 */

namespace AppBundle\Messaging\Command;


use AppBundle\Entity\User;

class RegisterUser
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}