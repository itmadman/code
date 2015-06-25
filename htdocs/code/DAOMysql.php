<?php 

Class DAOMysql{
	 public static $DB = null;
	 public static $stmt = null;
	 private static $parms = array ();
	 public static $table = '';
	 public static $dbtype = 'mysql';
	 public static $dbhost = '';
	 public static $dbport = '';
	 public static $dbname = '';
	 public static $dbuser = '';
	 public static $dbpass = '';
	 public static $charset = '';
	 public static $connect = '';
	 public static $debug = false;
	private $sql=[
		'from'=>'',
		'where'=>'',
		'order'=>'',
		'limit'=>'',
		'select'=>'',
		'one'=>'',
		'all'=>'',
		'count'=>'',
		'leftjoin'=>'',
	];

	public function __construct($table){
		  self::$dbtype = 'mysql';
		  self::$dbhost = LoadConfig::getConfig()['db']['host'];
		  self::$dbport = '3306';
		  self::$dbname = LoadConfig::getConfig()['db']['name'];
		  self::$dbuser = LoadConfig::getConfig()['db']['user'];
		  self::$dbpass = LoadConfig::getConfig()['db']['password'];
		  self::$connect = LoadConfig::getConfig()['db']['connect'];
		  self::$charset = LoadConfig::getConfig()['db']['charset'];
		  self::connect ();
		  self::$table = $table;
		  self::execute ( 'SET NAMES ' . self::$charset );
	}

	 public function execute($sql) {
  		self::getPDOError ( $sql );
  		return self::$DB->exec ( $sql );
 	}

 	private function getPDOError($sql) {
	  self::$debug ? self::errorfile ( $sql ) : '';
	  if (self::$DB->errorCode () != '00000') {
	   $info = (self::$stmt) ? self::$stmt->errorInfo () : self::$DB->errorInfo ();
	   echo (self::sqlError ( 'mySQL Query Error', $info [2], $sql ));
	   exit ();
	  }
 	}

 	private function sqlError($message = '', $info = '', $sql = '') {
   
		  $html = '';
		  if ($message) {
		   $html .=  $message;
		  }
		   
		  if ($info) {
		   $html .= 'SQLID: ' . $info ;
		  }
		  if ($sql) {
		   $html .= 'ErrorSQL: ' . $sql;
		  }
		   
		  throw new Exception($html);
 	}

	 public function connect() {
		  try {
		   self::$DB = new PDO ( self::$dbtype . ':host=' . self::$dbhost . ';port=' . self::$dbport . ';dbname=' . self::$dbname, self::$dbuser, self::$dbpass, array (
		     PDO::ATTR_PERSISTENT => self::$connect
		   ) );
		  } catch ( PDOException $e ) {
		   die ( "Connect Error Infomation:" . $e->getMessage () );
		  }
 	}

	public function where($where){
		$sql='';
		if(is_array($where)){
			foreach($where as $k=>$v){
				$sql .=$k.'='.$v; 
			}
		}else{
			$sql = $where;
		}
		 $this->sql['where']=' WHERE '.$sql;
		 return $this;
	}

	public function leftjoin($joinTable,$on){
		$this->sql['leftjoin']=" LEFT JOIN $joinTable ON $on";
		return $this;
	}

	public function order($order){
		if(is_array($order))return false;
		 $this->sql['order'] = " ORDER BY $order";
		 return $this;
	}

	public function limit($offset='0',$size='20'){
		 $this->sql['limit'] = " LIMIT $offset,$size";
		 return $this;
	}

	public function select($select='*'){
		$this->sql['select'] = 'SELECT '.$select;
		return $this;
	}

	public function one(){
		$sql=$this->sql['select'].' FROM '.self::$table.$this->sql['leftjoin'].$this->sql['where'].$this->sql['order'];

		return self::_fetch ( $sql, $type = '0' );
	}

	public function all(){
		$sql=$this->sql['select'].' FROM '.self::$table.$this->sql['leftjoin'].$this->sql['where'].$this->sql['order'].$this->sql['limit'];
		
		return self::_fetch ( $sql, $type = '1' );
	}

	public function count(){
		$sql=$this->sql['select'].' FROM '.self::$table.$this->sql['leftjoin'].$this->sql['where'];

		return self::_fetch ( $sql, $type = '2' );
	}

	private function _fetch($sql, $type) {
		  $result = array ();
		  self::$stmt = self::$DB->query ( $sql );
		  self::getPDOError ( $sql );
		  self::$stmt->setFetchMode ( PDO::FETCH_ASSOC );
		  switch ($type) {
		   case '0' :
		    $result = self::$stmt->fetch ();
		    break;
		   case '1' :
		    $result = self::$stmt->fetchAll ();
		    break;
		   case '2' :
		     
		    $result = self::$stmt->rowCount ();
		     
		    break;
		  }
		  self::$stmt = null;
		  return $result;
 	}
}
