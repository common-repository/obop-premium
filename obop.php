<?php 
    class obop
    {
        const OBOP_OK = 1;
        const OBOP_REDIRECT = 2;
        const OBOP_NOT_IDENTIFIED = 3;
        
        private static $_isObopOk;
        
        public static function init()
        {
            if(isset($_POST["obop-uuid"]))
                self::verifyToken($_POST["obop-uuid"]);
        }
        
        public static function status()
        {
            if((isset($_COOKIE["isObopOk"]) && $_COOKIE["isObopOk"] == true) || self::$_isObopOk)
                return self::OBOP_OK;
            elseif(isset($_COOKIE["isObopAuth"]) && !isset($_COOKIE["isObopOk"]))
                return self::OBOP_REDIRECT;
            else
                return self::OBOP_NOT_IDENTIFIED;
        }
        
        private static function verifyToken($token)
        {
            $client = new SoapClient('http://dev.obop.co/ws/checkToken');
					
			if($client->checkToken($token))
            {
                setcookie("isObopOk", true);
                
                self::$_isObopOk = true;
            }
            else
                setcookie("isObopOk", false);
        }
    }
?>