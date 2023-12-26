#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Sleepybuildings\Choplifter\Game;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$application = new Application();

$command = $application->register('game')
	->setCode(function (InputInterface $input, OutputInterface $output): int
	{

		$logger = new Logger('game');
		$logger->pushHandler(new StreamHandler('game.log'));

		(new Game($output, $logger))->run();

		return Command::SUCCESS;
	});

$application->setDefaultCommand($command->getName());
$application->run();