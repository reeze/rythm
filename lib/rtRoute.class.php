<?php
class rtRoute
{
	private static $_routes = array();
	
	
	/**
	 * Add routes rule
	 *
	 * @param array $array
	 * 			array('route_name', // empty for anonymous route
	 * 				  'rule', 		// rule
	 * 				  array('a' => 1)	// array $params 
	 * 				);
	 */
	public static function connect($routes)
	{
		self::$_routes = $routes;
	}
	/**
	 * Add route to routes table
	 *
	 * @param array $route
	 */
	public static function prepend($route)
	{
		self::$_routes = array_merge($route, self::$_routes);
	}
	
	/**
	 * Push route to the end of the routes table
	 *
	 * @param array $route
	 */
	public static function push($route)
	{
		self::$_routes = array_merge(self::$_routes, $route);
	}
	
	/**
	 * Route the request
	 * @return array array($controller, $action, $params);
	 */
	public static function route()
	{
		$request = rtRequest::getInstance();
		
		// match the routes
		foreach (self::$_routes as $route)
		{
			list($rule, $tokens) = self::compile_route($route[1]);
			
			
			if(preg_match($rule, $request->getParameter('request_path_info'), $matches) == 1)
			{
				// fill the request
				foreach ($tokens as $i => $token)
				{
					$request->setParameter($token, $matches[$i + 1]);
				}
				
				// fill with params
				if(isset($route[2]) && is_array($route))
				{
					foreach($route[2] as $key => $value)
					{
						$request->setParameter($key, $value);
					}
				}
				
				rtLogger::log(ROUTE_LOG, "matched route: {$route[1]}");
				return;
			}
			// Log matching
			rtLogger::log(ROUTE_LOG, "mismatch route: {$route[1]}");
		}
		// no route match
		throw new RouterExecption('No route match:' . $_SERVER['REQUEST_URI']);
	}
	
	
	/**
	 * generate the internal url to friendly url
	 * We use syrtony style internal url:
	 * 	- controller/action?params=value
	 *  - @named_route?params=value
	 * 
	 * @param string $internal_url
	 * @param Boolean $absolut whether return absolute url
	 * 
	 * @return url
	 */
	public static function generate($internal_url)
	{
		$old_internal_url = $internal_url; // just for exception throw
		
		if(empty($internal_url)) $internal_url = '@homepage'; // default route
		
		// parse params
		$params = array();
		$hash = '';
		
		// hash handle
		if(($pos_hash = strpos($internal_url, '#')))
		{
			$hash = substr($internal_url, $pos_hash + 1);
			
			// trim the end hash
			$internal_url = substr($internal_url, 0, 
								strlen($internal_url) - strlen($hash) - 1);
		}
		
		if(($pos_q = strpos($internal_url, '?')))
		{
			$query = substr($internal_url, $pos_q + 1);
			$pairs = split('&', $query);
			
			foreach ($pairs as $pair)
			{
				list($key, $value) = explode('=', $pair);
				if($key) $params[$key] = $value;
			}
			
			// trim the query string
			$internal_url = substr($internal_url, 0,
								strlen($internal_url) - strlen($query) - 1);
		}
		
		// named route start with '@'
		if($internal_url[0] == '@')
		{
			$named_route = substr($internal_url, 1);
		}
		else
		{
			list($controller, $action) = explode('/', $internal_url);
			if(!$action) $action = 'index'; // default action name
			
			$params['controller'] = $controller;
			$params['action'] = $action;
		}
		
		foreach (self::$_routes as $route)
		{
			if(isset($named_route))
			{
				// find the named route and return the url
				if($route[0] == $named_route)
				{
					return self::fill_route($route, $params, $hash);
				}
				else continue;
			}
			else
			{
				// trying to match the route with params
				if(self::match($route, $params))
				{
					return self::fill_route($route, $params, $hash);
				}
			}
		}
		
		// can't generate url
		throw new RouterExecption("Can't generate url for $old_internal_url");
	}
	
	/**
	 * Fill the route with params and return the url
	 *
	 * @param array $route
	 * @param array $params
	 * @param Boolean
	 * 
	 * @return string the filled url
	 */
	public static function fill_route($route, $params, $hash)
	{
		$rule = $route[1];
		$default_params = isset($route[2]) ? $route[2] : array();
		
		$new_params = array_merge($params, $default_params);
		
		// merge the default params
//		$params = array_merge($route[2], $params);
//		Debug::p($params);
		foreach ($new_params as $key => $value)
		{
			if(strpos($rule, ":$key") !== false)
			{
				// replace it
				$rule = str_replace(":$key", $value, $rule);
				
				// pop params out
				unset($params[$key]);
			}
			else
			{
				if(isset($default_params[$key]))
				{
					unset($params[$key]); // it has a default value
				}
			}
		}
		
		if(strpos($rule, ':') !== false)
		{
			throw new RouterExecption("Route:$rule, Missing require paramter");
		}
		
		$query =  http_build_query($params);
		$url = $query ? "$rule?" . $query : $rule;
		
		if($hash) $url = "$url#" . $hash;
		
		return $url;		
	}
	
	/**
	 * Check if the route match the given params
	 * XXX It' hard to understand. but it works
	 *
	 * @param array $route: array('rule', array('params'));
	 * @param array $params
	 * @return Boolean
	 */
	public static function match($route, $params)
	{
//		echo "match:" . $route[1] . '<br />';
		list(, $tokens) = self::compile_route($route[1]);
		
		
		if(count($tokens) > count($route[2]) + count($params)) return false;
		
		foreach ($tokens as $token)
		{
			if(!isset($route[2][$token]))
			{
				if(!isset($params[$token]))
				{
					return false;
				}
				
			}
			if(isset($route[2][$token])) unset($route[2][$token]);
			if(isset($params[$token])) unset($params[$token]);
		}
		
//		Debug::p(array_intersect($route[2], $params));
		if($params['controller'] != $route[2]['controller'] ||
		   $params['action'] != $route[2]['action'])
		{
			return false;
		}
				
		return true;
	}
		
	
	
	
	
	
	// =================================================================
	/**
	 * FIXME It's somehow ungly here
	 * TODO  add route cache
	 *
	 * @var array
	 */
	public static $compile_tokens = array();
	/**
	 * Compile the config route to regex match
	 *
	 * @param string $route
	 * @return array regex
	 */
	private static function compile_route($route)
	{
		self::$compile_tokens = array();
		// internel call back function for regex replacement
		if(!function_exists('_callback'))
		{
			function _callback($match)
			{
				rtRoute::$compile_tokens[] = $match[1];
				//var_dump(Router::$compile_tokens);echo "<br />";
				
				return "([a-zA-Z0-9\-_%]+)";
			}
		}
		
		$compile_tokens = array();
		// Strip regex chars: / + ? . and _ -
		$reg_strip_parten = "/([\/\._-])/";
		$reg_strip_repalce = '\\\$1'; // replace with \(regex char)
		
		$route = preg_replace($reg_strip_parten, $reg_strip_repalce, $route);
		// FIXME more characters should allowed in match parten		
		$route = preg_replace_callback("/:([a-zA-Z0-9\-_%]+)/", '_callback', $route);
		

		$route = "/^$route$/"; // add regex slash	
//		echo $route . "<br />";
		return array($route, self::$compile_tokens);
	}
	
}

class RouterExecption extends rtException
{ }

