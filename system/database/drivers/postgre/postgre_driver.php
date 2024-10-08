<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_postgre_driver extends CI_DB {

	var $dbdriver = 'postgre';

	var $_escape_char = '"';

	
	var $_like_escape_str = " ESCAPE '%s' ";
	var $_like_escape_chr = '!';

	
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' RANDOM()'; 

	
	function _connect_string()
	{
		$components = array(
								'hostname'	=> 'host',
								'port'		=> 'port',
								'database'	=> 'dbname',
								'username'	=> 'user',
								'password'	=> 'password'
							);

		$connect_string = "";
		foreach ($components as $key => $val)
		{
			if (isset($this->$key) && $this->$key != '')
			{
				$connect_string .= " $val=".$this->$key;
			}
		}
		return trim($connect_string);
	}

	
	function db_connect()
	{
		return @pg_connect($this->_connect_string());
	}

	
	function db_pconnect()
	{
		return @pg_pconnect($this->_connect_string());
	}

	
	function reconnect()
	{
		if (pg_ping($this->conn_id) === FALSE)
		{
			$this->conn_id = FALSE;
		}
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
		return @pg_query($this->conn_id, $sql);
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

		return @pg_exec($this->conn_id, "begin");
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

		return @pg_exec($this->conn_id, "commit");
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

		return @pg_exec($this->conn_id, "rollback");
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

		$str = pg_escape_string($str);

		
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
		return @pg_affected_rows($this->result_id);
	}

	
	function insert_id()
	{
		$v = $this->_version();
		$v = $v['server'];

		$table	= func_num_args() > 0 ? func_get_arg(0) : NULL;
		$column	= func_num_args() > 1 ? func_get_arg(1) : NULL;

		if ($table == NULL && $v >= '8.1')
		{
			$sql='SELECT LASTVAL() as ins_id';
		}
		elseif ($table != NULL && $column != NULL && $v >= '8.0')
		{
			$sql = sprintf("SELECT pg_get_serial_sequence('%s','%s') as seq", $table, $column);
			$query = $this->query($sql);
			$row = $query->row();
			$sql = sprintf("SELECT CURRVAL('%s') as ins_id", $row->seq);
		}
		elseif ($table != NULL)
		{
			
			$sql = sprintf("SELECT CURRVAL('%s') as ins_id", $table);
		}
		else
		{
			return pg_last_oid($this->result_id);
		}
		$query = $this->query($sql);
		$row = $query->row();
		return $row->ins_id;
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
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";

		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			$sql .= " AND table_name LIKE '".$this->escape_like_str($this->dbprefix)."%' ".sprintf($this->_like_escape_str, $this->_like_escape_chr);
		}

		return $sql;
	}

	function _list_columns($table = '')
	{
		return "SELECT column_name FROM information_schema.columns WHERE table_name ='".$table."'";
	}

	
	function _field_data($table)
	{
		return "SELECT * FROM ".$table." LIMIT 1";
	}

	
	function _error_message()
	{
		return pg_last_error($this->conn_id);
	}

	
	function _error_number()
	{
		return '';
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

		return implode(', ', $tables);
	}

	
	function _insert($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}

	function _insert_batch($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values);
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
		return "TRUNCATE ".$table;
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
		$sql .= "LIMIT ".$limit;

		if ($offset > 0)
		{
			$sql .= " OFFSET ".$offset;
		}

		return $sql;
	}

	
	function _close($conn_id)
	{
		@pg_close($conn_id);
	}


}
