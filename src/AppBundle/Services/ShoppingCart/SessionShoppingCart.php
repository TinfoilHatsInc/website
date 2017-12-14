<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 15:38
 */

namespace AppBundle\Services\ShoppingCart;

use AppBundle\Model\Cart;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use function Sodium\add;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionShoppingCart implements ShoppingCart
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ShoppingCartSessionService constructor.
     * @param Session $session
     * @param EntityManager $em
     */
    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * @param Product $product
     * @return array|null
     */
    public function getItem(Product $product)
    {
        $this->session->start();
        $cart = $this->session->get('cart');
        if(!$cart) {
            return [];
        }
        if(!isset($cart[$product->getId()])) {
            return [];
        }
        $product = $this->em->getRepository(Product::class)->find($cart['productId']);
        return [
            'product' => $product,
            'amount' => $cart['amount']
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

        if(!is_int($amount)) {
            throw new \InvalidArgumentException(sprintf("Amount must be an integer, got type %s", gettype($amount)));
        }

        $this->session->start();
        $currentCart = $this->session->get('cart');
        $currentCart[$product->getId()] = [
            'productId' => $product->getId(),
            'amount' => $amount
        ];
        $this->session->set('cart', $currentCart);
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
        if(!is_int($amount)) {
            throw new \InvalidArgumentException(sprintf("Amount must be an integer, got type %s", gettype($amount)));
        }

        if(!$this->checkIfProductInCart($product)) {
            $this->addToCart($product, $amount);
        }

        $this->session->start();
        $currentCart = $this->session->get('cart');
        $currentCart[$product->getId()]['amount'] = $amount;
        $this->session->set('cart', $currentCart);
    }

    /**
     * Build a Cart object from the data stored in session
     *
     * @return Cart
     */
    public function getCartModel()
    {
        $this->session->start();

        $cart = new Cart();
        if(!$this->session->has('cart')) {
            return $cart;
        }

        $productRepository = $this->em->getRepository(Product::class);
        $cartFromSession = $this->session->get('cart');
        foreach ($cartFromSession as $item) {
            $product = $productRepository->find($item['productId']);
            $cart->addProduct($product, $item['amount']);
        }
        return $cart;
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $this->session->start();
        $this->session->set('cart', []);
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