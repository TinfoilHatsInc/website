<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 6-12-17
 * Time: 12:59
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Order;
use AppBundle\Services\OrderService;

class OrderTotal extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('calculateOrderTotal', [$this, 'calculateOrderTotal'])
        ];
    }

    /**
     * Calculate Order total
     *
     * @param Order $order
     * @return int
     */
    public function calculateOrderTotal(Order $order)
    {
        return OrderService::calculateOrderTotal($order);
    }
}