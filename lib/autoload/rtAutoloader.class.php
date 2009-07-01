<?php

/**
 * rtAutoloader
 *
 */
class rtAutoLoader
{
	
	public static function initPath()
	{
		// basic include paths
			
		self::addPath(APP_DIR,
					  ROOT_DIR . DS . 'lib',
					  // TODO plugin paths
					  rt_CORE_DIR . DS . 'vendor',
					  rt_CORE_DIR);
					  
		// register autoload function
		spl_autoload_register('rtAutoLoader::basic_autoloader');
	}
	
	public static function basic_autoloader($class_name)
	{
		$paths = explode(PS, get_include_path());
		$found = false;
		
		// manually find include files here. just because if the included file
		// doesn't exist will throw warning...
		
		foreach ($paths as $path)
		{
			$file = $path . DS . "$class_name.php";
			
			if(file_exists($file))
			{
				require_once $file;
				return true;
			}
		}
		foreach ($paths as $path)
		{
			
			$cls_file = $path . DS . "$class_name.class.php";
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
		
		set_include_path(implode(PS, $paths) . PS . get_include_path());
	}
}