<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageController
 * @package AppBundle\Controller
 *
 * Renders pages for company site
 */
class PageController extends Controller
{
    /**
     * @Route("/products", name="products")
     */
    public function productsAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            ':pages:products.html.twig', [
                'products' => $products
        ]);
    }
}
