<?php
class rtComponent
{
	protected $_view_vars_ = array();
	protected $_template_;
	protected $_component_name_;
	
    public function execute($component, $func, $vars=array())
    {
    	$func_up = ucwords($func);
    	$real_func ="execute{$func_up}";
        if (!method_exists($this, $real_func)) {
            throw new Exception("Function: '$real_func' doesn't exist in component:" . get_class($this));
        }        
        
        // call the component function
        call_user_func(array($this, $real_func));
        
        // render view
        $this->_template_ = rtConfig::get('rt_app_views_dir') . DS . 'components' . DS . $component . DS . "_$func";
        list($file, $view_class) = findTemplateFileName($this->_template_);
        
        // merge the use defined vars
        $view = new $view_class($file, array_merge($this->_view_vars_, $vars, $this->getMagicViewVars()));
        
        return $view->getOutput();
    }
    
    // Now view support direct assign
    // set function $this->name = $value
    protected function __set($name, $value)
    {
        $this->_view_vars_[$name] = $value;
    }
    protected function __get($name)
    {
        return isset($this->_view_vars_[$name]) ? $this->_view_vars_[$name] : NULL;
    }
    
    /**
     * Get Magic View Vars
     *
     * @return array
     */
    public function getMagicViewVars()
    {
        $magic_vars = array(
           'rt_flash' => rtFlash::getInstance(),
           'rt_request' => rtRequest::getInstance(),
           'rt_user' => rtUser::getInstance()
        );
        return $magic_vars;
    }

}