<?php

namespace App\Controller;

use App\Entity\Company;
use App\Service\CurlGet;
use App\Form\CompanyType;
use App\Service\MailSender;
use App\Service\GraphDataProcessor;
use App\Service\PriceDataFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailSender $mailSender, CurlGet $curlGet, PriceDataFetcher $priceDataFetcher, GraphDataProcessor $graphDataProcessor): Response
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

            $prices = $priceDataFetcher->getHistoricalPrices($company->getCompanySymbol(), $curlGet);
            $graphData = $graphDataProcessor->getGraphData($prices);

            return $this->render('company/show.html.twig', [
                'company' => $company,
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
}
