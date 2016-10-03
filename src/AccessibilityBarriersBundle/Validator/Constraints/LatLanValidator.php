<?php

namespace AccessibilityBarriersBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LatLanValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        $latitude = floatval($protocol->getLatitude());
        $longitude = floatval($protocol->getLongitude());

        if ($latitude >= 50.069419 || $latitude <= 50.002822 || $longitude >= 22.067585 || $longitude <= 21.937809) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
