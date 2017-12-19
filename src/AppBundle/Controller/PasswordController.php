<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\ResetPasswordType;
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

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $email
            ]);

            if(!$user) {
                //Render confirmation page even if user is not found
                return $this->render(':security:forgot_password_send.html.twig');
            }

            $user->setPasswordResetRequestedAt(new \DateTime('now'));
            TokenGenerator::generateToken($tokenForLink, $tokenHashForDatabase);
            //Store hashed token in database
            $user->setConfirmationToken($tokenHashForDatabase);
            $this->getDoctrine()->getManager()->flush();

            $message = (new \Swift_Message())
                ->setTo($user->getEmail())
                ->setFrom('noreply@tinfoilhats.com')
                ->setSubject('Password Reset')
                ->setBody(
                    $this->renderView(':email:reset_password.html.twig', [
                        'firstname' => $user->getFirstName(),
                        'lastname' => $user->getLastName(),
                        'resetLink' => $this->generateUrl('reset_password', ['token' => $tokenForLink], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]), 'text/html'
                );
            $mailer = $this->get('swiftmailer.mailer');
            $mailer->send($message);

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
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'confirmationToken' => $hashedToken
        ]);

        if(!$user) {
            return new Response("Token not found");
        }

        if(TokenGenerator::isTokenExpired($user->getPasswordResetRequestedAt())) {
            return new Response("Token expired");
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $plainPassword = $formData['plainPassword'];
            $password = $this->get('security.password_encoder')->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $user->setPasswordResetRequestedAt(null);
            $user->setConfirmationToken(null);
            $this->getDoctrine()->getManager()->flush();
            $this->get('request_stack')->getCurrentRequest()->request->set('referer', '/');
            return $this->redirectToRoute('login');
        }

        return $this->render(':security:reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
