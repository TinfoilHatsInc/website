<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\OrderType;
use AppBundle\Messaging\Command\CreatePayment;
use AppBundle\Messaging\Command\FillOrder;
use AppBundle\Messaging\Command\SendOrderEmail;
use AppBundle\Security\OrderVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/order")
 *
 * Class OrderController
 * @package AppBundle\Controller
 */
class OrderController extends Controller
{
    /**
     * @Route(path="/", name="create_order")
     * @Method({"POST", "GET"})
     * @Security("has_role('ROLE_CUSTOMER')")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createOrderAction(Request $request)
    {
        $order = new Order();
        /** @var User $user */
        $user = $this->getUser();
        $order->setUser($user);
        if($user->getAddress()) {
            $order->setCity($user->getAddress()->getCity());
            $order->setStreet($user->getAddress()->getStreet());
            $order->setHouseNumber($user->getAddress()->getHouseNumber());
            $order->setZipcode($user->getAddress()->getZipcode());
            $order->setCountry($user->getAddress()->getCountry());
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle(new FillOrder($order));
            $this->get('command_bus')->handle(new CreatePayment($order));
            $this->get('command_bus')->handle(new SendOrderEmail($order));
            return $this->redirect($order->getPaymentUrl());
        }

        return $this->render(':order:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/{id}", name="show_order")
     * @Method({"GET"})
     * @Security("has_role('ROLE_CUSTOMER')")
     *
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOrderAction(Order $order)
    {
        $this->denyAccessUnlessGranted(OrderVoter::VIEW, $order);

        return $this->render(':order:show.html.twig', [
            'order' => $order
        ]);
    }
}
