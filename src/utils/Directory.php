<?php

namespace Seven\JsonDB\Utils;

class Directory {
    
    protected static string $dir = __DIR__.'/db/meta/';

    public static function create(string $name): bool
    {
        if (file_exists(static::$dir.$name)) {
            throw new Exception("Database already exists.", 1);
        }
        if(mkdir(static::$dir.$name)){
            return true;
        }
        return false;
    }

    public static function list(): array
    {   
        $folders = glob(static::$dir.'*', GLOB_ONLYDIR);
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
        (array)$files = glob(static::$dir.$database.'/*');
        if (!empty($files)) {
            array_map(
                'unlink',
                array_filter($files)
            );
            return true;
        }
        return false;
    }

    public static function delete(string $database)
    {
        if ( !is_dir(static::$dir.$database) ) {
            throw new Exception("Database '$database' does not exist.", 1);
        }
        rmdir(static::$dir.$database);
    }

}