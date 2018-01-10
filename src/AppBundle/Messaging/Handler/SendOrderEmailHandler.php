<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 14:14
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Messaging\Command\SendOrderEmail;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class SendOrderEmailHandler
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    public function __construct(\Swift_Mailer $mailer, Router $router, TwigEngine $twigEngine)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twigEngine = $twigEngine;
    }

    public function handle(SendOrderEmail $sendOrderEmail)
    {
        $order = $sendOrderEmail->getOrder();
        $user = $order->getUser();

        $message = (new \Swift_Message())
            ->setTo($user->getEmail())
            ->setFrom('noreply@tinfoilhats.com')
            ->setSubject('Order Placed | Tinfoil Hats, inc.')
            ->setBody(
                $this->twigEngine->render(':email:order.html.twig', [
                    'firstname' => $user->getFirstName(),
                    'lastname' => $user->getLastName(),
                    'profile' => $this->router->generate('my_profile_orders', [], UrlGeneratorInterface::ABSOLUTE_URL)
                ]), 'text/html'
            );
        $this->mailer->send($message);
    }
}