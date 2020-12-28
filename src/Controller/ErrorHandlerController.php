<?php

namespace App\Controller;

use App\Platform\Http\Response;

class ErrorHandlerController extends AbstractController
{
    public function renderErrorPage(string $message): Response
    {
        return $this->render('error', [
            'message' => $message,
        ]);
    }
}