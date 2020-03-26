<?php

namespace Pbmengine\Logger;

use Pbmengine\Logger\Middleware\AddEnvInformations;
use Pbmengine\Logger\Middleware\AddGitInformations;
use Pbmengine\Logger\Middleware\AddRequestInformations;
use Illuminate\Support\Str;
use Monolog\Formatter\NormalizerFormatter;

class Formatter extends NormalizerFormatter
{
    /** @var array */
    protected $originalRecord;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $this->originalRecord = $record;

        $record = parent::format($record);

        return $this->getDocument($record);
    }

    /**
     * Convert log message
     *
     * @param array $record
     *
     * @return array
     */
    protected function getDocument(array $record)
    {
        $result['level']        = Str::lower($record['level_name']);
        $result['message']      = $record['message'];
        $result['report_time']  = $this->originalRecord['datetime']->__toString();
        $result['context']      = ! $this->isException($record) ? $record['context'] : [];

        if ($this->isException($record)) {
            $result['exception_code'] = $this->originalRecord['context']['exception']->getCode();
            $result['exception_line'] = $this->originalRecord['context']['exception']->getLine();
            $result['exception_file'] = $this->originalRecord['context']['exception']->getFile();
            $result['exception_file_content'] = file_exists($this->originalRecord['context']['exception']->getFile())
                    ? preg_split('/\r\n|\r|\n/', file_get_contents($this->originalRecord['context']['exception']->getFile()))
                    : '';
            $result['exception_class'] = get_class($this->originalRecord['context']['exception']);
            $result['exception_trace'] = $record['context']['exception']['trace'];
        }

        $result = array_merge(
            $result,
            (new AddEnvInformations())->handle(),
            (new AddGitInformations())->handle(),
            (new AddRequestInformations())->handle()
        );

        return $result;
    }

    protected function isException(array $record): bool
    {
        return array_key_exists('exception', $record['context']);
    }
}
