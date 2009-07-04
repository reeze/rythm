<?php
/**
 * Common Middleware class
 *
 */
class CommonMiddleware
{
    public static $ViewStylesheets = array();
    public static $ViewJavascripts = array();
    
	public function process_request($request)
	{
		// handle Flash system should initialized before any others
		// so we clean here
		// TODO do we need ajax request check before clean flash?
		rtFlash::getInstance()->clean();
		
		return $request;
	}
	/**
	 * This is last middleware to output the content of page
	 *
	 */
	public function process_response($response)
	{
		$response->display();
		return $response;
	}	
}