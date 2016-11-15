<?php

namespace ApiBundle\Security\Authorization\Voter;

use AccessibilityBarriersBundle\Entity\Alert;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class AlertVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof Alert) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Alert $alert */
        $alert = $subject;

        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($alert, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Alert $alert, User $user)
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }
        if ($alert->getUser() == $user) {
            return true;
        }

        return false;
    }
}
