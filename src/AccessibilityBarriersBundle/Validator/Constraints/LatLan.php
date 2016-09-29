<?php
namespace AccessibilityBarriersBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LatLan extends Constraint
{
    public $message = 'Coordinates are not included in the allowed area';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'coordinates_validation';
    }
}
