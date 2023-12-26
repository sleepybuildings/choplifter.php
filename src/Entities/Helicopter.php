<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities;

use Exception;
use Override;
use Sleepybuildings\Choplifter\Entities\World\Ground;
use Sleepybuildings\Choplifter\Entity;
use Sleepybuildings\Choplifter\GameState;
use Sleepybuildings\Choplifter\Key;

class Helicopter implements Entity
{
	public const string Name = 'player';

	private const string Helicopter = 'X';
	private const string BladesEmpty = '       ';
	private const string BladesFull = 'xxxxxxx';
	private const string BladesHalf = '   x   ';

	private int $x = -1;
	private int $y = -1;

	private float $bladeState = 0;
	private bool $fullBlades = true;


	#[Override] public function update(GameState $state): void
	{
		if($this->y === -1)
			$this->setUp($state);

		$state->screen->write($this->x - 3, $this->y - 1, self::BladesEmpty);
		$state->screen->write($this->x, $this->y, ' ');

		$this->processInput($state->keyPressed);

		$this->bladeState += $state->delta;
		if($this->bladeState > .4)
			$this->bladeState = .0;

		$this->fullBlades = $this->bladeState > .2;

		$this->draw($state);

		$state->logger->info('Chopper pos', [$this->x, $this->y, $this->bladeState]);
	}


	private function draw(GameState $state): void
	{
		// Chopper

		$state->screen->write($this->x, $this->y, self::Helicopter);

		// Blades

		$state->screen->write($this->x - 3, $this->y - 1, $this->fullBlades
		 	? self::BladesFull
			: self::BladesHalf
		);
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
		$this->x += match(true)
		{
			$key->isLeft() => -1,
			$key->isRight() => 1,
			default => 0
		};

		$this->y += match(true)
		{
			$key->isUp() => -1,
			$key->isDown() => 1,
			default => 0
		};
	}


	#[Override] public function name(): ?string
	{
		return self::Name;
	}


	#[Override] public function killed(): bool
	{
		return false;
	}
}