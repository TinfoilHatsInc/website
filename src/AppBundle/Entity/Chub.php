<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 11:24
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Chub
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="chub")
 * @ORM\HasLifecycleCallbacks()
 */
class Chub
{
    use Timestampable;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $alias;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ownedChubs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="chub")
     */
    private $notifications;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $alarmEnabled = false;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
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
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param ArrayCollection $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @return bool
     */
    public function isAlarmEnabled()
    {
        return $this->alarmEnabled;
    }

    /**
     * @param bool $alarmEnabled
     */
    public function setAlarmEnabled($alarmEnabled)
    {
        $this->alarmEnabled = $alarmEnabled;
    }
}