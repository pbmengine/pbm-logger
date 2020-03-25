<?php

namespace App\Logging\Middleware;

use Pbmengine\Logger\Contracts\Middleware;

class AddRequestInformations implements Middleware
{
    public function handle(): array
    {
        return [
            'ip' => app()->make('config')->get('logging.channels.pbm.anonymize_ips')
                ? ''
                : request()->ip(),
            'useragent' => request()->userAgent()
        ];
    }
}
