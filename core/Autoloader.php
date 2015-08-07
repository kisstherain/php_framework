<?php
namespace core;
class Autoloader{
    private $dir = "";
    private function __construct($dir){
        $this->dir = $dir;
    }
    public static function register($dir){
        spl_autoload_register(array(new self($dir), 'autoload'));
    }
    public function autoload($className){
        $filepath = $this->dir.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
        if (is_file($filepath)) {
            require_once($filepath);
        }
    }
}
