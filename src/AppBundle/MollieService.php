<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 28-11-17
 * Time: 10:17
 */

namespace AppBundle;


use AppBundle\Entity\Order;
use AppBundle\Entity\User;
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
     * MollieService constructor.
     * @param string $apiKey
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct($apiKey, EntityManager $em, Router $router)
    {
        $this->mollieApi = new \Mollie_API_Client();
        $this->mollieApi->setApiKey($apiKey);
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param Order $order
     */
    public function createPayment(Order $order)
    {
        $molliePayment = $this->mollieApi->payments->create([
            "amount"    => 10,
            "description"   => "test payment",
            "redirectUrl"   => $this->router->generate('show_order', ['id' => $order->getId()], Router::ABSOLUTE_URL),
            "webhookUrl"    => $this->router->generate('mollie_webhook', [], Router::ABSOLUTE_URL)
        ]);

        $molliePayment = $this->mollieApi->payments->get($molliePayment->id);

        $order->setPaymentId($molliePayment->id);
        $order->setPaymentStatus($molliePayment->status);
        $this->em->flush();
    }

    /**
     * @param $paymentId
     */
    public function processPayment($paymentId)
    {
        $molliePayment = $this->mollieApi->payments->get($paymentId);
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'paymentId' => $paymentId
        ]);
        $order->setPaymentStatus($molliePayment->status);
        $this->em->flush();
    }
}