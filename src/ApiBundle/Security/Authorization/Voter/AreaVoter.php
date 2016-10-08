<?php

namespace ApiBundle\Security\Authorization\Voter;

use AccessibilityBarriersBundle\Entity\Area;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class AreaVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof Area) {
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

        /** @var Area $area */
        $area = $subject;

        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($area, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Area $area, User $user)
    {
        if ($area->getUser() == $user) {
            return true;
        }

        return false;
    }
}
