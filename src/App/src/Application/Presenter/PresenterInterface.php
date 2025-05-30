<?php

namespace App\Application\Presenter;

interface PresenterInterface
{
    public function present(array $output): mixed;
}