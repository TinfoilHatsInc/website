<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Messaging\Command\ForgotPassword;
use AppBundle\Messaging\Command\ResetPassword;
use AppBundle\Util\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route(path="/password")
 *
 * Class PasswordController
 * @package AppBundle\Controller
 */
class PasswordController extends Controller
{
    /**
     * @Route(path="/", name="change_password")
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
                    'form' => $form->createView(),
                    'error' => 'Invalid password'
                ]);
            }
            $this->get('command_bus')->handle(new ResetPassword($user, $form['newPassword']->getData()));
            return $this->redirectToRoute('my_profile');
        }

        return $this->render(':profile:edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/forgot", name="forgot_password")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $email = $formData['email'];
            if(!isset($email)) {
                return $this->render(':security:forgot_password.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            $this->get('command_bus')->handle(new ForgotPassword($email));
            return $this->render(':security:forgot_password_send.html.twig');
        }

        return $this->render(':security:forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/reset/{token}", name="reset_password")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param $token string Original reset token
     * @return Response
     */
    public function resetPasswordAction(Request $request, $token)
    {
        if(!$token && TokenGenerator::isTokenValid($token)) {
            return new Response("Invalid reset token");
        }

        $hashedToken = TokenGenerator::calculateTokenHash($token);
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'confirmationToken' => $hashedToken
        ]);

        if(!$user) {
            return new Response("Invalid reset token");
        }

        if(TokenGenerator::isTokenExpired($user->getTokenCreatedAt())) {
            return new Response("Token expired");
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $plainPassword = $formData['plainPassword'];
            $this->get('command_bus')->handle(new ResetPassword($user, $plainPassword));
            return $this->redirectToRoute('login');
        }

        return $this->render(':security:reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
