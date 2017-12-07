<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 7-12-17
 * Time: 11:20
 */

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class Referer extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('referer', [$this, 'getReferer'])
        ];
    }

    /**
     * @return array|string
     */
    public function getReferer()
    {
        return $this->request->getCurrentRequest()->headers->get('referer');
    }

}