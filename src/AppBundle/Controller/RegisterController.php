<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Messaging\Command\ConfirmAccount;
use AppBundle\Messaging\Command\RegisterUser;
use AppBundle\Util\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends Controller
{
    /**
     * @Route(path="/register", name="register")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->get('command_bus')->handle(new RegisterUser($user));

            return $this->render(':security:confirm_account.html.twig');
        }

        return $this->render(':security:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/confirm/{token}", name="confirm_account")
     * @Method({"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function confirmAccountAction(Request $request, $token)
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

        $this->get('command_bus')->handle(new ConfirmAccount($user));
        return $this->render(':security:account_confirmed.html.twig');
    }
}
