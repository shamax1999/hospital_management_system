<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_mssql_driver extends CI_DB {

	var $dbdriver = 'mssql';

	
	var $_escape_char = '';

	
	var $_like_escape_str = " ESCAPE '%s' ";
	var $_like_escape_chr = '!';

	
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' ASC'; // not currently supported

	
	function db_connect()
	{
		if ($this->port != '')
		{
			$this->hostname .= ','.$this->port;
		}

		return @mssql_connect($this->hostname, $this->username, $this->password);
	}

	
	function db_pconnect()
	{
		if ($this->port != '')
		{
			$this->hostname .= ','.$this->port;
		}

		return @mssql_pconnect($this->hostname, $this->username, $this->password);
	}

	function reconnect()
	{
		
	}

	
	function db_select()
	{
		
		return @mssql_select_db('['.$this->database.']', $this->conn_id);
	}

	
	function db_set_charset($charset, $collation)
	{
		
		return TRUE;
	}

	
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		return @mssql_query($sql, $this->conn_id);
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

		$this->simple_query('BEGIN TRAN');
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

		$this->simple_query('COMMIT TRAN');
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

		$this->simple_query('ROLLBACK TRAN');
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

		
		$str = str_replace("'", "''", remove_invisible_characters($str));

		
		if ($like === TRUE)
		{
			$str = str_replace(
				array($this->_like_escape_chr, '%', '_'),
				array($this->_like_escape_chr.$this->_like_escape_chr, $this->_like_escape_chr.'%', $this->_like_escape_chr.'_'),
				$str
			);
		}

		return $str;
	}

	function affected_rows()
	{
		return @mssql_rows_affected($this->conn_id);
	}

	
	function insert_id()
	{
		$ver = self::_parse_major_version($this->version());
		$sql = ($ver >= 8 ? "SELECT SCOPE_IDENTITY() AS last_id" : "SELECT @@IDENTITY AS last_id");
		$query = $this->query($sql);
		$row = $query->row();
		return $row->last_id;
	}

	function _parse_major_version($version)
	{
		preg_match('/([0-9]+)\.([0-9]+)\.([0-9]+)/', $version, $ver_info);
		return $ver_info[1]; // return the major version b/c that's all we're interested in.
	}

	
	function _version()
	{
		return "SELECT @@VERSION AS ver";
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
		$sql = "SELECT name FROM sysobjects WHERE type = 'U' ORDER BY name";

		
		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			
			return FALSE; // not currently supported
		}

		return $sql;
	}

	
	function _list_columns($table = '')
	{
		return "SELECT * FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = '".$table."'";
	}

	
	function _field_data($table)
	{
		return "SELECT TOP 1 * FROM ".$table;
	}

	
	function _error_message()
	{
		return mssql_get_last_message();
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
		$i = $limit + $offset;

		return preg_replace('/(^\SELECT (DISTINCT)?)/i','\\1 TOP '.$i.' ', $sql);
	}

	
	function _close($conn_id)
	{
		@mssql_close($conn_id);
	}

}

