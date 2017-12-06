<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 6-12-17
 * Time: 16:17
 */

namespace AppBundle\Services;

use AppBundle\Entity\Order;

class OrderService
{
    /**
     * @param Order $order
     * @return int
     */
    public static function calculateOrderTotal(Order $order)
    {
        $total = 0;
        foreach ($order->getOrderedProducts() as $orderedProduct) {
            $total += $orderedProduct->getProduct()->getPrice() * $orderedProduct->getAmount();
        }
        return $total;
    }
}