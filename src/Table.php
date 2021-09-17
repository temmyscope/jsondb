<?php

namespace Seven\JsonDB;

use Seven\JsonDB\Traits\Table as TableTrait;
use Seven\JsonDB\Json;


class Table {

    /**
     * @property $format determines output format: ARRAYS || OBJECTS
    */
	public const ARRAYS = 'get';

	public const OBJECTS = 'getObjects';

    public string $id;

    public string $createdAt;

    public string $updatedAt;

	use TableTrait;

    public static function init(
        string $database, string $table, Json $json
    ): self {
        $table = new self($database, $table, $json);

        return $table;
    }

}