<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MollieController extends Controller
{
    /**
     * @Route(path="/mollie", name="mollie_webhook")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function mollieWebhookAction(Request $request)
    {
        $this->get('tinfoil.service.mollie')->processPayment($request->get('id'));
        $this->get('logger')->debug($request->get('id'));
        return new Response();
    }
}
