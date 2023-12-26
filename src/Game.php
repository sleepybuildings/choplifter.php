<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

use Monolog\Logger;
use Sleepybuildings\Choplifter\Entities\Chopper\Helicopter;
use Sleepybuildings\Choplifter\Entities\World\Ground;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class Game
{
	private const int Frames = 30;

	private bool $inGame = true;

	private Key $key = Key::None;

	private readonly Cursor $cursor;
	private readonly ScreenBuffer $screen;

	private WorldEntities $worldEntities;


	public function __construct(
		private readonly OutputInterface $output,
		private readonly Logger $logger,
	)
	{
		$this->cursor = new Cursor($this->output);
		$this->screen = new ScreenBuffer($this->cursor, new Terminal());

		$this->worldEntities = new WorldEntities();
	}


	public function run(): void
	{
		$this->setUp();
		$this->setUpWorld();

		$previousTime = microtime(as_float: true);
		while($this->inGame)
		{
			$currentTime = microtime(as_float: true);
			$delta = $currentTime - $previousTime;
			$previousTime = $currentTime;

			$this->processInput();
			if(!$this->inGame)
				break;

			$this->updateGame($delta);

			$this->screen->flush();

			$pass = microtime(as_float: true) - $currentTime;
			$waitTime = (int)((1_000_000 / self::Frames) - $pass);
			$fps = round(1_000_000 / $waitTime, 2);

			$this->screen->write(0, 0, 'FPS: ' . $fps);

			usleep($waitTime);
		}

		$this->tearDown();
	}


	private function updateGame(float $delta): void
	{
		$state = new GameState(
			keyPressed: $this->key,
			delta: $delta,
			screen: $this->screen,
			entities: $this->worldEntities,
			logger: $this->logger
		);

		foreach($this->worldEntities->entities() as $entity)
		{
			$entity->update($state);
		}

		$this->screen->write(4, 4, "K " . $this->key->value);
	}


	private function processInput(): void
	{
		$rawKey = $this->readKey();

		if(!empty($rawKey))
			$this->logger->info('Key pressed', ['raw' => $rawKey]);

		$this->key = Key::tryFrom($rawKey) ?? Key::None;

		if($this->key !== Key::None)
			$this->logger->info('Key pressed', ['key' => $this->key]);

		switch($this->key)
		{
			case Key::C:
				$this->inGame = false;
				echo "DONEEEEEE";
				break;
		}
	}


	private function readKey(): string
	{
		return fread(STDIN, 1);
	}


	private function setUpWorld(): void
	{
		$this->worldEntities->spawn(new Ground());
		$this->worldEntities->spawn(new Helicopter());
	}


	private function setUp(): void
	{
		$this->cursor->hide();
		$this->cursor->clearScreen();

		system("stty cbreak -echo"); // Disabled enter on fread
		//system("stty -icanon"); // Disabled enter on fread
		stream_set_blocking(STDIN, false);
	}


	private function tearDown(): void
	{
		$this->cursor->show();

		system("stty sane");
	}
}