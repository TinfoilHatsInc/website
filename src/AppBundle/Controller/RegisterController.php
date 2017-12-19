<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->eraseCredentials();
            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy([
                'name' => 'ROLE_CUSTOMER'
            ]);
            $user->setRoles([$role]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            $targetPath = $request->get('_target_path');
            if(!$targetPath) {
                return $this->redirectToRoute('my_profile');
            }

            //Check target path
            $targetPath = $request->get('_target_path');
            $baseUrl = $this->generateUrl('home', [], UrlGeneratorInterface::ABSOLUTE_URL);
            //Base found at first position
            if(strpos($targetPath, $baseUrl) !== 0) {
                return $this->redirectToRoute('my_profile');
            }
            return $this->redirect($request->get('_target_path'));
        }

        return $this->render(':security:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function confirmAccountAction(Request $request)
    {
        //TODO implement
    }
}
