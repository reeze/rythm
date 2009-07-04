<?php


# require the core rythm
require_once 'Rythm.class.php';

/**
 * rtAutoloader
 *
 */
class rtAutoloader
{
	public static function register()
	{
		self::initPath();
		
    // register autoload function
    spl_autoload_register('rtAutoloader::basic_autoloader');
    
    rtConfig::set('rt_core_dir', dirname(__FILE__));
    
          
    
	}
	
	public static function initPath()
	{
		// basic include paths		
		
		
		$rt_core_dir = dirname(__FILE__);
		
		self::addPath(
					  // TODO plugin paths
					  $rt_core_dir . DIRECTORY_SEPARATOR . 'vendor',
					  $rt_core_dir);
					  
	}
	
	public static function basic_autoloader($class_name)
	{
		$paths = explode(PATH_SEPARATOR, get_include_path());
		
		// manually find include files here. just because if the included file
		// doesn't exist will throw warning...
		
		foreach ($paths as $path)
		{
			$file = $path . DIRECTORY_SEPARATOR . "$class_name.php";
			
			if(file_exists($file))
			{
				require_once $file;
				return true;
			}
		}
		foreach ($paths as $path)
		{
			
			$cls_file = $path . DIRECTORY_SEPARATOR . "$class_name.class.php";
			if(file_exists($cls_file))
			{
				require_once $cls_file;
				return true;
			}
		}
		foreach ($paths as $path)
		{
			if(strpos($class_name, 'rt') == 0)
			{
				// trying to use rt files
				$folder = strtolower(substr($class_name, 2));
				$rt_file = $path . DS . "$folder/$class_name.class.php";
				if(file_exists($rt_file))
				{
					require_once $rt_file;
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Add paths to the include path
	 *
	 */
	public static function addPath()
	{
		$paths = func_get_args();
		
		set_include_path(implode(PATH_SEPARATOR, $paths) . PATH_SEPARATOR . get_include_path());
	}
}