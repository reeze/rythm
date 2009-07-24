<?php
/**
 * Basic tag helper
 */


/**
 * generate a tag for the given tag name
 *
 * @param string $tag_name
 * @param mix $value
 * @param array $options
 * @param boolean $close
 */
function tag($tag_name, $text, $options=array(), $close=false)
{
	$str = "<$tag_name";
	foreach ($options as $key => $value) {
		$str .= " $key=\"$value\"";
	}
	if($close)
	{
		$str .=" />";
		return $str;
	}
	
	$str .= ">$text</$tag_name>";
	return $str;
}

function image_tag($filename, $options=array())
{
	return tag('img', NULL, array_merge($options, array('src' => image_path($filename))), true);
}

function image_path($filename)
{
	if(!strpos($filename, '.'))
	{
		$filename .= ".png"; // default suffix
	}
	
	return "/images/$filename";
}

function javascript_tag()
{

}

function javascript_path($filename)
{
	if(!strpos($filename, '.'))
  {
    $filename .= ".js"; // default suffix
  }
  return "/js/$filename";
}
