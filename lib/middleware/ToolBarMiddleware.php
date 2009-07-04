<?php

class ToolBarMiddleware
{
	public function process_request()
	{
		// add jquery files
		rtResponse::getInstance()->addJavascript('jquery', 'rt_toolbar');
		rtResponse::getInstance()->addStylesheet('rt_toolbar');	
	}
	
	public function process_response()
	{
		if(!rtConfig::get('enable_toolbar', false)) return ;
		
		$params = array(
			'logs' => rtLogger::getLogs()
		);
		
		$params = array_merge($params, rtController::getMagicViewVars());
		
		list($tpl, $view_class) = findTemplateFileName(rtConfig::get('rt_core_dir') . DS . 'default' . DS . 'toolbar');
		
		$toolbar = new $view_class($tpl, $params);
		
		$response = rtResponse::getInstance();
		
		$new_body = str_replace('</body>', 
								$toolbar->getOutput() . '</body>', 
								$response->getBody());
		$response->setBody($new_body);
		
	}
}