<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 6-12-17
 * Time: 12:59
 */

namespace AppBundle\Twig;

use AppBundle\Model\Cart;
use AppBundle\Services\ShoppingCartService;

class OrderTotal extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('calculateTotal', [$this, 'calculateTotal'])
        ];
    }

    /**
     * Calculate Cart total
     *
     * @param Cart $cart
     * @return int
     */
    public function calculateTotal(Cart $cart)
    {
        return ShoppingCartService::calculateCartTotal($cart);
    }
}