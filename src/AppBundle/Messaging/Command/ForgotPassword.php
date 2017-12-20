<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 20-12-17
 * Time: 14:25
 */

namespace AppBundle\Messaging\Command;


class ForgotPassword
{
    /**
     * @var string
     */
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}