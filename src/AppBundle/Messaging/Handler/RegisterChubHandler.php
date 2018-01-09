<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 9-1-18
 * Time: 12:50
 */

namespace AppBundle\Messaging\Handler;


use AppBundle\Entity\Chub;
use AppBundle\Entity\User;
use AppBundle\Messaging\Command\RegisterChub;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class RegisterChubHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $chubEm;

    public function __construct(EntityManager $em, Session $session, EntityManager $chubEm)
    {
        $this->em = $em;
        $this->session = $session;
        $this->chubEm = $chubEm;
    }

    public function handle(RegisterChub $registerChub)
    {
        $user = $registerChub->getUser();
        $chub = $this->createNewChubEntity($user);
        $this->registerInKeyDatabase($chub);

        $this->session->getFlashBag()->add('success', sprintf('New Chub successfully registered for %s with Chub ID %s', $user->getEmail(), $chub->getId()));
    }

    /**
     * Create new Chub entity
     *
     * @param User $user
     * @return Chub
     */
    private function createNewChubEntity(User $user)
    {
        $chub = new Chub();
        $chub->setUser($user);
        $this->em->persist($chub);
        $this->em->flush();
        return $chub;
    }

    /**
     * Register new Chub in key database
     *
     * @param Chub $chub
     */
    private function registerInKeyDatabase(Chub $chub)
    {
        $connection = $this->chubEm->getConnection();
        $sql = 'INSERT INTO IDTable (deviceid, user) VALUES (:deviceid, :user)';
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'deviceid' => $chub->getId(),
            'user' => $chub->getUser()->getId()
        ]);
    }
}