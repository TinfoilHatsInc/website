<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 6-12-17
 * Time: 16:34
 */

namespace AppBundle\Twig;

use AppBundle\Model\Cart;
use AppBundle\Services\ShoppingCartService;

class CartTotal extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('calculateCartTotal', [$this, 'calculateCartTotal'])
        ];
    }

    /**
     * @param Cart $cart
     * @return int
     */
    public function calculateCartTotal(Cart $cart)
    {
        return ShoppingCartService::calculateCartTotal($cart);
    }
}