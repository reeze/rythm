<?php

/**
 * Dispatcher
 **/
class rtDispatcher
{
    public static function dispatch(rtAppConfig $config)
    {
      // Load the project's config
    	    	
    	
    	Rythm::init();
    	
    	// Load the application's config
    	
    	
    	
    	// Route the request
    	rtRoute::route();
    	
    	//============================================================
    	// Start Handle the request
    	//============================================================
    	
    	// Initial middleware classes
    	$middleware_classes = rtConfig::get('middlewares', array());
    	
    	$middlewares = array();
    	foreach ($middleware_classes as $middleware_class) 
    	{
    		require_once "middleware/$middleware_class.php";
    		$middlewares[] = new $middleware_class;;
    	}
    	    	
    	// ===========================================
    	// middleware start process request
    	$request = rtRequest::getInstance();
    	foreach($middlewares as $middleware)
    	{
    		if(method_exists($middleware, 'process_request'))
    		{
    			$middleware->process_request($request);
    		}
    	}
    	// middleware end process request
    	
    	// ===========================================
    	// Core Process
    	$controller = $request->getController();
    	$action = $request->getAction();
    	
		  $controller_class = ucfirst($controller) . 'Controller';
		  
		  if(!class_exists($controller_class)) throw new rtException("Can't find Controller: $controller_class");
		  
		  $controller = new $controller_class;
		  if(!$controller instanceof rtController) throw new rtException("Controller:$controller_class must extend from rtController class");
		  
		  $controller->execute($action . 'Action', $request);
		 	// End Core Process
		  
		  
		  // ===========================================
		  // start process response
		  $response = rtResponse::getInstance();
		  // response middleware process in the reverse direction
		  foreach (array_reverse($middlewares) as $middleware)
		  {
				if(method_exists($middleware, 'process_response'))
				{
				  $middleware->process_response($response);
				}
		  }
		  // end process response
    }
}
