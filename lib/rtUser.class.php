<?php
class rtUser
{
    private static $_instance;
    private $authenticated = false;
    private $credentials = array();
    
    private function __construct()
    {
    	if(isset($_SESSION['rt.user']['authenticated']))
    	{
    		$this->authenticated = true;
    	}
    }
    
    
    /**
     * Check if user has authenticated
     *
     */
    public function authenticated()
    {
    	return $this->authenticated;
    }
    
    public function setAuthenticated()
    {
    	$this->authenticated = true;
    }
    
    public function hasCredential($credit)
    {
    	return array_search($credit, $this->credentials) !== FALSE; 
    }
    
    
	
    /**
     * get request instance
     *
     * @return rtUser
     */
    public static function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new self(); 
        }
        return self::$_instance;
    }
}
