<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Utilities;

readonly class Point
{
	public function __construct(
		public int $x = 0,
		public int $y = 0,
	)
	{
	}
}