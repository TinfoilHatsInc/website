<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 29-11-17
 * Time: 14:14
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Country
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="country")
 * @ORM\Entity()
 */
class Country
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $ISO;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $niceName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $ISO3;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numCode;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phoneCode;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getISO()
    {
        return $this->ISO;
    }

    /**
     * @param string $ISO
     */
    public function setISO($ISO)
    {
        $this->ISO = $ISO;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNiceName()
    {
        return $this->niceName;
    }

    /**
     * @param string $niceName
     */
    public function setNiceName($niceName)
    {
        $this->niceName = $niceName;
    }

    /**
     * @return string
     */
    public function getISO3()
    {
        return $this->ISO3;
    }

    /**
     * @param string $ISO3
     */
    public function setISO3($ISO3)
    {
        $this->ISO3 = $ISO3;
    }

    /**
     * @return int
     */
    public function getNumCode()
    {
        return $this->numCode;
    }

    /**
     * @param int $numCode
     */
    public function setNumCode($numCode)
    {
        $this->numCode = $numCode;
    }

    /**
     * @return int
     */
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }

    /**
     * @param int $phoneCode
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phoneCode = $phoneCode;
    }
}