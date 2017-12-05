<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createOrderAction(Request $request)
    {
        $order = new Order();
        /** @var User $user */
        $user = $this->getUser();
        $order->setUser($this->getDoctrine()->getRepository(User::class)->find(1));
        if($this->isGranted('ROLE_USER')) {
            $order->setCity($user->getCity());
            $order->setAddress($user->getAddress());
            $order->setZipcode($user->getZipcode());
            $order->setCountry($user->getCountry());
        }

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            $mollieService = $this->get('tinfoil.service.mollie');
            $redirectUrl = $mollieService->createPayment($order);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($redirectUrl);
        }

        return $this->render(':order:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/{id}", name="show_order")
     *
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOrderAction(Order $order)
    {
        return $this->render(':order:payment_complete.html.twig', [
            'order' => $order
        ]);
    }
}
