<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

use Sleepybuildings\Choplifter\Entities\Helicopter;
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
		private readonly OutputInterface $output
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
			var_dump(ord(fread(STDIN, 1)));
			echo PHP_EOL;
			continue;


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
			entities: $this->worldEntities
		);

		foreach($this->worldEntities->entities() as $entity)
		{
			$entity->update($state);
		}

		$this->screen->write(4, 4, "K " . $this->key->value);
	}


	private function processInput(): void
	{
		$this->key = Key::tryFrom($this->readKey()) ?? Key::None;
print_r($this->key);
		switch($this->key)
		{
			case Key::Escape: // ESC
				$this->inGame = false;
				echo "DONEEEEEE";
				break;
		}
	}


	private function readKey(): int
	{
		return ord(fread(STDIN, 1));
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

		system("stty -icanon"); // Disabled enter on fread
		stream_set_blocking(STDIN, false);
	}


	private function tearDown(): void
	{
		$this->cursor->show();

		system("stty sane");
	}
}