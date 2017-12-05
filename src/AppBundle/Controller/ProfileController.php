<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ProfileController
 * @package AppBundle\Controller
 *
 * @Route(path="/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route(path="/", name="my_profile")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProfileAction()
    {
        $user = $this->getUser();
        $order = $this->getDoctrine()->getRepository(Order::class)->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC'],
            ['limit' => 1]);
        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'order' => $order
        ]);
    }
}
