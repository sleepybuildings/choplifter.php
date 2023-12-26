<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities;

use Sleepybuildings\Choplifter\Utilities\Rect;

abstract class PhysicalEntity implements Entity
{

	protected ?Rect $hitbox = null;


	public function getHitBox(): ?Rect
	{
		return $this->hitbox;
	}
}