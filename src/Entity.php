<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

interface Entity
{

	public function name(): ?string;

	public function update(GameState $state): void;

	public function killed(): bool;

}