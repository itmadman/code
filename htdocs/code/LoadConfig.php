<?php 
Class LoadConfig{
	private static $_config=null;

	public static function setConfig($config){
		self::$_config = $config;
	}
	public static function getConfig(){
		return self::$_config;
	}

}

