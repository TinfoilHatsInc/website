<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 19-12-17
 * Time: 10:48
 */

namespace AppBundle\Util;


use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProduct;

class TotalCalculator
{
    public static function calculateFromArray(array $orderedProducts)
    {
        $total = 0;
        foreach ($orderedProducts as $product) {
            $total += $product['product']->getPrice() * $product['amount'];
        }
        return $total;
    }

    public static function calculateFromOrder(Order $order)
    {
        $total = 0;
        /** @var OrderProduct $orderedProduct */
        foreach ($order->getOrderedProducts() as $orderedProduct) {
            $total += $orderedProduct->getProduct()->getPrice() * $orderedProduct->getAmount();
        }
        return $total;
    }
}