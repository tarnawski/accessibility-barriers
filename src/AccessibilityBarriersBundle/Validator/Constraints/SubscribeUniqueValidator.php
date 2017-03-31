<?php

namespace AccessibilityBarriersBundle\Validator\Constraints;

use AccessibilityBarriersBundle\Entity\Subscribe;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscribeUniqueValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($protocol, Constraint $constraint)
    {
        $email = $protocol->getEmail();
        $subscribe = $this->entityManager->getRepository(Subscribe::class)->findOneBy(['email' => $email]);

        if ($subscribe) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
