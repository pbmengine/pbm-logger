<?php

namespace Pbmengine\Logger\Middleware;

use Pbmengine\Logger\Contracts\Middleware;

class AddRequestInformations implements Middleware
{
    public function handle(): array
    {
        return [
            'request_ip' => app()->make('config')->get('logging.channels.pbm.anonymize_ips')
                ? ''
                : request()->ip(),
            'request_useragent' => request()->userAgent()
        ];
    }
}
