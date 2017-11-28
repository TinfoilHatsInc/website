<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MollieController extends Controller
{
    /**
     * @Route(path="/mollie", name="mollie_webhook")
     *
     * @param Request $request
     */
    public function mollieWebhookAction(Request $request)
    {
        var_dump($request->getContent());die;
    }
}
