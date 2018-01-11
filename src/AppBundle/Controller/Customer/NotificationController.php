<?php

namespace AppBundle\Controller\Customer;


use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use AppBundle\Security\NotificationVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class NotificationController
 * @package AppBundle\Controller\Customer
 *
 * @Security("has_role('ROLE_CUSTOMER')")
 * @Route(path="/customer/notification")
 */
class NotificationController extends Controller
{
    /**
     * @Route(path="/", name="customer_notifications")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAllForUser($user);

        return $this->render(':customer/notifications:index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route(path="/{id}", name="customer_notifications_show")
     * @Method({"GET"})
     *
     * @param $notification
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Notification $notification)
    {
        $this->denyAccessUnlessGranted(NotificationVoter::VIEW, $notification);

        return $this->render(':customer/notifications:show.html.twig', [
            'notification' => $notification
        ]);
    }
}
