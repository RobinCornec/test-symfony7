<?php

namespace App\PandaScore\Api;

use Generator;

interface ApiInterface
{
    public function getAll(): Generator;
}