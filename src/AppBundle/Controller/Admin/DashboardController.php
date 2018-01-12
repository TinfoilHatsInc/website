<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Chub;
use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route(path="/admin")
 *
 * Class AdminController
 * @package AppBundle\Controller\Admin
 */
class DashboardController extends Controller
{

    /**
     * @Route(path="/", name="admin_dashboard")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $totalOrders = count($this->getDoctrine()->getRepository(Order::class)->findAll());
        $totalRegisteredChubs = count($this->getDoctrine()->getRepository(Chub::class)->findAll());
        $totalProducts = count($this->getDoctrine()->getRepository(Product::class)->findAll());
        return $this->render(':admin:index.html.twig', [
            'totalOrders' => $totalOrders,
            'totalRegisteredChubs' => $totalRegisteredChubs,
            'totalProducts' => $totalProducts
        ]);
    }
}
