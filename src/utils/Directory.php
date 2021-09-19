<?php

namespace Seven\JsonDB\Utils;

use Seven\JsonDB\Json;

class Directory {
    
    protected static string $dir;

    public static function setDirectory(string $directory)
    {
        $self = new self();
        $self::$dir = rtrim($directory).'/';
        return $self;
    }

    public static function create(string $name): bool
    {
        if (is_dir(self::$dir.$name)) {
            throw new \Exception("Database already exists.", 1);
        }
        if( mkdir(self::$dir.$name) ){
            if (!file_exists(self::$dir.'/schema.php')) 
                file_put_contents(self::$dir.'/schema.php', '<?php return[];');
            return true;
        }
        return false;
    }

    public static function list(): array
    {   
        $folders = glob(self::$dir.'*', GLOB_ONLYDIR);
        if (!empty($folder)) {
            $databases = [];
            foreach($folders as $folder){
                $databases[] = str_replace(
                    static::$dir, $replace = '', $folder
                );
            }
            return $databases;
        }
        return [];
    }

    public static function flush(string $database): bool
    {
        (array)$files = glob(self::$dir.$database.'/*');
        if (!empty($files)) {
            array_map(
                'unlink',
                array_filter($files)
            );
            if((new Json($database, self::$dir))->deleteBase($database)){ 
                return true;
            }
            return false;
        }
        return true;
    }

    public static function delete(string $database): bool
    {
        if ( !is_dir(self::$dir.$database) ) {
            throw new \Exception("Database '$database' does not exist.", 1);
        }
        if( rmdir(self::$dir.$database) && (new Json(
            $database, self::$dir
        ))->deleteBase($database) ){
            return true;
        }
        return false;
    }

}