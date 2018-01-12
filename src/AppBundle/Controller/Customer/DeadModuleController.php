<?php

namespace AppBundle\Controller\Customer;

use AppBundle\Entity\DeadModule;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DeadModuleController
 * @package AppBundle\Controller\Customer
 *
 * @Route(path="/customer/deadmodules")
 * @Security("has_role('ROLE_CUSTOMER')")
 */
class DeadModuleController extends Controller
{
    /**
     * @Route(path="/", name="customer_dead_modules")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $deadModules = $this->getDoctrine()->getRepository(DeadModule::class)->findAllForUser($this->getUser());

        return $this->render(':customer/dead_module:index.html.twig', [
            'deadModules' => $deadModules
        ]);
    }
}
