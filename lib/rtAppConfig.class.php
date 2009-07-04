<?php

class rtAppConfig
{
	/**
	 * get the application's config
	 *
	 * @param unknown_type $app_name
	 * @param unknown_type $env
	 */
	public static function getAppConfig($app_name, $env)
	{
		$app_conf = new AppConfig();
		$app_conf->setup();
		
		// init frameowk's dirs
		// TODO Make these dirs configable
		rtConfig::set('rt_apps_dir', rtConfig::get('rt_project_dir') . DS . 'apps');
		rtConfig::set('rt_app_dir', rtConfig::get('rt_apps_dir') . DS . $app_name);
		rtConfig::set('rt_app_config_dir', rtConfig::get('rt_app_dir') . DIRECTORY_SEPARATOR . 'config');
		rtConfig::set('rt_app_controllers_dir', rtConfig::get('rt_app_dir') . DS . 'controllers');
		rtConfig::set('rt_app_components_dir', rtConfig::get('rt_app_dir') . DS . 'components');
		rtConfig::set('rt_app_views_dir', rtConfig::get('rt_app_dir') . DS . 'views');
		rtConfig::set('rt_app_views_layout_dir', rtConfig::get('rt_app_views_dir') . DS . 'layout');
		rtConfig::set('rt_models_dir', rtConfig::get('rt_project_dir') . DS . 'lib' . DS . 'models');
		rtConfig::set('rt_app_helpers_dir', rtConfig::get('rt_app_dir') . DS . 'helper');
		rtConfig::set('rt_config_dir', rtConfig::get('rt_project_dir') . DS . 'config');
		rtConfig::set('rt_web_dir', rtConfig::get('rt_project_dir') . DS . 'web');
		rtConfig::set('rt_data_dir', rtConfig::get('rt_project_dir') . DS . 'data');
		
		
		rtAutoloader::addPath(rtConfig::get('rt_project_dir') . DS . 'lib');
		rtAutoloader::addPath(
		                    rtConfig::get('rt_models_dir'),
		                    rtConfig::get('rt_models_dir') . DS . 'generated',
		                    rtConfig::get('rt_app_controllers_dir'),
		                    rtConfig::get('rt_app_components_dir')
		                    );
    
		// setup app config
		require rtConfig::get('rt_app_config_dir') . DS . $app_name . 'AppConfig.class.php';
		$app_class_name = $app_name . 'AppConfig';
		$class = new $app_class_name();
		$class->setup();
		
		return $class;
	}
	
	public function getAppName()
	{
		return $this->app_name;
	}
	public function getEnv()
	{
		return $this->env;
	}
}
