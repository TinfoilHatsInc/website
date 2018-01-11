<?php

namespace AppBundle\Controller\Customer;

use AppBundle\Entity\Chub;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 * @package AppBundle\Controller
 *
 * @Security("has_role('ROLE_CUSTOMER')")
 * @Route(path="/customer")
 */
class DashboardController extends Controller
{
    /**
     * @Route(path="/", name="customer_dashboard")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $chubs = $this->getDoctrine()->getRepository(Chub::class)->findBy([
            'user' => $user
        ], [
            'createdAt' => 'DESC'
        ]);
        return $this->render(':customer:index.html.twig', [
            'chubs' => $chubs
        ]);
    }
}
