<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities\World;

use Override;
use Sleepybuildings\Choplifter\Entities\Entity;
use Sleepybuildings\Choplifter\Entities\PhysicalEntity;
use Sleepybuildings\Choplifter\GameState;
use Sleepybuildings\Choplifter\Utilities\Rect;

class Ground extends PhysicalEntity
{

	public const string Name = 'ground';

	private ?string $ground = null;


	public function __construct()
	{
	}


	#[Override] public function update(GameState $state): void
	{
		if($this->ground === null)
			$this->ground = str_repeat('-', $state->screen->width);

		if($this->hitbox === null)
		{
			$this->hitbox = new Rect(
				x: 0,
				y: $state->screen->height - 5,
				width: $state->screen->width,
				height: $state->screen->height,
			);
		}

		// Draw the ground

		$state->screen->write(0, $this->hitbox->y, $this->ground);
	}


	#[Override] public function killed(): bool
	{
		return false;
	}


	#[Override] public function name(): ?string
	{
		return self::Name;
	}
}