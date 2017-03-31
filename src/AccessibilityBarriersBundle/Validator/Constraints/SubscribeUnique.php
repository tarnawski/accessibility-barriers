<?php

namespace AccessibilityBarriersBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SubscribeUnique extends Constraint
{
    public $message = 'Email exist in system';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'subscribe_validate';
    }
}
