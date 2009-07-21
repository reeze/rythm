<?php

/**
 * Plugin system
 *
 */
class rtPlugin
{
	/**
	 * Initialize the plugin
	 * used to listen to the event happened
	 *
	 */
	public function init()
	{
		throw new rtException("Plugin have to imply");
	}
	
	/**
	 * install the plugin
	 *
	 */
	public function install(){}
	
	/**
	 * Uninstall the plugin
	 *
	 */
	public function uninstall(){}
}