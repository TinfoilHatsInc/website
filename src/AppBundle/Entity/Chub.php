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

    const ALARM_STATUS_OFF = 'off';
    const ALARM_STATUS_ARMED = 'armed';
    const ALARM_STATUS_ON = 'on';

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
     * @ORM\Column(type="string", nullable=false)
     */
    private $chubKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="alarm_status", type="string", columnDefinition="ENUM('off', 'armed', 'on')")
     */
    private $alarmStatus;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DeadModule", mappedBy="chub")
     */
    private $deadModules;

    public function __construct($key)
    {
        if(!is_string($key)) {
            throw new \InvalidArgumentException(sprintf("Key must be of type string, %s given", gettype($key)));
        }
        $this->chubKey = $key;
        $this->alarmStatus = self::ALARM_STATUS_OFF;
        $this->notifications = new ArrayCollection();
        $this->deadModules = new ArrayCollection();
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
    public function getKey()
    {
        return $this->chubKey;
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
     * @return string
     */
    public function getAlarmStatus()
    {
        return $this->alarmStatus;
    }

    /**
     * @param string $alarmStatus
     */
    public function setAlarmStatus($alarmStatus)
    {
        if(!in_array($alarmStatus, array(self::ALARM_STATUS_OFF, self::ALARM_STATUS_ARMED, self::ALARM_STATUS_ON))) {
            throw new \InvalidArgumentException(sprintf("Invalid Alarm Status. Should be one of 'off', 'armed', 'on', got '%s'", $alarmStatus));
        }

        $this->alarmStatus = $alarmStatus;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeadModules()
    {
        return $this->deadModules;
    }

    /**
     * @param ArrayCollection $deadModules
     */
    public function setDeadModules($deadModules)
    {
        $this->deadModules = $deadModules;
    }
}