<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route(path="/admin/products")
 *
 * Class ProductController
 * @package AppBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @Route(path="/", name="admin_products")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            ':admin/product:index.html.twig', [
                'products' => $products
        ]);
    }

    /**
     * @Route(path="/add", name="admin_products_add")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin_products_show', ['id' => $product->getId()]);
        }

        return $this->render(':admin/product:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/{id}", name="admin_products_show")
     * @Method("GET")
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Product $product)
    {
        return $this->render(':admin/product:show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route(path="/edit/{id}", name="admin_products_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $originalFeatures = new ArrayCollection();
        foreach ($product->getFeatures() as $feature) {
            $originalFeatures->add($feature);
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Feature $originalFeature */
            foreach ($originalFeatures as $originalFeature) {
                if(!$product->getFeatures()->contains($originalFeature)) {
                    $product->removeFeature($originalFeature);
                    $originalFeature->setProduct(null);
                }
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin_products_show', ['id' => $product->getId()]);
        }

        return $this->render(':admin/product:edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route(path="/remove", name="admin_products_remove")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeAction(Request $request)
    {
        if(!$this->isCsrfTokenValid('remove_product_token', $request->get('csrf_token'))) {
            return new Response("Invalid CSRF token", Response::HTTP_BAD_REQUEST);
        }

        $productId = $request->get('product_id');
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
        if(!$product) {
            return $this->redirectToRoute('admin_products');
        }

        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_products');
    }
}
