<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 4-12-17
 * Time: 11:53
 */

namespace AppBundle\Services;


use AppBundle\Entity\Product;
use AppBundle\Model\Cart;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ShoppingCartService
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
     * ShoppingCartService constructor.
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
     * @param $amount
     */
    public function addToCart(Product $product, $amount)
    {
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
    public function buildModelFromSession()
    {
        $this->session->start();
        $productRepository = $this->em->getRepository(Product::class);
        $cart = new Cart();
        $cartFromSession = $this->session->get('cart');
        foreach ($cartFromSession as $item) {
            $product = $productRepository->find($item['productId']);
            $cart->addProduct($product, $item['amount']);
        }
        return $cart;
    }

    /**
     * @param Cart $cart
     * @return int
     */
    public static function calculateCartTotal(Cart $cart)
    {
        $total = 0;
        foreach ($cart->getProducts() as $item) {
            /** @var Product $product */
            $product = $item['product'];
            $total += $product->getPrice() * $item['amount'];
        }
        return $total;
    }
}