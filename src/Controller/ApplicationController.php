<?php
declare(strict_types=1);

namespace App\Controller;

use App\Platform\Http\Request;
use App\Platform\Http\Response;

class ApplicationController extends AbstractController
{
    public function indexAction(Request $request): Response
    {
        $now = new \DateTimeImmutable();

        return $this->render('Application/index', [
            'name' => 'Anton',
            'date' => [
                'since' => $now->modify('-1 month')->format('m/d/Y'),
                'till'  => $now->format('m/d/Y'),
            ],
        ]);
    }
}