<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Utilities;

readonly class Rect
{

	public function __construct(
		public int $x = 0,
		public int $y = 0,
		public int $width = 0,
		public int $height = 0,
	)
	{
	}


	public static function fromCenter(
		int $centerX = 0,
		int $centerY = 0,
		int $width = 0,
		int $height = 0,
	): Rect
	{
		return new Rect(
			x: (int)round($centerX - ($width / 2)),
			y: (int)round($centerY - ($height / 2)),
			width: $width,
			height: $height,
		);
	}


	public function getCenter(): Point
	{
		return new Point(
			x: $this->x + ($this->width / 2),
			y: $this->y + ($this->height / 2),
		);
	}

}