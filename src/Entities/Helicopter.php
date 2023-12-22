<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities;

use Exception;
use Sleepybuildings\Choplifter\Entities\World\Ground;
use Sleepybuildings\Choplifter\Entity;
use Sleepybuildings\Choplifter\GameState;
use Sleepybuildings\Choplifter\Key;
use function Symfony\Component\String\s;

class Helicopter implements Entity
{
	public const string Name = 'player';

	private const Helicopter = 'X';

	private int $x = -1;
	private int $y = -1;


	#[\Override] public function update(GameState $state): void
	{
		if($this->y === -1)
			$this->setUp($state);

		$this->processInput($state->keyPressed);
		$state->screen->write($this->x, $this->y, self::Helicopter);
	}


	/**
	 * @throws Exception
	 */
	private function setUp(GameState $state): void
	{
		/** @var Ground $ground */
		$ground = $state->entities->get(Ground::Name);
		if(!$ground)
			throw new Exception('Ground not found');

		$this->x = $state->screen->centerX;
		$this->y = (int)($state->screen->centerY - ($ground->height / 2));
	}


	private function processInput(Key $key): void
	{
		$this->y = match(true)
		{
			$key->isUp() => -1,
			$key->isDown() => 1,
			default => 0
		};
	}


	#[\Override] public function name(): ?string
	{
		return self::Name;
	}


	#[\Override] public function killed(): bool
	{
		return false;
	}
}