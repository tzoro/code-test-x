<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Service\CurlGet;
use App\Service\MailSender;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompanyRepository $companyRepository, MailSender $mailSender): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($company, true);

            $mailSender->send();

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company, CurlGet $curlGet): Response
    {
        $prices = $this->getHistoricalPrices($curlGet);
        $graphData = $this->getGraphData($prices);

        return $this->render('company/show.html.twig', [
            'company' => $company,
            'prices' => $prices,
            'openData' => $graphData->open,
            'closeData' => $graphData->close
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($company, true);

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $companyRepository->remove($company, true);
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
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
