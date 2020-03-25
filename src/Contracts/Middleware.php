<?php

namespace Pbmengine\Logger\Contracts;

interface Middleware
{
    public function handle(): array;
}
