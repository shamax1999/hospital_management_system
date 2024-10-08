<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_odbc_driver extends CI_DB {

	var $dbdriver = 'odbc';

	
	var $_escape_char = '';

	
	var $_like_escape_str = " {escape '%s'} ";
	var $_like_escape_chr = '!';

	
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword;


	function __construct($params)
	{
		parent::__construct($params);

		$this->_random_keyword = ' RND('.time().')';
	}

	
	function db_connect()
	{
		return @odbc_connect($this->hostname, $this->username, $this->password);
	}

	
	function db_pconnect()
	{
		return @odbc_pconnect($this->hostname, $this->username, $this->password);
	}

	
	function reconnect()
	{
		
	}

	
	function db_select()
	{
		
		return TRUE;
	}

	
	function db_set_charset($charset, $collation)
	{
		
		return TRUE;
	}

	
	function _version()
	{
		return "SELECT version() AS ver";
	}

	
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		return @odbc_exec($this->conn_id, $sql);
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

		return odbc_autocommit($this->conn_id, FALSE);
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

		$ret = odbc_commit($this->conn_id);
		odbc_autocommit($this->conn_id, TRUE);
		return $ret;
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

		$ret = odbc_rollback($this->conn_id);
		odbc_autocommit($this->conn_id, TRUE);
		return $ret;
	}

	
	function escape_str($str, $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = $this->escape_str($val, $like);
			}

			return $str;
		}

		
		$str = remove_invisible_characters($str);

		
		if ($like === TRUE)
		{
			$str = str_replace(	array('%', '_', $this->_like_escape_chr),
								array($this->_like_escape_chr.'%', $this->_like_escape_chr.'_', $this->_like_escape_chr.$this->_like_escape_chr),
								$str);
		}

		return $str;
	}

	
	function affected_rows()
	{
		return @odbc_num_rows($this->conn_id);
	}

	
	function insert_id()
	{
		return @odbc_insert_id($this->conn_id);
	}

	
	function count_all($table = '')
	{
		if ($table == '')
		{
			return 0;
		}

		$query = $this->query($this->_count_string . $this->_protect_identifiers('numrows') . " FROM " . $this->_protect_identifiers($table, TRUE, NULL, FALSE));

		if ($query->num_rows() == 0)
		{
			return 0;
		}

		$row = $query->row();
		$this->_reset_select();
		return (int) $row->numrows;
	}

	
	function _list_tables($prefix_limit = FALSE)
	{
		$sql = "SHOW TABLES FROM `".$this->database."`";

		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			
			return FALSE; 
		}

		return $sql;
	}

	
	function _list_columns($table = '')
	{
		return "SHOW COLUMNS FROM ".$table;
	}

	
	function _field_data($table)
	{
		return "SELECT TOP 1 FROM ".$table;
	}

	
	function _error_message()
	{
		return odbc_errormsg($this->conn_id);
	}

	
	function _error_number()
	{
		return odbc_error($this->conn_id);
	}

	
	function _escape_identifiers($item)
	{
		if ($this->_escape_char == '')
		{
			return $item;
		}

		foreach ($this->_reserved_identifiers as $id)
		{
			if (strpos($item, '.'.$id) !== FALSE)
			{
				$str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);

				
				return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
			}
		}

		if (strpos($item, '.') !== FALSE)
		{
			$str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;
		}
		else
		{
			$str = $this->_escape_char.$item.$this->_escape_char;
		}

		
		return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
	}

	
	function _from_tables($tables)
	{
		if ( ! is_array($tables))
		{
			$tables = array($tables);
		}

		return '('.implode(', ', $tables).')';
	}

	
	function _insert($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}

	
	function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
	{
		foreach ($values as $key => $val)
		{
			$valstr[] = $key." = ".$val;
		}

		$limit = ( ! $limit) ? '' : ' LIMIT '.$limit;

		$orderby = (count($orderby) >= 1)?' ORDER BY '.implode(", ", $orderby):'';

		$sql = "UPDATE ".$table." SET ".implode(', ', $valstr);

		$sql .= ($where != '' AND count($where) >=1) ? " WHERE ".implode(" ", $where) : '';

		$sql .= $orderby.$limit;

		return $sql;
	}


	
	function _truncate($table)
	{
		return $this->_delete($table);
	}

	
	function _delete($table, $where = array(), $like = array(), $limit = FALSE)
	{
		$conditions = '';

		if (count($where) > 0 OR count($like) > 0)
		{
			$conditions = "\nWHERE ";
			$conditions .= implode("\n", $this->ar_where);

			if (count($where) > 0 && count($like) > 0)
			{
				$conditions .= " AND ";
			}
			$conditions .= implode("\n", $like);
		}

		$limit = ( ! $limit) ? '' : ' LIMIT '.$limit;

		return "DELETE FROM ".$table.$conditions.$limit;
	}

	
	function _limit($sql, $limit, $offset)
	{
		
		return $sql;
	}

	
	function _close($conn_id)
	{
		@odbc_close($conn_id);
	}


}

