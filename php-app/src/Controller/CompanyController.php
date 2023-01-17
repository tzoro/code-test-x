<?php

namespace App\Controller;

use App\Entity\Company;
use App\Service\CurlGet;
use App\Form\CompanyType;
use App\Service\MailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailSender $mailSender, CurlGet $curlGet): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailSender->send(
                $company->getEmail(),
                $company->getCompanySymbol(),
                'From ' . $company->getStartDate()->format('Y-m-d') . ' to ' . $company->getEndDate()->format('Y-m-d')
            );

            $prices = $this->getHistoricalPrices($curlGet);
            $graphData = $this->getGraphData($prices);

            return $this->render('company/show.html.twig', [
                'prices' => $prices,
                'openData' => $graphData->open,
                'closeData' => $graphData->close
            ]);
        }

        return $this->renderForm('company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    private function getGraphData(Array $prices): \stdClass
    {
        $graphData = new \stdClass();
        $graphData->open = [];
        $graphData->close = []; 
        foreach ($prices as $key => $value) {
            array_push($graphData->open, [$value->date, $value->open]);
            array_push($graphData->close, [$value->date, $value->close]);
        }

        return $graphData;
    }

    private function getHistoricalPrices(CurlGet $curlGet): Array
    {
        $url = "https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=AMRN&region=US";
        $headers = [
            "X-RapidAPI-Host: yh-finance.p.rapidapi.com",
            "X-RapidAPI-Key: " . $this->getParameter('app.rapid_api_key')
        ];

        $response = $curlGet->getData($url, $headers);

        return json_decode($response)->prices;
    }
}
