<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PasswordController extends Controller
{
    /**
     * @Route(path="/password", name="change_password")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_CUSTOMER')")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$this->get('security.password_encoder')->isPasswordValid($user, $form['oldPassword']->getData())) {
                return $this->render(':profile:edit_password.html.twig', [
                    'form' => $form->addError(new FormError("Incorrect Password"))->createView()
                ]);
            }
            $newPassword = $this->get('security.password_encoder')->encodePassword($user, $form['plainPassword']->getData());
            $user->setPassword($newPassword);
            $user->eraseCredentials();
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('my_profile');
        }

        return $this->render(':profile:edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function forgotPasswordAction(Request $request)
    {

    }
}
