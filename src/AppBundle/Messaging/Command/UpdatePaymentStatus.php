<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 17-1-18
 * Time: 15:35
 */

namespace AppBundle\Messaging\Command;


class UpdatePaymentStatus
{
    /**
     * @var string
     */
    private $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }
}