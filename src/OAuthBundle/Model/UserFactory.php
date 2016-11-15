<?php

namespace OAuthBundle\Model;

use OAuthBundle\Entity\User;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserFactory
{
    public function build($username, $email, $userId, $fieldName)
    {
        $user = $this->createUser($username, $email);
        $user->setPlainPassword(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($user, $fieldName, $userId);

        return $user;
    }

    public function buildAfterRegistration($firstName, $lastName, $username, $email, $password)
    {
        $user = $this->createUser($firstName, $lastName, $username, $email);
        $user->setPlainPassword($password);

        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @return User
     */
    private function createUser($firstName, $lastName, $username, $email)
    {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles(['ROLE_API']);
        $user->setEnabled(true);
        $user->setEmailNotification(true);

        return $user;
    }
}
