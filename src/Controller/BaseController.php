<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class BaseController
{
    public function hello(string $name): Response
    {
        return new Response(sprintf('Hello, %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8')));
    }
}