<?php

declare(strict_types=1);

namespace muqsit\evdbeacon\block\tile;

use pocketmine\math\Vector3;
use pocketmine\world\ChunkListener;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;

class BeaconChunkListener implements ChunkListener{

	/** @var World */
	protected $world;

	/** @var Vector3 */
	protected $pos;

	public function __construct(Beacon $beacon){
		$pos = $beacon->getPos();
		$this->world = $pos->getWorldNonNull();
		$this->pos = $pos->asVector3();
	}

	public function onChunkChanged(Chunk $chunk) : void{
	}

	public function onChunkLoaded(Chunk $chunk) : void{
	}

	public function onChunkUnloaded(Chunk $chunk) : void{
	}

	public function onChunkPopulated(Chunk $chunk) : void{
	}

	public function onBlockChanged(Vector3 $block) : void{
		if( // TODO: Check for blocks inside 3D pyramidal volume instead of a cubical, possibly by caching XYZ offsets relative to beacon's position
			$block->y >= ($this->pos->y - 4) &&
			$block->y < $this->pos->y &&

			$block->x >= ($this->pos->x - 4) &&
			$block->x <= ($this->pos->x + 4) &&

			$block->z >= ($this->pos->z - 4) &&
			$block->z <= ($this->pos->z + 4)
		){
			$tile = $this->world->getTileAt($this->pos->x, $this->pos->y, $this->pos->z);
			if($tile instanceof Beacon){
				$tile->flagForLayerRecalculation();
			}
		}
	}
}