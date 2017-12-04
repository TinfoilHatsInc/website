<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
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
     * @Route(path="/create", name="create_payment")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createOrderAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('order_csrf_token', $request->get('order_csrf_token'))) {
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

        $cart = $this->get('tinfoil.service.cart')->buildModelFromSession();
        $mollieService = $this->get('tinfoil.service.mollie');

        $productArray = [];
        foreach ($cart->getProducts() as $product) {
            $orderedProduct =
            $productArray[] = $product['product'];
        }

        $order = new Order();
        $order->setOrderedProducts(new ArrayCollection($productArray));
        $order->setUser($this->getUser());
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();
        $redirectUrl = $mollieService->createPayment($order);

        return $this->redirect($redirectUrl);
    }
}
