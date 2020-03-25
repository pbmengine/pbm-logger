<?php

namespace Pbmengine\Logger;

use Monolog\Logger;

class LogFactory
{
    /**
     * Create a custom Monolog instance.
     *
     * @return Logger
     */
    public function __invoke()
    {
        $logger = new Logger('Pbm', [
            new PbmHandler(Logger::DEBUG, true)
        ]);

        return $logger;
    }
}
