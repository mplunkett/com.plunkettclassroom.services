<?php    
	
	class sqlDb 
	{
    	private $connection;
				
        function __construct() 
        {
			//$this->open_conn();
        }
		
        public function open_conn()
        {
			$this->connection = odbc_connect("PlunkettLibrary", 'mlp_Admin', 'Buck$2@14');
			
			if(!$this->connection) {
				die("database connection failure: " . odbc_errormsg($this->connection));
			}
        }
		
        public function close_conn() 
        {
			if(isset($this->connection)) {
            	odbc_close($this->connection);
			}
        }
		
		public function prep_query($sql, $vals)
		{
			$this->open_conn();
			$prep = $this->prep_statement($sql);
			
			if(!$prep) {
				die("database connection failure: " . odbc_errormsg($this->connection));
			}
			else {
				$succ = odbc_execute($prep, $vals);
				
				if(!$succ) {
					die("database connection failure: " . odbc_errormsg($this->connection));
				}
				else {
					return $succ;
				}
			}			
		}
		
        public function query_conn($sql) 
        {
        	$this->open_conn();
            $result = odbc_exec($this->connection, $sql);
			//$this->confirm_query($result);
			if(!$result) {
                die("database query failure: " . odbc_errormsg($this->connection) . "   " . $sql);				
			}
			return $result;
        }
		
		public function query_object($sql) 
		{
			$this->open_conn();
			$res = odbc_exec($this->connection, $sql);
			$this->confirm_query($res);
			$dObject = $this->fetch_object($res);
			
			return $dObject;
		}
		
        private function confirm_query($result_set) 
        {
            if(!$result_set) {
                die("database query failure: " . odbc_errormsg($this->connection));				
			}
			//$this->close_conn();
        }
		
		private function prep_statement($sql)
		{
			$statemnt = odbc_prepare($this->connection, $sql);
			return $statemnt;
		}
		
        public function fetch_array($result_set)
        {
            return odbc_fetch_array($result_set);
        }
		
		public function fetch_object($result_set)
		{
			return odbc_fetch_object($result_set);
		}
    
        public function fetch_assoc($result_set, $row)
        {
            return odbc_fetch_array($result_set, $row);
        }
        
        public function num_rows($result_set)
        {
       		return odbc_num_rows($result_set);
        }
        
        public function free_result($result_set)
        {
        	odbc_free_stmt($result_set);
        }
	}
?>