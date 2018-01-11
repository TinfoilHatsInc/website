<?php
/**
 * Created by PhpStorm.
 * User: matthijs
 * Date: 6-12-17
 * Time: 15:07
 */

namespace AppBundle\Security;

use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class NotificationVoter extends Voter
{
    const VIEW = 'view';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::VIEW])) {
            return false;
        }

        if(!$subject instanceof Notification) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        /** @var Notification $notification */
        $notification = $subject;

        if($user->getId() == $notification->getChub()->getUser()->getId()) {
            return true;
        }

        return false;
    }
}