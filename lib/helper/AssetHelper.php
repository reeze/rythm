<?php

/**
 * Asset helpers
 */

/**
 * Output the added javascripts
 */
function include_javascripts()
{
	// we may have new javascripts
	if(($new = func_get_args()))
	{
		mfResponse::getInstance()->addJavascript($new);
	}
	
	$files = rtResponse::getInstance()->getJavascripts();
	$output = '';
	foreach ($files as $file)
	{
		$output .= "<script src='/js/$file.js' type='text/javascript'></script>\n"; // TODO rewrite it ,hard code here		
	}

	echo $output;
}

/**
 * Output the added stylesheets
 */
function include_stylesheets()
{
	// we may have new stylesheet
	if(($new = func_get_args()))
	{
		mfResponse::getInstance()->addStylesheet($new);
	}
	
	$files = rtResponse::getInstance()->getStylesheets();
	$output = '';
	foreach ($files as $file)
	{
		$output .= "<link type='text/css' rel='stylesheet' href='/css/$file.css' />\n"; // TODO rewrite it ,hard code here		
	}

	echo $output;
}