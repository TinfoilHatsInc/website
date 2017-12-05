<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 4-12-17
 * Time: 12:42
 */

namespace AppBundle\Model;


use AppBundle\Entity\Product;

class Cart
{
    /**
     * @var array
     */
    private $products;

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function addProduct(Product $product, $amount)
    {
        $this->products[] = [
            'product' => $product,
            'amount' => $amount
        ];
    }
}