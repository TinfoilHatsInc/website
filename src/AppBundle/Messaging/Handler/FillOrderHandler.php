<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 13:21
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProduct;
use AppBundle\Messaging\Command\FillOrder;
use AppBundle\Services\MollieService;
use AppBundle\Services\ShoppingCart\ShoppingCartService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class FillOrderHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ShoppingCartService
     */
    private $shoppingCartService;

    public function __construct(EntityManager $em, Router $router, ShoppingCartService $shoppingCartService)
    {
        $this->em = $em;
        $this->router = $router;
        $this->shoppingCartService = $shoppingCartService;
    }

    public function handle(FillOrder $order)
    {
        /** @var Order $order */
        $order = $order->getOrder();

        $cart = $this->shoppingCartService->getCartModel();
        $productArray = [];
        foreach ($cart->getProducts() as $product) {
            $orderProduct = new OrderProduct();
            $orderProduct->setProduct($product['product']);
            $orderProduct->setAmount($product['amount']);
            $orderProduct->setOrder($order);
            $this->em->persist($orderProduct);
            $productArray[] = $orderProduct;
        }

        $order->setOrderedProducts(new ArrayCollection($productArray));
        $this->em->persist($order);
        $this->em->flush();
        $this->shoppingCartService->clearCart();
    }
}