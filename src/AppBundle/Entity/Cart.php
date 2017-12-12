<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-12-17
 * Time: 15:57
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Cart
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="cart")
 */
class Cart
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="cart")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var CartProduct
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CartProduct", mappedBy="cart")
     */
    private $cartProducts;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return CartProduct
     */
    public function getCartProducts()
    {
        return $this->cartProducts;
    }

    /**
     * @param CartProduct $cartProducts
     */
    public function setCartProducts($cartProducts)
    {
        $this->cartProducts = $cartProducts;
    }
}