<?php

namespace Pbmengine\Logger\Middleware;

use Pbmengine\Logger\Contracts\Middleware;

class AddEnvInformations implements Middleware
{
    public function handle(): array
    {
        return [
            'app_env' => app()->environment(),
            'app_name' => app()->make('config')->get('app.name'),
            'laravel_version' => app()->version(),
            'laravel_locale' => app()->getLocale(),
            'php_version' => phpversion(),
        ];
    }
}
