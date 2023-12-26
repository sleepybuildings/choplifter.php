<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities\Chopper;

use Exception;
use Override;
use Sleepybuildings\Choplifter\Entities\PhysicalEntity;
use Sleepybuildings\Choplifter\Entities\World\Ground;
use Sleepybuildings\Choplifter\GameState;
use Sleepybuildings\Choplifter\Key;
use Sleepybuildings\Choplifter\Utilities\Rect;

class Helicopter extends PhysicalEntity
{
	public const string Name = 'player';

	private const string Legs = '/ \\';
	private const string LegsGone = '   ';

	private const string Helicopter = 'X';
	private const string BladesEmpty = '       ';
	private const string BladesFull = 'xxxxxxx';
	private const string BladesHalf = '   x   ';

	private ChopperState $chopperState = ChopperState::Idle;

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
		$state->screen->write($this->x - 1, $this->y + 1, self::LegsGone);

		$this->processInput($state->keyPressed);

		$this->updateBladeState($state);
		$this->updateChopperState($state);

		$this->draw($state);
	}


	private function hasLanded(GameState $state): bool
	{
		return $this->y > $state->entities->get(Ground::Name)->getHitBox()->y + 1;
	}


	private function updateChopperState(GameState $state): void
	{
		$this->hitbox = Rect::fromCenter($this->x, $this->y,
			width: strlen(self::Helicopter), height: 1);

		if($this->hasLanded($state))
			$this->chopperState = ChopperState::Landed;
		else
			$this->chopperState = ChopperState::Idle;

		//$state->logger->info('STATE', [$this->chopperState->name]);
	}



	private function updateBladeState(GameState $state): void
	{
		$this->bladeState += $state->delta;
		if($this->bladeState > .4)
			$this->bladeState = .0;

		$this->fullBlades = $this->chopperState === ChopperState::Landed
			|| $this->bladeState > .2;
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

		// Landing legs

		$state->screen->write($this->x - 1, $this->y + 1, $this->chopperState === ChopperState::Landed
		 	? self::Legs
			: self::LegsGone
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
		$this->y = (int)($state->screen->centerY - ($ground->getHitBox()->height / 2));
	}


	private function processInput(Key $key): void
	{
		// If the chopper is on the ground, we only accept
		// an upwards action.

		if($this->chopperState === ChopperState::Landed && !$key->isUp())
			return;

		$this->x += match(true)
		{
			$key->isLeft() => -2,
			$key->isRight() => 2,
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