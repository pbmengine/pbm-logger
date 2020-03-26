<?php

namespace Pbmengine\Logger;

use GuzzleHttp\Client;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class PbmHandler extends AbstractProcessingHandler
{
    /** @var int */
    protected $minimumLogLevel = Logger::ERROR;

    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $apiEndpoint;

    public function __construct(int $level = Logger::DEBUG, bool $bubble = true)
    {
        $this->setApiKey(config('logging.channels.pbm.api_key', ''));
        $this->setApiEndpoint(config('logging.channels.pbm.api_endpoint', ''));
        $this->setMinimumLogLevel(config('logging.channels.pbm.level', Logger::ERROR));

        parent::__construct($level, $bubble);
    }

    protected function setApiEndpoint(string $endpoint)
    {
        $this->apiEndpoint = $endpoint;
    }

    protected function setApiKey(string $key)
    {
        $this->apiKey = $key;
    }

    protected function setMinimumLogLevel(int $level)
    {
        if (! in_array($level, Logger::getLevels())) {
            throw new \InvalidArgumentException('The given minimum log level is not supported.');
        }

        $this->minimumLogLevel = $level;
    }

    protected function write(array $report): void
    {
        if ($this->shouldReport($report)) {
            $this->sendReportToApi($report['formatted']);
        }
    }

    protected function sendReportToApi(array $report)
    {
        $client = new Client();

        try {
            $client->request('POST', $this->apiEndpoint, [
                'json' => $report,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'apiKey' => $this->apiKey
                ]
            ]);
        } catch (\Exception $e) {}
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new Formatter();
    }

    protected function shouldReport(array $report): bool
    {
        return $this->hasException($report) || $this->hasValidLogLevel($report);
    }

    protected function hasException(array $report): bool
    {
        $context = $report['context'];

        return isset($context['exception']) && $context['exception'] instanceof Throwable;
    }

    protected function hasValidLogLevel(array $report): bool
    {
        return $report['level'] >= $this->minimumLogLevel;
    }
}
