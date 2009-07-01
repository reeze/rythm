<?php
class rtResponse
{
	private static $instance;
	private $headers = array();
	private $stylesheets = array();
	private $javascripts = array();
	private $slots = array();
	private $view_class = 'rtView'; // default view class
	private $body;
	
	private function __constructor()
	{
		
	}
	
	public function setViewClass($class)
	{
		if(!class_exists($class))
		{
			throw new rtException("Missing view class: $class");
		}
		
		$this->view_class = $class;
	}
	/**
	 * get view class
	 *
	 * @return string
	 */
	public function getViewClass()
	{
		return $this->view_class;
	}
	
	/**
	 * add stylesheets
	 * 
	 * addStylesheet('a', 'b'); or
	 * addStylesheet(array('a', 'b'));
	 * The same as addJavascript()
	 *
	 */
	public function addStylesheet()
	{
		$files = func_get_args();
		if(is_array($files[0])) $files = $files[0];
		
		$this->stylesheets = array_unique(array_merge($files, $this->stylesheets));
	}
	
	public function getStylesheets()
	{
		return $this->stylesheets;
	}
	
	public function addJavascript()
	{
		$files = func_get_args();
		if(is_array($files[0])) $files = $files[0];
		
		$this->javascripts = array_unique(array_merge($files, $this->javascripts));
	}
	
	public function getJavascripts()
	{
		return $this->javascripts;
	}
	
	public function setHeader($header)
	{
		$this->headers[] = $header;
	}
	
	public function setBody($body)
	{
		$this->body = $body;
	}
	public function getBody()
	{
		return $this->body;
	}
	public function display()
	{
		foreach ($this->headers as $header)
		{
			header($header);
		}
		echo $this->body;
	}
	
	// Slot support
	public function setSlot($name, $content)
	{
		$this->slots[$name] = $content;
	}
	public function getSlot($name, $default=NULL)
	{
		return isset($this->slots[$name]) ? $this->slots[$name] : $default;
	}
	
	public function header($header)
	{
		$this->headers[] = $header;
	}
	
	/**
	 * get instance
	 *
	 * @return rtResponse
	 */
	public static function getInstance()
	{
        if(!self::$instance)
        {
            self::$instance = new self(); 
        }
        return self::$instance;
	}
	
}