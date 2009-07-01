<?php

class rtConfig
{
	private static $_data = array();
	
	public static function init($data)
	{
		self::$_data = $data;
	}
	
	/**
	 * Get the config value by key
	 *
	 * @param string $key
	 * @param mixed $default if the value of the key is null return default value
	 * @return mixed
	 */
	public static function get($key, $default = null)
	{
		return isset(self::$_data[$key]) ? self::$_data[$key] : $default;
	}
	public static function set($key, $value)
	{
		if($key) self::$_data[$key] = $value;
	}
}
