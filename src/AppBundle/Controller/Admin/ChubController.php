<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Chub;
use AppBundle\Form\ChubType;
use AppBundle\Messaging\Command\RegisterChub;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ChubController
 * @package AppBundle\Controller
 *
 * @Route(path="/admin/chub")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ChubController extends Controller
{
    /**
     * @Route(path="/", name="admin_chub")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $chubs = $this->getDoctrine()->getRepository(Chub::class)->findAll();
        return $this->render(':admin/chub:index.html.twig', [
            'chubs' => $chubs
        ]);
    }

    /**
     * @Route(path="/register", name="admin_chub_register")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerChub(Request $request)
    {
        $form = $this->createForm(ChubType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form['user']->getData();
            $this->get('command_bus')->handle(new RegisterChub($user));
        }

        return $this->render(':admin/chub:register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
