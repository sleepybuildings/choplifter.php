<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

enum Key: int
{
	case None = 0;
	case Escape = 27;

	case Left = 37;
	case Up = 38;
	case Right = 39;
	case Down = 40;

	case W = 87;
	case S = 83;
	case A = 65;
	case D = 68;


	public function isUp(): bool
	{
		return $this === self::Up || $this === self::W;
	}


	public function isDown(): bool
	{
		return $this === self::Down || $this === self::S;
	}
}