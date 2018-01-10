<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 10-1-18
 * Time: 15:06
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Snapshot
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 */
class Snapshot
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
     * @var Notification
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Notification", inversedBy="snapshots")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    private $notification;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $filePath;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }
}