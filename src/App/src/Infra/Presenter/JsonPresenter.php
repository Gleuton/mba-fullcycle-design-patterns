<?php

namespace App\Infra\Presenter;

use App\Application\Presenter\PresenterInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class JsonPresenter implements PresenterInterface
{
    public function present(array $output): ResponseInterface
    {
        foreach ($output as $key => $value) {
                $output[$key]['date'] = $value['date']->format('Y-m-d');
        }
        return new JsonResponse($output);
    }
}
