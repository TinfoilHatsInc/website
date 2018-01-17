<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 17-1-18
 * Time: 15:37
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Order;
use AppBundle\Messaging\Command\UpdatePaymentStatus;
use Doctrine\ORM\EntityManager;

class UpdatePaymentStatusHandler
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Mollie_API_Client
     */
    private $mollieApi;

    public function __construct($apiKey, EntityManager $em)
    {
        $this->apiKey = $apiKey;
        $this->em = $em;
        $this->mollieApi = new \Mollie_API_Client();
    }

    public function handle(UpdatePaymentStatus $updatePaymentStatus)
    {
        $this->mollieApi->setApiKey($this->apiKey);
        $paymentId = $updatePaymentStatus->getPaymentId();
        $molliePayment = $this->mollieApi->payments->get($paymentId);
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'paymentId' => $paymentId
        ]);
        $order->setPaymentStatus($molliePayment->status);
        $this->em->flush();
    }
}