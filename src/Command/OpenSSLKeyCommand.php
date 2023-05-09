<?php

namespace Codememory\ApiBundle\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OpenSSLKeyCommand extends Command
{
    protected static $defaultDescription = 'Public and private key generator for jwt adapter';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the key pair, recommendation to use snake_case');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path where to save the keys');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        if (!file_exists($input->getArgument('path'))) {
            throw new RuntimeException("Directory {$input->getArgument('path')} does not exist");
        }

        $privatePath = $this->getFilePath($input, '_private_key.pem');
        $publicPath = $this->getFilePath($input, '_public_key.pem');

        shell_exec("openssl genrsa -out $privatePath 2048");
        shell_exec("openssl rsa -in $privatePath -outform PEM -pubout -out $publicPath");

        $style->info('Keys generated successfully');

        return self::SUCCESS;
    }

    private function getFilePath(InputInterface $input, string $endingFilename): string
    {
        return rtrim($input->getArgument('path'), '/').'/'.$input->getArgument('name').$endingFilename;
    }
}