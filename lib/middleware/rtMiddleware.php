<?php
/**
 * Abstract Middleware class
 *
 */
abstract class Middleware
{
	/**
	 * Process the request
	 *
	 * @param Request $request
	 * @return Request
	 */
	abstract public function process_request(Request $request);
	
	/**
	 * Process Response
	 *
	 * @param Response $response
	 * @param Response
	 */
	abstract public function process_response(Response $response);
	
	/**
	 * Handle the exceptions
	 *
	 * @param Exception $exception
	 */
	abstract public function process_exception(Exception $exception);
}