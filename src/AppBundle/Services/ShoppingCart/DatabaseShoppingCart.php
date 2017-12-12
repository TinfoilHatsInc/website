<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 15:39
 */

namespace AppBundle\Services\ShoppingCart;

use AppBundle\Model\Cart;
use AppBundle\Entity\User;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\CartProduct;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DatabaseShoppingCart implements ShoppingCart
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    public function __construct(EntityManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->user = $token->getToken()->getUser();
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function addToCart(Product $product, $amount)
    {
        $cart = $this->user->getCart();
        if(!$cart) {
            $cart = new \AppBundle\Entity\Cart();
            $this->user->setCart($cart);
            $cart->setUser($this->user);
            $this->em->persist($cart);
            $this->em->persist($this->user);
        }
        $cartProduct = new CartProduct();
        $cartProduct->setProduct($product);
        $cartProduct->setAmount($amount);
        $cartProduct->setCart($cart);
        $this->em->persist($cartProduct);
        $this->em->flush();
    }

    /**
     * @param Product $product
     */
    public function removeFromCart(Product $product)
    {
        // TODO: Implement removeFromCart() method.
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function updateAmount(Product $product, $amount)
    {
        // TODO: Implement updateAmount() method.
    }

    /**
     * Build a Cart object from the cart data stored
     *
     * @return Cart
     */
    public function getCartModel()
    {
        $dbCart = $this->em->getRepository(\AppBundle\Entity\Cart::class)->findOneBy([
            'user' => $this->user
        ]);

        $cartProducts = $dbCart->getCartProducts();
        $cart = new Cart();

        /** @var CartProduct $cartProduct */
        foreach ($cartProducts as $cartProduct) {
            $cart->addProduct($cartProduct->getProduct(), $cartProduct->getAmount());
        }

        return $cart;
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        // TODO: Implement clearCart() method.
    }

    /**
     * @param Cart $cart
     * @return int
     */
    public static function calculateCartTotal(Cart $cart)
    {
        return 0;
    }
}