<?php

class rtContext
{
	private static $instance,
			$request,
			$reponse,
			$view_args = array('javascripts' => array(), 'stylesheets' => array());
	
	
	private function __construct()
	{
		
	}
	
	public function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
}