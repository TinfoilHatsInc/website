<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 22-11-17
 * Time: 14:58
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Product
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="product")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable()
 */
class Product
{
    use Timestampable;

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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * Price in cents
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="product_icon", fileNameProperty="iconName")
     */
    private $iconFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $iconName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feature", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $features;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chub_required", type="boolean")
     */
    private $chubRequired = true;

    public function __construct()
    {
        $this->features = new ArrayCollection();
    }

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return $this
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->setUpdatedAt();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getIconFile()
    {
        return $this->iconFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $icon
     *
     * @return $this
     */
    public function setIconFile(File $icon = null)
    {
        $this->iconFile = $icon;

        if ($icon) {
            $this->setUpdatedAt();
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * @param string $iconName
     *
     * @return $this
     */
    public function setIconName($iconName)
    {
        $this->iconName = $iconName;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param ArrayCollection $features
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * @param Feature $feature
     */
    public function addFeature(Feature $feature)
    {
        $feature->setProduct($this);
        $this->features->add($feature);
    }

    /**
     * @param Feature $feature
     */
    public function removeFeature(Feature $feature)
    {
        $this->features->removeElement($feature);
    }

    /**
     * @return bool
     */
    public function isChubRequired()
    {
        return $this->chubRequired;
    }

    /**
     * @param bool $chubRequired
     */
    public function setChubRequired($chubRequired)
    {
        $this->chubRequired = $chubRequired;
    }
}