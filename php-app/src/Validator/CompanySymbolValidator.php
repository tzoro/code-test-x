<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CompanySymbolValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $symbolFound = false;
        $symbolsData = $this->getCurlGetData('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
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

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

    #@todo: Extract to service
    private function getCurlGetData(String $url = '', Array $headers = []): String
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = [];
        }

        return $response;
    }
}
