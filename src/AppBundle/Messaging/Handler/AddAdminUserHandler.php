<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 21-11-17
 * Time: 12:05
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Messaging\Command\AddAdminUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class AddAdminUserHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function handle(AddAdminUser $addAdminUser)
    {
        $user = new User();
        $user->setEmail($addAdminUser->getEmail());
        $user->setPassword($this->encoder->encodePassword($user, $addAdminUser->getPassword()));
        $user->eraseCredentials();
        $user->setEmail($addAdminUser->getEmail());
        $user->addRole($this->getAdminRole());
        $this->em->persist($user);
        $this->em->flush();
    }

    private function getAdminRole()
    {
        $adminRole = $this->em->getRepository(Role::class)->findOneBy(array(
            'name' => 'ROLE_ADMIN'
        ));
        return $adminRole;
    }
}