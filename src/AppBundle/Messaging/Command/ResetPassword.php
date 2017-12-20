<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:24
 */

namespace AppBundle\Messaging\Command;


use AppBundle\Entity\User;

class ResetPassword
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $newPlainPassword;

    public function __construct(User $user, $newPlainPassword)
    {
        $this->user = $user;
        $this->newPlainPassword = $newPlainPassword;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getNewPlainPassword()
    {
        return $this->newPlainPassword;
    }
}