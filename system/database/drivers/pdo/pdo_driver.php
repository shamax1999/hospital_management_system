<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_pdo_driver extends CI_DB {

	var $dbdriver = 'pdo';

	
	var $_escape_char = '';
	var $_like_escape_str;
	var $_like_escape_chr;
	

	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword;
	
	var $options = array();

	function __construct($params)
	{
		parent::__construct($params);

		
		if (strpos($this->hostname, 'mysql') !== FALSE)
		{
			$this->_like_escape_str = '';
			$this->_like_escape_chr = '';

			
			if(is_php('5.3.6'))
			{
				$this->hostname .= ";charset={$this->char_set}";
			}

			
			$this->options['PDO::MYSQL_ATTR_INIT_COMMAND'] = "SET NAMES {$this->char_set}";
		}
		elseif (strpos($this->hostname, 'odbc') !== FALSE)
		{
			$this->_like_escape_str = " {escape '%s'} ";
			$this->_like_escape_chr = '!';
		}
		else
		{
			$this->_like_escape_str = " ESCAPE '%s' ";
			$this->_like_escape_chr = '!';
		}

		empty($this->database) OR $this->hostname .= ';dbname='.$this->database;

		$this->trans_enabled = FALSE;

		$this->_random_keyword = ' RND('.time().')'; 
	}

	
	function db_connect()
	{
		$this->options['PDO::ATTR_ERRMODE'] = PDO::ERRMODE_SILENT;

		return new PDO($this->hostname, $this->username, $this->password, $this->options);
	}

	
	function db_pconnect()
	{
		$this->options['PDO::ATTR_ERRMODE'] = PDO::ERRMODE_SILENT;
		$this->options['PDO::ATTR_PERSISTENT'] = TRUE;
	
		return new PDO($this->hostname, $this->username, $this->password, $this->options);
	}

	
	function reconnect()
	{
		if ($this->db->db_debug)
		{
			return $this->db->display_error('db_unsuported_feature');
		}
		return FALSE;
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
		return $this->conn_id->getAttribute(PDO::ATTR_CLIENT_VERSION);
	}

	
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
		$result_id = $this->conn_id->prepare($sql);
		$result_id->execute();
		
		if (is_object($result_id))
		{
			if (is_numeric(stripos($sql, 'SELECT')))
			{
				$this->affect_rows = count($result_id->fetchAll());
				$result_id->execute();
			}
			else
			{
				$this->affect_rows = $result_id->rowCount();
			}
		}
		else
		{
			$this->affect_rows = 0;
		}
		
		return $result_id;
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

		
		$this->_trans_failure = (bool) ($test_mode === TRUE);

		return $this->conn_id->beginTransaction();
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

		$ret = $this->conn->commit();
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

		$ret = $this->conn_id->rollBack();
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
		
		
		$str = $this->conn_id->quote($str);
		
		
		if (strpos($str, "'") === 0)
		{
			$str = substr($str, 1, -1);
		}
		
		
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
		return $this->affect_rows;
	}

	
	function insert_id($name=NULL)
	{
		
		if (strpos($this->hostname, 'pgsql') !== FALSE)
		{
			$v = $this->_version();

			$table	= func_num_args() > 0 ? func_get_arg(0) : NULL;

			if ($table == NULL && $v >= '8.1')
			{
				$sql='SELECT LASTVAL() as ins_id';
			}
			$query = $this->query($sql);
			$row = $query->row();
			return $row->ins_id;
		}
		else
		{
			return $this->conn_id->lastInsertId($name);
		}
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
		$error_array = $this->conn_id->errorInfo();
		return $error_array[2];
	}

	
	function _error_number()
	{
		return $this->conn_id->errorCode();
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

		return (count($tables) == 1) ? $tables[0] : '('.implode(', ', $tables).')';
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
	
	
	function _update_batch($table, $values, $index, $where = NULL)
	{
		$ids = array();
		$where = ($where != '' AND count($where) >=1) ? implode(" ", $where).' AND ' : '';

		foreach ($values as $key => $val)
		{
			$ids[] = $val[$index];

			foreach (array_keys($val) as $field)
			{
				if ($field != $index)
				{
					$final[$field][] =  'WHEN '.$index.' = '.$val[$index].' THEN '.$val[$field];
				}
			}
		}

		$sql = "UPDATE ".$table." SET ";
		$cases = '';

		foreach ($final as $k => $v)
		{
			$cases .= $k.' = CASE '."\n";
			foreach ($v as $row)
			{
				$cases .= $row."\n";
			}

			$cases .= 'ELSE '.$k.' END, ';
		}

		$sql .= substr($cases, 0, -2);

		$sql .= ' WHERE '.$where.$index.' IN ('.implode(',', $ids).')';

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
		if (strpos($this->hostname, 'cubrid') !== FALSE || strpos($this->hostname, 'sqlite') !== FALSE)
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
		else
		{
			$sql .= "LIMIT ".$limit;

			if ($offset > 0)
			{
				$sql .= " OFFSET ".$offset;
			}
			
			return $sql;
		}
	}

	
	function _close($conn_id)
	{
		$this->conn_id = null;
	}


}
