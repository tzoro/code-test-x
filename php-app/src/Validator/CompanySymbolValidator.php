<?php

namespace App\Validator;

use App\Service\CurlGet;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CompanySymbolValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $curlGet = new CurlGet();
        $symbolFound = false;
        $symbolsData = $curlGet->getData('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
        $symbolsData = json_decode($symbolsData);

        foreach ($symbolsData as $key => $symbolValue) {
            if($symbolValue->Symbol === $value) {
                $symbolFound = true;
                break;
            }
        }

        if($symbolFound){
            return;
        }

        $emptyOrSymbol = is_null($value) ? '' : $value;

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $emptyOrSymbol)
            ->addViolation();
    }
}
