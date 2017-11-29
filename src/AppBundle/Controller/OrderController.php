<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/orders")
 *
 * Class OrderController
 * @package AppBundle\Controller
 */
class OrderController extends Controller
{
    /**
     * @Route(path="/create", name="create_order")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createOrderAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $mollieService = $this->get('tinfoil.service.mollie');

        $order = new Order();
        $order->setProducts(new ArrayCollection($products));
        $order->setUser($this->getUser());
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();
        $redirectUrl = $mollieService->createPayment($order);

        return $this->redirect($redirectUrl);
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
