<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities\World;

use Sleepybuildings\Choplifter\Entity;
use Sleepybuildings\Choplifter\GameState;

class Ground implements Entity
{
	public const string Name = 'ground';

	private ?string $ground = null;

	public readonly int $height;


	public function __construct()
	{
		$this->height = 5;
	}


	#[\Override] public function update(GameState $state): void
	{
		if($this->ground === null)
			$this->ground = str_repeat('-', $state->screen->width);

		// Draw the ground

		$yPos = $state->screen->height - $this->height;
		$state->screen->write(0, $yPos, $this->ground);
	}


	#[\Override] public function killed(): bool
	{
		return false;
	}


	#[\Override] public function name(): ?string
	{
		return self::Name;
	}
}