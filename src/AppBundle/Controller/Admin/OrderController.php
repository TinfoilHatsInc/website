<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 18-12-17
 * Time: 13:32
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class OrderController
 * @package AppBundle\Controller\Admin
 *
 * @Route(path="/admin/orders")
 * @Security("has_role('ROLE_ADMIN')")
 */
class OrderController extends Controller
{
    /**
     * @Route(path="/", name="admin_orders")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route(path="/{id}", name="admin_orders_show")
     * @Method({"GET"})
     *
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOrderAction(Order $order)
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order
        ]);
    }
}