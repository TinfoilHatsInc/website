<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 13:21
 */

namespace AppBundle\Messaging\Command;


use AppBundle\Entity\Order;

class FillOrder
{
    /**
     * @var Order
     */
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}