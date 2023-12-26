<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter\Entities;

use Sleepybuildings\Choplifter\GameState;

interface Entity
{

	public function name(): ?string;

	public function update(GameState $state): void;

	public function killed(): bool;

}