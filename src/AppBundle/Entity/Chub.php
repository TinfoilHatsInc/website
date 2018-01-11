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
     * @var string
     *
     * @ORM\Column(name="alarm_status", type="string", columnDefinition="ENUM('off', 'armed', 'on')")
     */
    private $alarmStatus;

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
}