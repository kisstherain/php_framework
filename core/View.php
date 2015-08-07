<?php
class View {
    public $path;
    protected $vars = array();
    private $template;
    public $layout;
    public function __construct($path = '.') {
        $this->path = $path;
    }
    public function get($key) {
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }
    public function set($key, $value = null) {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        }
        else {
            $this->vars[$key] = $value;
        }
    }
    public function has($key) {
        return isset($this->vars[$key]);
    }
    public function clear($key = null) {
        if (is_null($key)) {
            $this->vars = array();
        }
        else {
            unset($this->vars[$key]);
        }
    }
    public function render($file, $data = null) {
        $this->template = $this->getTemplate($file);
        if (!file_exists($this->template)) {
            throw new \Exception("Template file not found: {$this->template}.");
        }

        if (is_array($data)) {
            $this->vars = array_merge($this->vars, $data);
        }

        extract($this->vars);

        include $this->template;
    }

    public function show($data = null){
        $this->render($this->layout,$data);
    }

    public function content($file){
        $content = $this->fetch($file);
        $this->set("content",$content);
        return $content;
    }
    public function fetch($file, $data = null) {
        ob_start();

        $this->render($file, $data);
        $output = ob_get_clean();

        return $output;
    }
    public function exists($file) {
        return file_exists($this->getTemplate($file));
    }
    public function getTemplate($file) {
        if ((substr($file, -4) != '.php')) {
            $file .= '.php';
        }
        if ((substr($file, 0, 1) == '/')) {
            return $file;
        }
        else
        {
            return $this->path.'/'.$file;
        }
    }
    public function e($str) {
        echo htmlentities($str);
    }
}

