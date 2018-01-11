<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chub;
use AppBundle\Form\ChubAliasType;
use AppBundle\Security\ChubVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route(path="/edit/{id}", name="customer_chubs_edit")
     * @Method({"GET", "POST"})
     *
     * @param Chub $chub
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Chub $chub, Request $request)
    {
        $this->denyAccessUnlessGranted(ChubVoter::EDIT, $chub);

        $form = $this->createForm(ChubAliasType::class, $chub);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('customer_chubs_show', ['id' => $chub->getId()]);
        }

        return $this->render(':customer/chub:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
