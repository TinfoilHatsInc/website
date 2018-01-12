<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 12-1-18
 * Time: 10:29
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class DeadModule
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeadModuleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DeadModule
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Chub
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chub", inversedBy="deadModules")
     * @ORM\JoinColumn(name="chub_id", referencedColumnName="id")
     */
    private $chub;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $moduleName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Chub
     */
    public function getChub()
    {
        return $this->chub;
    }

    /**
     * @param Chub $chub
     */
    public function setChub($chub)
    {
        $this->chub = $chub;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }
}