<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_oci8_driver extends CI_DB {

	var $dbdriver = 'oci8';

	
	var $_escape_char = '"';

	
	var $_like_escape_str = " escape '%s' ";
	var $_like_escape_chr = '!';

	
	var $_count_string = "SELECT COUNT(1) AS ";
	var $_random_keyword = ' ASC'; 

	
	var $_commit = OCI_COMMIT_ON_SUCCESS;

	
	var $stmt_id;
	var $curs_id;

	
	var $limit_used;

	
	public function db_connect()
	{
		return @oci_connect($this->username, $this->password, $this->hostname, $this->char_set);
	}

	public function db_pconnect()
	{
		return @oci_pconnect($this->username, $this->password, $this->hostname, $this->char_set);
	}

	
	public function reconnect()
	{
		
		return;
	}

	
	public function db_select()
	{
		
		return TRUE;
	}

	
	public function db_set_charset($charset, $collation)
	{
		
		return TRUE;
	}

	
	protected function _version()
	{
		return oci_server_version($this->conn_id);
	}

	
	protected function _execute($sql)
	{
		
		$this->stmt_id = FALSE;
		$this->_set_stmt_id($sql);
		oci_set_prefetch($this->stmt_id, 1000);
		return @oci_execute($this->stmt_id, $this->_commit);
	}

	
	private function _set_stmt_id($sql)
	{
		if ( ! is_resource($this->stmt_id))
		{
			$this->stmt_id = oci_parse($this->conn_id, $this->_prep_query($sql));
		}
	}

	private function _prep_query($sql)
	{
		return $sql;
	}

	
	public function get_cursor()
	{
		$this->curs_id = oci_new_cursor($this->conn_id);
		return $this->curs_id;
	}

	
	public function stored_procedure($package, $procedure, $params)
	{
		if ($package == '' OR $procedure == '' OR ! is_array($params))
		{
			if ($this->db_debug)
			{
				log_message('error', 'Invalid query: '.$package.'.'.$procedure);
				return $this->display_error('db_invalid_query');
			}
			return FALSE;
		}

		
		$sql = "begin $package.$procedure(";

		$have_cursor = FALSE;
		foreach ($params as $param)
		{
			$sql .= $param['name'] . ",";

			if (array_key_exists('type', $param) && ($param['type'] === OCI_B_CURSOR))
			{
				$have_cursor = TRUE;
			}
		}
		$sql = trim($sql, ",") . "); end;";

		$this->stmt_id = FALSE;
		$this->_set_stmt_id($sql);
		$this->_bind_params($params);
		$this->query($sql, FALSE, $have_cursor);
	}

	
	private function _bind_params($params)
	{
		if ( ! is_array($params) OR ! is_resource($this->stmt_id))
		{
			return;
		}

		foreach ($params as $param)
		{
			foreach (array('name', 'value', 'type', 'length') as $val)
			{
				if ( ! isset($param[$val]))
				{
					$param[$val] = '';
				}
			}

			oci_bind_by_name($this->stmt_id, $param['name'], $param['value'], $param['length'], $param['type']);
		}
	}

	
	public function trans_begin($test_mode = FALSE)
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

		$this->_commit = OCI_DEFAULT;
		return TRUE;
	}

	
	public function trans_commit()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}

		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}

		$ret = oci_commit($this->conn_id);
		$this->_commit = OCI_COMMIT_ON_SUCCESS;
		return $ret;
	}

	
	public function trans_rollback()
	{
		if ( ! $this->trans_enabled)
		{
			return TRUE;
		}

		
		if ($this->_trans_depth > 0)
		{
			return TRUE;
		}

		$ret = oci_rollback($this->conn_id);
		$this->_commit = OCI_COMMIT_ON_SUCCESS;
		return $ret;
	}

	
	public function escape_str($str, $like = FALSE)
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

	
	public function affected_rows()
	{
		return @oci_num_rows($this->stmt_id);
	}

	
	public function insert_id()
	{
		
		return $this->display_error('db_unsupported_function');
	}

	
	public function count_all($table = '')
	{
		if ($table == '')
		{
			return 0;
		}

		$query = $this->query($this->_count_string . $this->_protect_identifiers('numrows') . " FROM " . $this->_protect_identifiers($table, TRUE, NULL, FALSE));

		if ($query == FALSE)
		{
			return 0;
		}

		$row = $query->row();
		$this->_reset_select();
		return (int) $row->numrows;
	}

	
	protected function _list_tables($prefix_limit = FALSE)
	{
		$sql = "SELECT TABLE_NAME FROM ALL_TABLES";

		if ($prefix_limit !== FALSE AND $this->dbprefix != '')
		{
			$sql .= " WHERE TABLE_NAME LIKE '".$this->escape_like_str($this->dbprefix)."%' ".sprintf($this->_like_escape_str, $this->_like_escape_chr);
		}

		return $sql;
	}

	
	protected function _list_columns($table = '')
	{
		return "SELECT COLUMN_NAME FROM all_tab_columns WHERE table_name = '$table'";
	}

	
	protected function _field_data($table)
	{
		return "SELECT * FROM ".$table." where rownum = 1";
	}

	
	protected function _error_message()
	{
		
		$error = is_resource($this->conn_id) ? oci_error($this->conn_id) : oci_error();
		return $error['message'];
	}

	
	protected function _error_number()
	{
		
		$error = is_resource($this->conn_id) ? oci_error($this->conn_id) : oci_error();
		return $error['code'];
	}

	
	protected function _escape_identifiers($item)
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

	
	protected function _from_tables($tables)
	{
		if ( ! is_array($tables))
		{
			$tables = array($tables);
		}

		return implode(', ', $tables);
	}

	
	protected function _insert($table, $keys, $values)
	{
		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
	}

	
	protected function _insert_batch($table, $keys, $values)
	{
		$keys = implode(', ', $keys);
		$sql = "INSERT ALL\n";

		for ($i = 0, $c = count($values); $i < $c; $i++)
		{
			$sql .= '	INTO ' . $table . ' (' . $keys . ') VALUES ' . $values[$i] . "\n";
		}

		$sql .= 'SELECT * FROM dual';

		return $sql;
	}

	
	protected function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
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

	protected function _truncate($table)
	{
		return "TRUNCATE TABLE ".$table;
	}

	
	protected function _delete($table, $where = array(), $like = array(), $limit = FALSE)
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

	
	protected function _limit($sql, $limit, $offset)
	{
		$limit = $offset + $limit;
		$newsql = "SELECT * FROM (select inner_query.*, rownum rnum FROM ($sql) inner_query WHERE rownum < $limit)";

		if ($offset != 0)
		{
			$newsql .= " WHERE rnum >= $offset";
		}

		
		$this->limit_used = TRUE;

		return $newsql;
	}

	
	protected function _close($conn_id)
	{
		@oci_close($conn_id);
	}


}

