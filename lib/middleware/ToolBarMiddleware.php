<?php

class ToolBarMiddleware
{
	public function process_request()
	{
		// add jquery files
		mfResponse::getInstance()->addJavascript('jquery', 'mf_toolbar');
		mfResponse::getInstance()->addStylesheet('mf_toolbar');	
	}
	
	public function process_response()
	{
		if(!mfConfig::get('enable_toolbar', false)) return ;
		
		$params = array(
			'logs' => rtLogger::getLogs()
		);
		
		$params = array_merge($params, rtController::getMagicViewVars());
		
		list($tpl, $view_class) = findTemplateFileName(MF_CORE_DIR . DS . 'default' . DS . 'toolbar');
		
		$toolbar = new $view_class($tpl, $params);
		
		$response = rtResponse::getInstance();
		
		$new_body = str_replace('</body>', 
								$toolbar->getOutput() . '</body>', 
								$response->getBody());
		$response->setBody($new_body);
		
	}
}