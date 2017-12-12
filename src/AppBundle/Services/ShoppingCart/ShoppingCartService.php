<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 14:36
 */

namespace AppBundle\Services\ShoppingCart;


use AppBundle\Model\Cart;
use AppBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ShoppingCartService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TokenStorage
     */
    private $token;

    /**
     * @var SessionShoppingCart|object
     */
    private $shoppingCart;

    public function __construct(ContainerInterface $container, TokenStorage $token)
    {
        $this->container = $container;
        $this->token = $token;

        if($this->token->getToken()->isAuthenticated()) {
            $this->shoppingCart = $this->container->get('tinfoil.service.db_cart');
        } else {
            $this->shoppingCart = $this->container->get('tinfoil.service.session_cart');
        }
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function addToCart(Product $product, $amount)
    {
        $this->shoppingCart->addToCart($product, $amount);
    }

    /**
     * @param Product $product
     */
    public function removeFromCart(Product $product)
    {
        $this->shoppingCart->removeFromCart($product);
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function updateAmount(Product $product, $amount)
    {
        $this->shoppingCart->updateAmount($product, $amount);
    }

    /**
     * Build a Cart object from the cart data stored
     *
     * @return Cart
     */
    public function getCartModel()
    {
        return $this->shoppingCart->getCartModel();
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $this->shoppingCart->clearCart();
    }

    /**
     * @param Cart $cart
     * @return int
     */
    public function calculateCartTotal(Cart $cart)
    {
        $total = 0;
        foreach ($cart->getProducts() as $product) {
            $total += $product['product']->getPrice() * $product['amount'];
        }
        return $total;
    }
}