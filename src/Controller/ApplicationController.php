<?php
declare(strict_types=1);

namespace App\Controller;

use App\Platform\Http\Response;

class ApplicationController extends AbstractController
{
    public function indexAction(): Response
    {
        return $this->render('Application/index', [
            'name' => 'Anton'
        ]);
    }
}