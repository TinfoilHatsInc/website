<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 15:37
 */

namespace AppBundle\Services\ShoppingCart;


use AppBundle\Entity\Product;
use AppBundle\Model\Cart;

interface ShoppingCart
{
    /**
     * @param Product $product
     * @param $amount
     */
    public function addToCart(Product $product, $amount);

    /**
     * @param Product $product
     */
    public function removeFromCart(Product $product);
    /**
     * @param Product $product
     * @param $amount
     */
    public function updateAmount(Product $product, $amount);

    /**
     * Build a Cart object from the cart data stored
     *
     * @return Cart
     */
    public function getCartModel();

    /**
     * Clear cart
     */
    public function clearCart();
    /**
     * @param Cart $cart
     * @return int
     */
    public static function calculateCartTotal(Cart $cart);
}