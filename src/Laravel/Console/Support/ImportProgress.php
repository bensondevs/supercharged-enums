<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportProgress
{
    private ?ProgressBar $bar = null;

    private function __construct(
        private readonly OutputInterface $output,
    ) {}

    public static function fromOutput(?OutputInterface $output, bool $quiet): ?self
    {
        if ($quiet || $output === null) {
            return null;
        }

        return new self($output);
    }

    public function start(int $total, string $message): void
    {
        if ($total > 0) {
            $this->output->writeln($message);
            $this->bar = new ProgressBar($this->output, $total);
            $this->bar->start();
        }
    }

    public function advance(): void
    {
        $this->bar?->advance();
    }

    public function finish(): void
    {
        if ($this->bar === null) {
            return;
        }

        $this->bar->finish();
        $this->output->writeln('');
        $this->bar = null;
    }

    public function line(string $message): void
    {
        $this->output->writeln($message);
    }
}
