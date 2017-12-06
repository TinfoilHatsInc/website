<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Form\ShippingDetailsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileController
 * @package AppBundle\Controller
 *
 * @Route(path="/profile")
 * @Security("has_role('ROLE_CUSTOMER')")
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
        $order = reset($order);

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'order' => $order
        ]);
    }

    /**
     * @Route(path="/profile/orders", name="my_profile_orders")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOrdersAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $this->getDoctrine()->getRepository(Order::class)->findBy([
            'user' => $user
        ], [
            'updatedAt' => 'DESC'
        ]);

        return $this->render(':profile:orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route(path="/edit/personal", name="my_profile_edit_personal")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPersonalDetailsAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('my_profile');
        }

        return $this->render(':profile:edit_personal_details.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/edit/shipping", name="my_profile_edit_shipping")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editShippingDetailsAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $address = $user->getAddress();
        if(!$address) {
            $address = new Address();
        }

        $form = $this->createForm(ShippingDetailsType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setAddress($address);
            $em->persist($user->getAddress());
            $em->flush();
            return $this->redirectToRoute('my_profile');
        }

        return $this->render(':profile:edit_shipping_details.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
