<?php
/**
 * rt standard helper UrlHelper
 * 
 * TODO imply it 
 */

function link_to($text, $url, $options=array(), $absolute=false)
{
	return tag('a', $text, array_merge($options, array('href' => url_for($url, $absolute))));
}

// get the url link
function url_for($url, $absolute=false)
{
	return rtRoute::generate($url, $absolute);
}
