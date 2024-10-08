<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_sqlsrv_driver extends CI_DB {

	var $dbdriver = 'sqlsrv';

	
	var $_escape_char = '';

	
	var $_like_escape_str = " ESCAPE '%s' ";
	var $_like_escape_chr = '!';

	
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' ASC'; 

	
	function db_connect($pooling = false)
	{
		
		$character_set = (0 === strcasecmp('utf8', $this->char_set)) ? 'UTF-8' : $this->char_set;

		$connection = array(
			'UID'				=> empty($this->username) ? '' : $this->username,
			'PWD'				=> empty($this->password) ? '' : $this->password,
			'Database'			=> $this->database,
			'ConnectionPooling' => $pooling ? 1 : 0,
			'CharacterSet'		=> $character_set,
			'ReturnDatesAsStrings' => 1
		);
		
		
		if(empty($connection['UID']) && empty($connection['PWD'])) {
			unset($connection['UID'], $connection['PWD']);
		}

		return sqlsrv_connect($this->hostname, $connection);
	}

	
	function db_pconnect()
	{
		$this->db_connect(TRUE);
	}

	
	function reconnect()
	{
		
	}

	
	function db_select()
	{
		return $this->_execute('USE ' . $this->database);
	}

	
	function db_set_charset($charset, $collation)
	{
		
		return TRUE;
	}

	
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		return sqlsrv_query($this->conn_id, $sql, null, array(
			'Scrollable'				=> SQLSRV_CURSOR_STATIC,
			'SendStreamParamsAtExec'	=> true
		));
	}

	
	function _prep_query($sql)
	{
		return $sql;
	}

	
	function trans_begin($test_mode = FALSE)
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}

		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}

		
		$this->_trans_failure = ($test_mode === TRUE) ? TRUE : FALSE;

		return sqlsrv_begin_transaction($this->conn_id);
	}

	
	function trans_commit()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}

		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}

		return sqlsrv_commit($this->conn_id);
	}

	
	function trans_rollback()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}

		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}

		return sqlsrv_rollback($this->conn_id);
	}

	
	function escape_str($str, $like = FALSE)
	{
		
		return str_replace("'", "''", $str);
	}

	
	function affected_rows()
	{
		return @sqlrv_rows_affected($this->conn_id);
	}

	
	function insert_id()
	{
		return $this->query('select @@IDENTITY as insert_id')->row('insert_id');
	}

	
	function _parse_major_version($version)
	{
		preg_match('/([0-9]+)\.([0-9]+)\.([0-9]+)/', $version, $ver_info);
		return $ver_info[1]; // return the major version b/c that's all we're interested in.
	}

	
	function _version()
	{
		$info = sqlsrv_server_info($this->conn_id);
		return sprintf("select '%s' as ver", $info['SQLServerVersion']);
	}

	function count_all($table = '')
	{
		if ($table == '')
			return '0';
	
		$query = $this->query("SELECT COUNT(*) AS numrows FROM " . $this->dbprefix . $table);
		
		if ($query->num_rows() == 0)
			return '0';

		$row = $query->row();
		$this->_reset_select();
		return $row->numrows;
	}

	
	function _list_tables($prefix_limit = FALSE)
	{
		return "SELECT name FROM sysobjects WHERE type = 'U' ORDER BY name";
	}

	
	function _list_columns($table = '')
	{
		return "SELECT * FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = '".$this->_escape_table($table)."'";
	}

	
	function _field_data($table)
	{
		return "SELECT TOP 1 * FROM " . $this->_escape_table($table);	
	}

	
	function _error_message()
	{
		$error = array_shift(sqlsrv_errors());
		return !empty($error['message']) ? $error['message'] : null;
	}

	
	function _error_number()
	{
		$error = array_shift(sqlsrv_errors());
		return isset($error['SQLSTATE']) ? $error['SQLSTATE'] : null;
	}

	
	function _escape_table($table)
	{
		return $table;
	}	


	
	function _escape_identifiers($item)
	{
		return $item;
	}

	
	function _from_tables($tables)
	{
		if ( ! is_array($tables))
		{
			$tables = array($tables);
		}

		return implode(', ', $tables);
	}

	
	function _insert($table, $keys, $values)
	{	
		return "INSERT INTO ".$this->_escape_table($table)." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}

	
	function _update($table, $values, $where)
	{
		foreach($values as $key => $val)
		{
			$valstr[] = $key." = ".$val;
		}
	
		return "UPDATE ".$this->_escape_table($table)." SET ".implode(', ', $valstr)." WHERE ".implode(" ", $where);
	}
	
	
	function _truncate($table)
	{
		return "TRUNCATE ".$table;
	}

	
	function _delete($table, $where)
	{
		return "DELETE FROM ".$this->_escape_table($table)." WHERE ".implode(" ", $where);
	}

	
	function _limit($sql, $limit, $offset)
	{
		$i = $limit + $offset;
	
		return preg_replace('/(^\SELECT (DISTINCT)?)/i','\\1 TOP '.$i.' ', $sql);		
	}

	
	function _close($conn_id)
	{
		@sqlsrv_close($conn_id);
	}

}

