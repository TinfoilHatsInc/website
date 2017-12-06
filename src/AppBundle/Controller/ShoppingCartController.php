<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Model\Cart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(path="/cart")
 *
 * Class ShoppingCartController
 * @package AppBundle\Controller
 */
class ShoppingCartController extends Controller
{
    /**
     * @Route(path="/", name="shopping_cart")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCartAction()
    {
        $cart = $this->get('tinfoil.service.cart')->buildModelFromSession();
        return $this->render('shoppingcart/show.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route(path="/add", name="shopping_cart_add")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addToCartAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('add_to_cart_token', $request->get('csrf_token'))) {
            return new Response("Invalid CSRF", Response::HTTP_BAD_REQUEST);
        }

        $product = $request->get('product_id');
        $amount = $request->get('amount');

        if(empty($product) || empty($amount)) {
            return new Response("Specify product and amount", Response::HTTP_BAD_REQUEST);
        }

        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($product);
        $this->get('tinfoil.service.cart')->addToCart($product, $amount);
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route(path="/remove", name="shopping_cart_remove")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromCartAction(Request $request)
    {
        $product = $request->get('product_id');
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($product);
        $this->get('tinfoil.service.cart')->removeFromCart($product);
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route(path="/update", name="shopping_cart_update")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAmountAction(Request $request)
    {
        $product = $request->get('product_id');
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($product);
        $amount = $request->get('amount');
        $this->get('tinfoil.service.cart')->updateAmount($product, $amount);
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route(path="/clear", name="shopping_cart_clear")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function clearCartAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('clear_cart_token', $request->get('csrf_token'))) {
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

        $this->get('tinfoil.service.cart')->clearCart();
        return $this->redirectToRoute('shopping_cart');
    }
}
