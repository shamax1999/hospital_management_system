<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_sqlite_driver extends CI_DB {

	var $dbdriver = 'sqlite';

	
	var $_escape_char = '';

	
	var $_like_escape_str = " ESCAPE '%s' ";
	var $_like_escape_chr = '!';

	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' Random()'; 

	
	function db_connect()
	{
		if ( ! $conn_id = @sqlite_open($this->database, FILE_WRITE_MODE, $error))
		{
			log_message('error', $error);

			if ($this->db_debug)
			{
				$this->display_error($error, '', TRUE);
			}

			return FALSE;
		}

		return $conn_id;
	}

	
	function db_pconnect()
	{
		if ( ! $conn_id = @sqlite_popen($this->database, FILE_WRITE_MODE, $error))
		{
			log_message('error', $error);

			if ($this->db_debug)
			{
				$this->display_error($error, '', TRUE);
			}

			return FALSE;
		}

		return $conn_id;
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
		return sqlite_libversion();
	}

	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		return @sqlite_query($this->conn_id, $sql);
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

		$this->simple_query('BEGIN TRANSACTION');
		return TRUE;
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

		$this->simple_query('COMMIT');
		return TRUE;
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

		$this->simple_query('ROLLBACK');
		return TRUE;
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

		$str = sqlite_escape_string($str);

		
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
		return sqlite_changes($this->conn_id);
	}

	
	function insert_id()
	{
		return @sqlite_last_insert_rowid($this->conn_id);
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
		$sql = "SELECT name from sqlite_master WHERE type='table'";

		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			$sql .= " AND 'name' LIKE '".$this->escape_like_str($this->dbprefix)."%' ".sprintf($this->_like_escape_str, $this->_like_escape_chr);
		}
		return $sql;
	}

	
	function _list_columns($table = '')
	{
		
		return FALSE;
	}

	function _field_data($table)
	{
		return "SELECT * FROM ".$table." LIMIT 1";
	}

	
	function _error_message()
	{
		return sqlite_error_string(sqlite_last_error($this->conn_id));
	}

	
	function _error_number()
	{
		return sqlite_last_error($this->conn_id);
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
		if ($offset == 0)
		{
			$offset = '';
		}
		else
		{
			$offset .= ", ";
		}

		return $sql."LIMIT ".$offset.$limit;
	}

	
	function _close($conn_id)
	{
		@sqlite_close($conn_id);
	}


}
