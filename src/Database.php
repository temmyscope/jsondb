<?php

namespace Seven\JsonDB;

use Seven\JsonDB\Traits\Database as DatabaseTrait;
use Seven\JsonDB\Json;


class Database {

	use DatabaseTrait;
	
	public static function init(string $database, Json $json): self
	{
		$db = new self($database, $json);
		return $db;
	}

}