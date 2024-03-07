<?php

namespace App\PandaScore\Hydrator;

use Generator;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface HydratorInterface
{
    public function createAll(ResponseInterface $response): Generator;
}
