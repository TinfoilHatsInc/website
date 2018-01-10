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
use Doctrine\Common\Collections\Criteria;
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
     * @return array|null
     */
    public function getItem(Product $product)
    {
        $cart = $this->user->getCart();
        $cartProduct = $this->em->getRepository(CartProduct::class)->findOneBy([
            'cart' => $cart,
            'product' => $product
        ]);

        if(!$cartProduct) {
            return [];
        }

        return [
            'product' => $cartProduct->getProduct(),
            'amount' => $cartProduct->getAmount()
        ];
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function addToCart(Product $product, $amount)
    {
        if($this->checkIfProductInCart($product)) {
            $newAmount = $this->getItem($product)['amount'] + $amount;
            $this->updateAmount($product, $newAmount);
            return;
        }

        $cart = $this->user->getCart();
        if(!$cart) {
            $cart = new \AppBundle\Entity\Cart();
            $this->user->setCart($cart);
            $cart->setUser($this->user);
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
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('product', $product));
        $cartProduct = $this->user->getCart()->getCartProducts()->matching($criteria)->first();
        if($cartProduct) {
            $this->user->getCart()->getCartProducts()->removeElement($cartProduct);
            $this->em->remove($cartProduct);
            $this->em->flush();
        }
    }

    /**
     * @param Product $product
     * @param $amount
     */
    public function updateAmount(Product $product, $amount)
    {
        if(!$this->checkIfProductInCart($product)) {
            $this->addToCart($product, $amount);
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('product', $product));
        $cartProduct = $this->user->getCart()->getCartProducts()->matching($criteria)->first();
        if($cartProduct) {
            $cartProduct->setAmount($amount);
            $this->em->flush();
        }
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
        $cart = new Cart();

        if(!$dbCart) {
            return $cart;
        }

        $cartProducts = $dbCart->getCartProducts();

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
        $cart = $this->em->getRepository(\AppBundle\Entity\Cart::class)->findOneBy([
            'user' => $this->user
        ]);
        $this->em->remove($cart);
        $this->em->flush();
    }

    /**
     * @param Product $product
     * @return bool
     */
    private function checkIfProductInCart(Product $product)
    {
        return $this->getItem($product) != null;
    }
}