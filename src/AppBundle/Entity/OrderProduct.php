<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 4-12-17
 * Time: 12:45
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderProduct
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="order_product")
 */
class OrderProduct
{
    /**
     * @var Order
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="orderedProducts")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    private $order;

    /**
     * @var Product
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", nullable=false)
     */
    private $amount;

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}