<?php

/**
 * Logger class
 *
 */

define('ROUTE_LOG', 'route');
define('MIDDLEWARE_LOG', 'middleware');

class rtLogger
{
	private static $_logs = array();
	/**
	 * Log method
	 *
	 * @param string $type log type: route, controller view or something
	 * @param string $msg
	 */
	public static function log($type, $msg)
	{
		if(rtConfig::get('enable_log'))
		{
			self::$_logs[] = array('type' => $type, 'message' => $msg, 'time' => time());
		}
	}
	
	/**
	 * Get logs by type
	 *
	 * @param string $type
	 * @param array  $logs
	 */
	public static function getLogs($type='_all_')
	{
		if($type == '_all_') return self::$_logs;
	}
}