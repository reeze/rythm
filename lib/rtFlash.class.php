<?php

/**
 * Flash class
 * 
 * Flash clean up invokes in CommonMiddleware class
 *
 */

class rtFlash
{
	private
		static $instance,
		$flashes;
	
	public function set($type, $msg)
	{
		$this->flashes['next'][$type] = $msg;
	}
	
	// This will show in this request
	public function setNow($type, $msg)
	{
		$this->flashes['now'][$type] = $msg;
	}
	
	public function has($type)
	{
		return isset($this->flashes['now'][$type]);
	}
	
	public function get($type)
	{
		return isset($this->flashes['now'][$type]) ?
					 $this->flashes['now'][$type] : null;
	}
	
	/**
	 * Clean the flash
	 *
	 */
	public function clean()
	{
		// clean the outdate flash messages, 
		// Flash should initialized before any flash access happens
		// so we invoke Flash::getInstance() in CommonMiddleware, because
		// it handle the request before any others

		// move previous flash to now
		$this->flashes['now'] = $this->flashes['next'];
		
		// empty the next request flash
		$this->flashes['next'] = array();
	}
	
	private function __construct()
	{
		if(!$_SESSION['_rt_flash_'])
		{
			$_SESSION['_rt_flash_'] = array(
					'now'  => array(), // the flash data we can get in this request
					'next' => array()  // will show on next request
					);
		}
		// We make a reference of  $s
		$this->flashes = &$_SESSION['_rt_flash_'];
	}
	
	/**
	 * Get instance
	 *
	 * @return Flash
	 */
	public function getInstance()
	{
		if(!self::$instance)
        {
            self::$instance = new self(); 
        }
        return self::$instance;
	}
}