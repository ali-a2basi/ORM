<?php

namespace App\Helpers;
use App\Exceptions\ConfigFileNotFoundException;


class Config{

    public static function getFileContents(string $fileName){
        //realpath function gives the real path of root and correct the syntaxes
        $filePath = realpath(__DIR__ . "/../Configs/" . $fileName . '.php');
        
        if(!$filePath){

            throw new ConfigFileNotFoundException();
        }
        $fileContents = require $filePath;
        return $fileContents;
        
    }


    public static function get(string $fileName, string $key = null){
        $fileContents = Config::getFileContents($fileName);
        if(is_null($key)){
            
            return $fileContents;
        }
        
        return $fileContents[$key] ;
        
    }
}