<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 14:59
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Notification
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chub", inversedBy="notifications")
     * @ORM\JoinColumn(name="chub_id", referencedColumnName="id")
     */
    private $chub;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $triggerName;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Snapshot", mappedBy="notification")
     */
    private $snapshots;

    public function __construct()
    {
        $this->snapshots = new ArrayCollection();
    }

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
    public function getTriggerName()
    {
        return $this->triggerName;
    }

    /**
     * @param string $triggerName
     */
    public function setTriggerName($triggerName)
    {
        $this->triggerName = $triggerName;
    }

    /**
     * @return ArrayCollection
     */
    public function getSnapshots()
    {
        return $this->snapshots;
    }

    /**
     * @param ArrayCollection $snapshots
     */
    public function setSnapshots($snapshots)
    {
        $this->snapshots = $snapshots;
    }
}