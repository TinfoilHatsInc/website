<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 28-11-17
 * Time: 10:17
 */

namespace AppBundle\Services;


use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class MollieService
{
    /**
     * @var \Mollie_API_Client
     */
    private $mollieApi;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ShoppingCartSessionService
     */
    private $shoppingCartService;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * MollieService constructor.
     * @param string $apiKey
     * @param EntityManager $em
     * @param Router $router
     * @param ShoppingCartService $shoppingCartService
     */
    public function __construct($apiKey, EntityManager $em, Router $router, ShoppingCartSessionService $shoppingCartService)
    {
        $this->mollieApi = new \Mollie_API_Client();
        $this->em = $em;
        $this->router = $router;
        $this->shoppingCartService = $shoppingCartService;
        $this->apiKey = $apiKey;
    }

    /**
     * @param Order $order
     *
     * @return null|string payment url
     */
    public function createPayment(Order $order)
    {
        $cart = $this->shoppingCartService->getCartModel();

        $productArray = [];
        foreach ($cart->getProducts() as $product) {
            $orderProduct = new OrderProduct();
            $orderProduct->setProduct($product['product']);
            $orderProduct->setAmount($product['amount']);
            $orderProduct->setOrder($order);
            $this->em->persist($orderProduct);
        }

        $order->setOrderedProducts(new ArrayCollection($productArray));
        $this->mollieApi->setApiKey($this->apiKey);
        $molliePayment = $this->mollieApi->payments->create([
            "amount"    => ShoppingCartSessionService::calculateCartTotal($cart) / 100,
            "description"   => $this->createOrderDescription($order),
            "redirectUrl"   => $this->router->generate('show_order', ['id' => $order->getId()], Router::ABSOLUTE_URL),
            "webhookUrl"    => $this->router->generate('mollie_webhook', [], Router::ABSOLUTE_URL)
        ]);

        $molliePayment = $this->mollieApi->payments->get($molliePayment->id);

        $order->setPaymentId($molliePayment->id);
        $order->setPaymentStatus($molliePayment->status);
        $this->em->flush();
        return $molliePayment->getPaymentUrl();
    }

    private function createOrderDescription(Order $order)
    {
        //TODO sanitze
        return sprintf("Order #%s", $order->getId());
    }

    /**
     * @param $paymentId
     */
    public function processPayment($paymentId)
    {
        $this->mollieApi->setApiKey($this->apiKey);
        $molliePayment = $this->mollieApi->payments->get($paymentId);
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'paymentId' => $paymentId
        ]);
        $order->setPaymentStatus($molliePayment->status);
        $this->em->flush();
    }
}