<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 21-11-17
 * Time: 12:05
 */

namespace AppBundle\Messaging\Command;


class AddAdminUser
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * AddAdminUser constructor.
     * @param $email
     * @param $password
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}