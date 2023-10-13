<?php

declare(strict_types=1);

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace AntonEvers\ThirdParty\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EchoCommand
 */
class TestCommand extends Command
{

    public function __construct(private string $pool = '', string $name = null) { parent::__construct($name); }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('test:3rdparty');
        $this->setDescription('Proves that this module is installed and working.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>{$this->pool} module is installed successfully and working!</info>");
        return Cli::RETURN_SUCCESS;
    }
}
