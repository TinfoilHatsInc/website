<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 21-11-17
 * Time: 11:57
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as CoreRole;

/**
 * Class Role
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="role")
 * @ORM\Entity()
 */
class Role extends CoreRole
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
    private $name;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->name;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole($role) {
        $this->name = $role;

        return $this;
    }
}