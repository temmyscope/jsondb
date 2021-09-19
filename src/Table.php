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

    public const TYPE_INT = 'int';

    public const TYPE_STRING = 'str';

    public $id;

    public $createdAt;

    public $updatedAt;

	use TableTrait;

    public static function init(
        string $database, string $table, Json $json
    ): self {
        return new self($database, $table, $json);
    }

}