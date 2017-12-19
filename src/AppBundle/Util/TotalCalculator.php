<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 19-12-17
 * Time: 10:48
 */

namespace AppBundle\Util;


class TotalCalculator
{
    public static function calculate(array $orderedProducts)
    {
        $total = 0;
        foreach ($orderedProducts as $product) {
            $total += $product['product']->getPrice() * $product['amount'];
        }
        return $total;
    }
}