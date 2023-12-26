<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

enum Key: string
{
	// https://www.gnu.org/software/screen/manual/html_node/Input-Translation.html
	case None = '';

	// Broken
	case Left = "\033[D";
	case Up = "\033[A";
	case Right = "\033[C";
	case Down = "\033[B";

	case W = 'w';
	case S = 's';
	case A = 'a';
	case D = 'd';
	case C = 'c';

//	case Escape = "\033";


	public function isLeft(): bool
	{
		return $this === self::Left || $this === self::A;
	}


	public function isRight(): bool
	{
		return $this === self::Right || $this === self::D;
	}

	public function isUp(): bool
	{
		return $this === self::Up || $this === self::W;
	}


	public function isDown(): bool
	{
		return $this === self::Down || $this === self::S;
	}
}