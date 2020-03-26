<?php

namespace Pbmengine\Logger\Middleware;

use Pbmengine\Logger\Contracts\Middleware;
use Symfony\Component\Process\Process;

class AddGitInformations implements Middleware
{
    public function handle(): array
    {
        return [
            'git_branch' => $this->branch(),
            'git_hash' => $this->hash(),
            'git_tag' => $this->tag(),
            'git_remote' => $this->remote(),
            'git_message' => $this->message(),
        ];
    }

    public function branch()
    {
        return $this->command("git rev-parse --abbrev-ref HEAD");
    }

    public function hash(): ?string
    {
        return $this->command("git log --pretty=format:'%H' -n 1");
    }

    public function message(): ?string
    {
        return $this->command("git log --pretty=format:'%s' -n 1");
    }

    public function tag(): ?string
    {
        return $this->command('git describe --tags --abbrev=0');
    }

    public function remote(): ?string
    {
        return $this->command('git config --get remote.origin.url');
    }

    protected function command($command)
    {
        $process = (new \ReflectionClass(Process::class))->hasMethod('fromShellCommandline')
            ? Process::fromShellCommandline($command, base_path())
            : new Process($command, base_path());

        $process->run();

        return trim($process->getOutput());
    }
}
