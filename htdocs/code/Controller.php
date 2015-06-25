<?php

Class controller{

	/*protected function render($str){

		echo '成功啦';

	}*/

	const CONTROLLER_NAME = 'site';



	const ACTION_NAME = 'index';



	//指定模板目录

	private $template_dir;



	//编译后的目录



	private $compile_dir;



	//读取模板中所有变量的数组



	private $arr_var=array();



	//控制器名



	private $controller_name;



	//方法名

	private $action_name;



	//构造方法



	public function __construct($template_dir="./view",$compile_dir="./runtime/template_c")



	{



	$this->template_dir=rtrim($template_dir,"/")."/";



	$this->compile_dir=rtrim($compile_dir,"/")."/";



	$this->controller_name = $this->getControllerName();



	$this->action_name = $this->getControllerName(true);



	}



	public function getControllerName($type=false){

		if(isset($_GET['r'])){
			
			$site = $type ? explode('/', URL::unEncryption($_GET['r']))[1] : explode('/', URL::unEncryption($_GET['r']))[0];

		}else{
			
			$site=$type ? self::ACTION_NAME : self::CONTROLLER_NAME;

		}

		return strtolower($site);

	}



	//模板中变量分配调用的方法



	/*public function render($tpl_var,$value=null){



	$this->arr_var[$tpl_var]=$value;



	}*/

	protected function YhgbdIungest($data=array()){

		foreach ($data as $key => $value) {

			$this->arr_var[$key]=$value;

		}

	}



	//调用模板显示



	public function render($data=array(),$fileName=''){

		if(is_array($data)){$this->YhgbdIungest($data);}

		//如果没有文件么传过来则默认方法名

		if(empty($fileName)){$fileName = $this->action_name;}



	$tplFile=$this->template_dir.rtrim($this->controller_name,"/")."/".$fileName.'.html';



	if(!file_exists($tplFile)){



	return false;



	}



	//定义编译合成的文件 加了前缀 和路径 和后缀名.php



	$comFileName=$this->compile_dir.rtrim($this->controller_name,"/")."/"."com_".$fileName.".php";



	$comFileTempName = $this->compile_dir.rtrim($this->controller_name,"/")."/";

	//判断目录不存在则建之

	if(!is_readable($comFileTempName)){is_file($comFileTempName) or mkdir($comFileTempName,0700);}



	if(!file_exists($comFileName) || filemtime($comFileName)< filemtime($tplFile)){//如果缓存文件不存在则 编译 或者文件修改了也编译



	$repContent=$this->tmp_replace(file_get_contents($tplFile));//得到模板文件 并替换占位符 并得到替换后的文件



	file_put_contents($comFileName,$repContent);//将替换后的文件写入定义的缓存文件中



	}



	//包含编译后的文件



	include $comFileName;



	}



	//替换模板中的占位符



	private function tmp_replace($content){



	$pattern=array(



	//'/\<\!--\s*\$([a-zA-Z]*)\s*--\>/i'

		'/\{\$([a-zA-Z]*)}/i',
		//'/{\s*\$([a-zA-Z]*)\s*}/i',

	);



	$replacement=array(



	'$this->arr_var["${1}"]',
	/*'<?php $this->arr_var["${1}"]; ?>',*/

	);



	$repContent=preg_replace($pattern,$replacement,$content);



	return $repContent;



	}



}

function M(){

	require_once('code/LoadConfig.php');

	require_once('config/main.php');

	LoadConfig::setConfig($config);

	require('code/PDOMysql.php');

	return new pdomysql();

}



function D($table){

	require_once('code/LoadConfig.php');

	require_once('config/main.php');

	LoadConfig::setConfig($config);

	require_once('code/DAOMysql.php');

	return new DAOMysql($table);

}



function Model($model_name){

	$path = 'model/'.ucwords($model_name).'Model.php';

	require_once($path);

	$class_name = ucwords($model_name).'Model';

	return  new $class_name();

}



function dump($arr){

	echo '<pre>';

	print_r($arr);

	echo '</pre>';

	exit;

}