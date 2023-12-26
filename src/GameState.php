<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

use Monolog\Logger;

class GameState
{


	public function __construct(
		public readonly Key $keyPressed,
		public readonly float $delta,
		public readonly ScreenBuffer $screen,
		public readonly WorldEntities $entities,
		public readonly Logger $logger,
	)
	{
	}
}