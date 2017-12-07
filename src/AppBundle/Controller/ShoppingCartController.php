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
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

        $product = $request->get('product_id');
        $amount = $request->get('amount');
        $amount = intval($amount);
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($product);

        try {
            $this->get('tinfoil.service.cart')->addToCart($product, $amount);
        } catch (\InvalidArgumentException $iE) {
            dump($iE->getMessage());die;
            return $this->redirectToRoute('shopping_cart');
        }
        return $this->redirectToRoute('shopping_cart');
    }

    /**
     * @Route(path="/remove", name="shopping_cart_remove")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function removeFromCartAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('remove_from_cart_token', $request->get('csrf_token'))) {
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

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
     * @return Response
     */
    public function updateAmountAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('update_cart_token', $request->get('csrf_token'))) {
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

        $product = $request->get('product_id');
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($product);
        $amount = $request->get('amount');
        $amount = intval($amount);

        try {
            $this->get('tinfoil.service.cart')->updateAmount($product, $amount);
        } catch (\InvalidArgumentException $iE) {
            return $this->redirectToRoute('shopping_cart');
        }
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
