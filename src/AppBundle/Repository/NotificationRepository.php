<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 11-1-18
 * Time: 12:12
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Chub;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * Find all notification for a given user
     *
     * @param User $user
     * @return array
     */
    public function findAllForUser(User $user)
    {
        $userChubs = $this->getEntityManager()->getRepository(Chub::class)->findBy([
            'user' => $user
        ]);
        $userChubIds = [];
        foreach ($userChubs as $userChub) {
            $userChubIds[] = $userChub->getId();
        }
        $qb = $this->createQueryBuilder('n');
        $query = $qb
            ->where('n.chub IN (:chubids)')
            ->setParameter('chubids', $userChubIds)
            ->orderBy('n.createdAt', 'DESC'
            )->getQuery();
        return $query->getResult();
    }
}