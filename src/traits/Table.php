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

	public static function generateId(): string {

		$randomToken = Strings::randToken();

		$randomString = Strings::uniqueId($randomToken);

		return Strings::limit($randomString, $length = 32);
	}

	public static function generateTime(): string {
		return Strings::timeFromString('now');
	}

    public function save(): string | Exception
    {
        $file = __DIR__.'/../db/meta/schema.php';
        
		$schemaMap = include $file;
		
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
			$data['id'] = Table::generateId();
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

	public function insertOne(array $data): string {
		
		$tableContent = $this->json->fetch($this->table);

		$data['id'] = $data['id'] ?: Table::generateId();
		$data['createdAt'] = $data['createdAt'] ?: Table::generateTime();
		$data['updatedAt'] = $data['updatedAt'] ?: Table::generateTime();

		$arrays = Arrays::init($tableContent)->add($data);

		$this->json->save($this->table, $arrays->get());

		return $data['id'];
	}

	public function insertMany(array $data): array {
		$tableContent = $this->json->fetch($this->table);
		
		$insertedIds = [];

		$arrays = Arrays::init($data);

		$arrays->map( function($dataIterator) use ($insertedIds){
			$dataIterator['id'] = $dataIterator['id'] ?: Table::generateId();
			$dataIterator['createdAt'] = $dataIterator['createdAt'] ?: Table::generateTime();
			$dataIterator['updatedAt'] = $dataIterator['updatedAt'] ?: Table::generateTime();

			$insertedIds[] = $dataIterator['id'];
			return $dataIterator;
		});

		$tableContent = [ ...$tableContent, ...$arrays->get() ];

		$this->json->save($this->table, $tableContent);

		return $insertedIds;
	}

	public function update(array $update, array $condition) : array {
		$tableContent = $this->json->fetch($this->table);
		$arrays = Arrays::init($data);
		$updatedIds = [];

		$arrays->map(function($arrayIterator) use ($updatedIds){
			foreach($condition as $key => $value ) {
				if ( array_key_exists($key, $arrayIterator) && $arrayIterator[$key] === $value) {
					$updatedIds[] = $arrayIterator['id'];
					foreach ($update as $incomingKey => $incomingValue) {
						$arrayIterator[$incomingKey] = $incomingValue;
					}
				}
			}
			return $arrayIterator;
		});

		$this->json->save($this->table, $arrays->get());
		return $updatedIds;
	}

	public function findOne(array $condition = [], string $format = self::OBJECTS): array | object {
		$totalContent = $this->find( condition: $condition, format: $format );

		if ( empty($tableContent) ) {
			return [];
		}

		return Arrays::init($tableContent)->first();
	}

	public function last(array $condition = [], string $format = self::OBJECTS): array | object {
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
		array $condition = [], string $sortBy = '-createdAt', 
		int $limit = 1, int $skip = 0, string $format = self::OBJECTS
	) : array | Exception {

		$tableContent = $this->json->fetch($this->table);

		$array = Arrays::init($tableContent);
		if (!empty($condition)) {
			$array->extractBy($condition);
		}
		$sortKey = str_replace('-', '', $sortBy);
		(str_contains($sortBy, '-')) ? $array->downSort($sortKey) : $array->upSort($sortKey);

		$array = Arrays::init(
			$array->trim($limit, $skip)
		);

		$availableFormats = [ self::ARRAYS, self::OBJECTS ];
		
		if ( !in_array($availableFormats, $format) ) {
			throw new Exception("INVALID RESULT FORMAT: '$format' ", 1);
		}

		return $array->$format();
    }

	public function findById(string $id, string $format = self::OBJECTS): array | object {
		$data = $this->find(
			condition: [ 'id' => $id ], format: $format
		);
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

		if($this->json->save($this->table, $remnant)){
			return true;
		}
		return false;
	}

}