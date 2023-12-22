<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class ScreenBuffer
{

	private array $changes = [];

	public readonly int $width;
	public readonly int $height;

	public readonly int $centerX;
	public readonly int $centerY;


	public function __construct(
		private readonly Cursor $cursor,
		Terminal $terminal
	)
	{
		$this->height = $terminal->getHeight();
		$this->width = $terminal->getWidth();

		$this->centerX = (int)round($this->width / 2);
		$this->centerY = (int)round($this->height / 2);
	}


	public function write(int $x, int $y, string $value): void
	{
		$xPos = $x;
		for($index = 0; $index < strlen($value); $index++)
		{
			if($xPos >= $this->width)
			{
				$y++;
				$xPos = 0;
			}

			if($y > $this->height)
				break;

			$this->changes[$y][$xPos] = $value[$index];
			$xPos++;
		}
	}


	public function flush(): void
	{
		// TODO: add checks so we dont rewrite
		// the same data over and over again.
		// For ex. the ground is static.

		foreach($this->changes as $row => $cells)
		{
			foreach($cells as $column => $value)
			{
				$this->cursor->moveToPosition($column, $row);
				fputs(STDOUT, $value);
			}
		}

		$this->changes = [];
	}
}