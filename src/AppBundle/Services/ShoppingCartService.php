<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 4-12-17
 * Time: 11:53
 */

namespace AppBundle\Services;


use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;

class ShoppingCartService
{
    /**
     * @var Session
     */
    private $session;

    /**
     * ShoppingCartService constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function addToCart(Product $product, $amount)
    {
        $this->session->start();
        $this->session->set('cart', [
            $product->getId() => $amount
        ]);
    }

    /**
     * @param Product $product
     */
    public function removeFromCart(Product $product)
    {
        $this->session->start();
        $currentCart = $this->session->get('cart');
        unset($currentCart[$product->getId()]);
        $this->session->set('cart', $currentCart);
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function updateAmount(Product $product, $amount)
    {
        $this->session->start();
        $currentCart = $this->session->get('cart');
        $currentCart[$product->getId()] = $amount;
        $this->session->set('cart', $currentCart);
    }
}