<?php

namespace ApiBundle\Security\Authorization\Voter;

use AccessibilityBarriersBundle\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use OAuthBundle\Entity\User;

class CommentVoter extends Voter
{
    const ACCESS = 'access';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

        if (!$subject instanceof Comment) {
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

        /** @var Comment $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($comment, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Comment $comment, User $user)
    {
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }
        if ($comment->getUser() == $user) {
            return true;
        }

        return false;
    }
}
