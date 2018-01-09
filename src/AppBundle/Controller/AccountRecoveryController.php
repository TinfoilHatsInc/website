<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Messaging\Command\RecoverDisabledAccount;
use AppBundle\Util\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AccountRecoveryController extends Controller
{
    /**
     * @Route(path="/recover/{token}", name="recover_account")
     * @Method({"GET"})
     *
     * @param $token
     * @return Response
     */
    public function recoverAccountAction($token)
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

        $this->get('command_bus')->handle(new RecoverDisabledAccount($user));
        return $this->render(':security:account_recovered.html.twig');
    }
}
