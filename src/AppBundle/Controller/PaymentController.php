<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Messaging\Command\UpdatePaymentStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(path="/payment")
 *
 * Class PaymentController
 * @package AppBundle\Controller
 */
class PaymentController extends Controller
{
    /**
     * @Route(path="/mollie", name="mollie_webhook")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function updatePaymentStatusAction(Request $request)
    {
        $this->get('command_bus')->handle(new UpdatePaymentStatus($request->get('id')));
        return new Response();
    }
}
