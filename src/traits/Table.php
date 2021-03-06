<?php
namespace Seven\JsonDB\Traits;

use Seven\Vars\{ Strings, Arrays };
use Seven\JsonDB\Json;
use \Exception;

trait Table {

	protected function __construct(
		protected string $database, protected string $table, protected Json $json
	)
    {
    }

	public function generateId(string $type = self::TYPE_STRING): string | int {
		if ($type === self::TYPE_STRING) {
			$randomToken = Strings::randToken();
			$randomString = Strings::uniqueId($randomToken);
			return Strings::limit($randomString, $length = 16);
		}
		$arrays = $this->asArrays();
		$size = $arrays->count();
		return (int)$size + 1;
	}

	public function lastInsertId()
	{
		$arrays = $this->asArrays();
		if (!$arrays->isEmpty()) {
			return $arrays->pop()['id'] ?? null;
		}
		return null;
	}

	public static function generateTime(): string {
		return Strings::timeFromString('now');
	}

    public function save(): string | Exception
    {
        $schemaMap = $this->json->fetchSchema();
		
		if (empty($schemaMap))
			throw new Exception(
				"'save' method can not be used with schemaless tables i.e. tables created without fields; ", 1
			);
			
        $fields = $schemaMap[$this->database][$this->table];
        $data = [];

        foreach ($fields as $field ) {
            $data[$field] = $this->{$field};
        }

		if (!isset($data['id']) || empty($data['id'])) {
			$data['id'] = $this->generateId();
		}
		if (!isset($data['createdAt']) || empty($data['createdAt'])) {
			$data['createdAt'] = Table::generateTime();
		}
		if (!isset($data['updatedAt']) || empty($data['updatedAt'])) {
			$data['updatedAt'] = Table::generateTime();
		}
		$this->json->save($this->table, $data);

		return $data['id'];
    }

	public function insert(array $data): string | int {

		$data['id'] = $data['id'] ?? $this->generateId();
		$data['createdAt'] = $data['createdAt'] ?? Table::generateTime();
		$data['updatedAt'] = $data['updatedAt'] ?? Table::generateTime();
		$this->json->save($this->table, $data);

		return $data['id'];
	}

	public function update(array $update, array $condition) : array {
		$tableContent = $this->json->fetch($this->table);
		$arrays = Arrays::init($tableContent);
		$updatedIds = [];

		$arrays->map(function($arrayIterator) use ($updatedIds, $condition, $update){
			foreach($condition as $key => $value ) {
				if ( array_key_exists($key, $arrayIterator) && $arrayIterator[$key] === $value) {
					$updatedIds[] = $arrayIterator['id'];
					foreach ($update as $incomingKey => $incomingValue) {
						$arrayIterator[$incomingKey] = $incomingValue;
					}
					$arrayIterator['updatedAt'] = Table::generateTime();
				}
			}
			return $arrayIterator;
		});
		$this->json->overwrite($this->table, $arrays->get());
		return $updatedIds;
	}

	public function findOne(string|array $condition = [], string $format = self::OBJECTS): array | object {
		$totalContent = $this->find( condition: $condition, format: $format );
		if ( empty($tableContent) ) {
			return [];
		}
		return Arrays::init($tableContent)->first();
	}

	public function first(string|array $condition = [], string $format = self::OBJECTS): array | object {
		return $this->findOne(
			$condition, $format
		);
	}

	public function last(string|array $condition = [], string $format = self::OBJECTS): array | object {
		$totalContent = $this->find( condition: $condition, format: $format );
		if ( empty($tableContent) ) {
			return [];
		}
		return Arrays::init($tableContent)->last();
	}

    /**
     * find data from table; using named parameters
     * @param array $condition
     * @param string $sortBy
	 * @param int $limit
	 * @param int $skip
	 * 
     * @return array 
    */
    public function find(
		string|array $condition = [], string $sortBy = '-createdAt', 
		int $limit = 1, int $skip = 0, string $format = self::OBJECTS
	) : array | Exception {

		$tableContent = $this->json->fetch($this->table);

		$array = Arrays::init($tableContent);
		if ($array->isEmpty()) {
			return [];
		}
		if (!empty($condition)) {
			$array->extractBy($condition);
		}
		$sortKey = str_replace('-', '', $sortBy);
		(str_contains($sortBy, '-')) ? $array->downSort($sortKey) : $array->upSort($sortKey);
		if ($array->isEmpty()) {
			return [];
		}
		$arrays = Arrays::init(
			$array->trim($limit, $skip)
		);
		$availableFormats = [ self::ARRAYS, self::OBJECTS ];
		if ( !in_array($format, $availableFormats) ) {
			throw new Exception("INVALID RESULT FORMAT: '$format' ", 1);
		}
		return $arrays->$format();
    }

	public function search(
		string|array $condition = [], string $sortBy = '-createdAt'
	): array
	{
		$tableContent = $this->json->fetch($this->table);

		$arrays = Arrays::init($tableContent);
		if ( !$arrays->isEmpty() ) {
			$sortKey = str_replace('-', '', $sortBy);
			(str_contains($sortBy, '-')) ? $arrays->downSort($sortKey) : $arrays->upSort($sortKey);
		}
		if (!empty($condition)) {
			return $arrays->search($condition);
		}
		return $arrays->get();
	}

	public function asArrays(): Arrays
	{
		return new Arrays( $this->json->fetch($this->table) );
	}

	public function findById(string|int $id, string $format = self::OBJECTS): array | object {
		$data = $this->find(
			condition: [ 'id' => $id ], format: $format
		);
		if (empty($data)) {
			return [];
		}
		return Arrays::init($data)->first();
	}

	/**
	 *  delete item from table based on condition; empties the table if no condition is passed
	 * 
	 * @param array $condition
	 * 
	 * @return bool 
	 */
	
	public function delete(array $condition = []): bool{
		$tableContent = $this->json->fetch($this->table);

		$array = Arrays::init($tableContent);

		$remnant = !empty($condition) ? $array->excludeBy($condition)->get() : [];

		if($this->json->overwrite($this->table, $remnant)){
			return true;
		}
		return false;
	}

	public function drop()
    {
        $this->json->delete($this->database, $this->table);
    }

	public function toSQL()
	{
		#port data in json table to sql
	}

	public function toCSV()
	{
		#port data in json table to csv
	}

}