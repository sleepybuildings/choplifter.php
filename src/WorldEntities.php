<?php

declare(strict_types=1);

namespace Sleepybuildings\Choplifter;

use Generator;
use Sleepybuildings\Choplifter\Entities\Entity;
use Sleepybuildings\Choplifter\Entities\PhysicalEntity;

class WorldEntities
{

	private array $entities = [];

	private array $nameMapping = [];


	public function get(string $name): null|Entity|PhysicalEntity
	{
		return $this->nameMapping[$name] ?? null;
	}


	public function entities(): Generator
	{
		/** @var Entity $entity */
		foreach($this->entities as $index => $entity)
		{
			yield $entity;

			if($entity->killed())
			{
				$this->removeEntity($index);
			}
		}
	}


	public function spawn(Entity $entity): void
	{
		if(($name = $entity->name()) !== null)
			$this->nameMapping[$name] = $entity;

		$this->entities[] = $entity;
	}


	private function removeEntity(int $index)
	{

	}


}