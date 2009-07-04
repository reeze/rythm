<?php

/**
 * @author Reeze <reeze.xia@gmail.com>
 * @copyright GPL ? thoes license are confuse. maybey someday I will look into them
 * 
 * You can use it freely, but no guarantee made.
 *
 */


// Rythm Core Libray Dir
define('RT_CORE_DIR', dirname(__FILE__));

// for conivent
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);


/**
 * Rythm Main Framework class
 **/
class Rythm
{
	const MAJOR_VERSION = '0';
	const MINOR_VERSION = '0';
	const REVISION		  = '1';
	
  public static function init()
  {
  	define('RT_START_TIME', time()); // framework start time

  	require_once 'sfYaml/sfYaml.php';
		// start session support
		session_start();		
		
		// load config file	
		self::loadConfig();	
		
		self::loadModel();	
		
		
    //load routes file
    self::loadRoute();
		
		// register view classes
		self::registerViewClasses();
		
		// Load helpers
		$helpers = rtConfig::get('default_helpers', array());
		foreach ($helpers as $helper)
		{
			UseHelper($helper);
		}
  }
    
  public static function loadModel()
  {
  	if(rtConfig::get('use_database'))
  	{
      $db_file = rtConfig::get('rt_config_dir') . DS . 'database.yml';

 	    $databases = sfYaml::load($db_file);
  		require_once 'Doctrine/Doctrine.php';
  		
  		// autoload for doctrine
      spl_autoload_register(array('Doctrine', 'autoload'));
      
      $manager = Doctrine_Manager::getInstance();
      $conn = Doctrine_Manager::connection($databases['dev']['dsn']);
      
      $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
  	}
  }
  
  public static function loadRoute()
  {
  	
  	$array = sfYaml::load(rtConfig::get('rt_app_config_dir') . DS . 'routing.yml');
  	
  	$routes = array();
  	foreach ($array as $name => $route)
  	{
  		$routes[] = array($name, $route['url'], $route['params']);
  		// formated route
  		$routes[] = array("{$name}_formatted", $route['url'] . '.:format', $route['params']);
  	}
  	
  	rtRoute::connect($routes);
  }
  
  /**
   * Load config files
   *
   */
  public static function loadConfig()
  {
  	$config = sfYaml::load(rtConfig::get('rt_config_dir') .  DS . 'config.yml');
  	
  	rtConfig::init($config);
  }
  
  
  public static function registerViewClasses()
  {
  	$classes = array();
  	$views = rtConfig::get('views');
  	foreach ($views as $view)
  	{
  		$classes[] = call_user_func(array($view, 'register'));
  	}
  	rtConfig::set('rt.view.classes', $classes);
  }
  
  /**
   * Get the current rt version
   *
   */
  public static function getVersion()
  {
  	return self::MAJOR_VERSION . self::MINOR_VERSION . self::REVISION;
  }
}

require_once RT_CORE_DIR . DS . 'rtUtils.class.php';

// exception handler
function exception_handler(Exception $e)
{
  //	ob_clean();
	ob_start();
	echo "<div id=\"msg\">" . $e->getMessage() . "</div>";
	echo "<pre>" . $e->getTraceAsString() . "</pre>";
	
	echo "<hr /> Vars:<br /><pre>" . var_dump(rtRequest::getInstance()) . "</pre>";
	$content = ob_get_clean();

  // findTemplateFileName may throw exception, but PHP5.2.5 can't handle this
  try
  {
    list($file, $view_class) = findTemplateFileName(RT_CORE_DIR . DS . 'default' . DS . 'layout');
    $view = new $view_class($file, array('mf_layout_content' => $content));
    $view->display();
  }
  catch (Exception $e_in)
  {
	  echo $content . "<hr />";
	  echo "<div>" . $e_in->getMessage() . "</div>";
	  echo "<pre>" . $e_in->getTraceAsString() . "</pre>";
  }
}

set_exception_handler('exception_handler');
