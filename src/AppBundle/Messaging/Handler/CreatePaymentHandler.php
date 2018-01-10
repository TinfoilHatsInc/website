<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 13:32
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Order;
use AppBundle\Messaging\Command\CreatePayment;
use AppBundle\Util\TotalCalculator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class CreatePaymentHandler
{
    /**
     * @var \Mollie_API_Client
     */
    private $mollieApi;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    public function __construct($apiKey, EntityManager $em, Router $router)
    {
        $this->mollieApi = new \Mollie_API_Client();
        $this->apiKey = $apiKey;
        $this->em = $em;
        $this->router = $router;
    }

    public function handle(CreatePayment $createPayment)
    {
        $order = $createPayment->getOrder();

        $this->mollieApi->setApiKey($this->apiKey);
        $molliePayment = $this->mollieApi->payments->create([
            "amount"    => TotalCalculator::calculateFromOrder($order) / 100,
            "description"   => $this->createOrderDescription($order),
            "redirectUrl"   => $this->router->generate('show_order', ['id' => $order->getId()], Router::ABSOLUTE_URL),
            "webhookUrl"    => $this->router->generate('mollie_webhook', [], Router::ABSOLUTE_URL)
        ]);

        $molliePayment = $this->mollieApi->payments->get($molliePayment->id);

        $order->setPaymentId($molliePayment->id);
        $order->setPaymentStatus($molliePayment->status);
        $order->setPaymentUrl($molliePayment->getPaymentUrl());
        $this->em->flush();
    }

    private function createOrderDescription(Order $order)
    {
        //TODO maybe something more useful
        return sprintf("Order #%s", $order->getId());
    }
}