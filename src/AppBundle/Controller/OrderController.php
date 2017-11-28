<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $mollieService->createPayment($order);

        return new Response(json_encode($order));
    }

    /**
     * @Route(path="/{id}", name="show_order")
     *
     * @param Order $order
     */
    public function showOrderAction(Order $order)
    {
        var_dump($order);die;
    }
}
