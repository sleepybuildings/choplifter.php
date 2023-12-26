<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities\Chopper;

enum ChopperState
{
	case Idle;
	case Landed;
	case Left;
	case Right;
	case Front;
}