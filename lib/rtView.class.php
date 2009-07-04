<?php

/**
 * View layer. PHPView
 **/
class rtView
{
	protected static $extension = 'php';	
    protected  $vars = array();
    protected $output;
    
    public function __construct($file, $params=array())
    {
    	if(!file_exists($file))
    	{
//    		throw new Exception("Missing view: $file");
    	}
    	$this->vars = $params;
    	$this->render($file, $params);
    }
    
    /**
     * Register extension and class name
     *
     */
    public static function register()
    {
    	return array('extension' => self::getExtension(), 'class' => __CLASS__);
    }
    
    /**
     * Get the extension of this view connected with
     *
     */
    public static function getExtension()
    {
    	return self::$extension;
    }
    
    public function getOutput()
    {
    	return $this->output;
    }
    public function render($view_file, $params=array())
    {
    	ob_start();
        extract($this->vars, EXTR_SKIP);
        include $view_file; // this is the core method of PHPView imply
        $this->output = ob_get_clean(); // get and clean the buffer
    }
    public function display()
    {
    	echo $this->output;
    }
}
