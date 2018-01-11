<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chub;
use AppBundle\Security\ChubVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ChubController
 * @package AppBundle\Controller
 *
 * @Security("has_role('ROLE_CUSTOMER')")
 * @Route(path="/customer/chub")
 */
class ChubController extends Controller
{
    /**
     * @Route(path="/", name="customer_chubs")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $chubs = $this->getDoctrine()->getRepository(Chub::class)->findBy([
            'user' => $this->getUser()
        ], [
            'createdAt' => 'DESC'
        ]);
        return $this->render(':customer/chub:index.html.twig', [
            'chubs' => $chubs
        ]);
    }

    /**
     * @Route(path="/{id}", name="customer_chubs_show")
     * @Method({"GET"})
     *
     * @param Chub $chub
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Chub $chub)
    {
        $this->denyAccessUnlessGranted(ChubVoter::VIEW, $chub);

        return $this->render(':customer/chub:show.html.twig', [
            'chub' => $chub
        ]);
    }
}
