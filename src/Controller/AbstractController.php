<?php

namespace Codememory\ApiBundle\Controller;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;
use Codememory\ApiBundle\ResponseSchema\ResponseSchema;
use Codememory\ApiBundle\ResponseSchema\View\SuccessView;
use Codememory\EntityResponseControl\Interfaces\ResponseControlInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        private readonly Request $request
    ) {
    }

    protected function getRequestData(): array
    {
        return json_decode($this->request->getContent(), true);
    }

    public function response(int $httpCode, ViewInterface $view): ResponseSchemaInterface
    {
        $response = new ResponseSchema();

        $response->setHttpCode($httpCode);
        $response->setView($view);

        return $response;
    }

    public function responseControl(int $httpCode, ResponseControlInterface $responseControl): ResponseSchemaInterface
    {
        $response = new ResponseSchema();

        $response->setHttpCode($httpCode);
        $response->setView(new SuccessView($responseControl->collect()->toArray()));

        return $response;
    }
}