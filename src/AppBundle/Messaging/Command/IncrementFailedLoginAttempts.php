<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 10:10
 */

namespace AppBundle\Messaging\Command;


class IncrementFailedLoginAttempts
{
    private $userEmail;

    public function __construct($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }
}