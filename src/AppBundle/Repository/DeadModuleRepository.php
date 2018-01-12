<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 11-1-18
 * Time: 12:12
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Chub;
use AppBundle\Entity\DeadModule;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class DeadModuleRepository extends EntityRepository
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
        $chubIds = [];
        foreach ($userChubs as $userChub) {
            $chubIds[] = $userChub->getId();
        }
        $qb = $this->createQueryBuilder('d');
        $query = $qb
            ->where('d.chub IN (:chubids)')
            ->setParameter('chubids', $chubIds)
            ->orderBy('d.createdAt', 'DESC'
            )->getQuery();
        return $query->getResult();
    }
}