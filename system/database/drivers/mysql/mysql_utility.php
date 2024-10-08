<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_mysql_utility extends CI_DB_utility {

	function _list_databases()
	{
		return "SHOW DATABASES";
	}

	function _optimize_table($table)
	{
		return "OPTIMIZE TABLE ".$this->db->_escape_identifiers($table);
	}

	
	function _repair_table($table)
	{
		return "REPAIR TABLE ".$this->db->_escape_identifiers($table);
	}

	
	function _backup($params = array())
	{
		if (count($params) == 0)
		{
			return FALSE;
		}

		
		extract($params);

		
		$output = '';
		foreach ((array)$tables as $table)
		{
			
			if (in_array($table, (array)$ignore, TRUE))
			{
				continue;
			}

			
			$query = $this->db->query("SHOW CREATE TABLE `".$this->db->database.'`.`'.$table.'`');

			
			if ($query === FALSE)
			{
				continue;
			}

			
			$output .= '#'.$newline.'# TABLE STRUCTURE FOR: '.$table.$newline.'#'.$newline.$newline;

			if ($add_drop == TRUE)
			{
				$output .= 'DROP TABLE IF EXISTS '.$table.';'.$newline.$newline;
			}

			$i = 0;
			$result = $query->result_array();
			foreach ($result[0] as $val)
			{
				if ($i++ % 2)
				{
					$output .= $val.';'.$newline.$newline;
				}
			}

			
			if ($add_insert == FALSE)
			{
				continue;
			}

			
			$query = $this->db->query("SELECT * FROM $table");

			if ($query->num_rows() == 0)
			{
				continue;
			}

			$i = 0;
			$field_str = '';
			$is_int = array();
			while ($field = mysql_fetch_field($query->result_id))
			{
				
				$is_int[$i] = (in_array(
										strtolower(mysql_field_type($query->result_id, $i)),
										array('tinyint', 'smallint', 'mediumint', 'int', 'bigint'), //, 'timestamp'),
										TRUE)
										) ? TRUE : FALSE;

				$field_str .= '`'.$field->name.'`, ';
				$i++;
			}

			
			$field_str = preg_replace( "/, $/" , "" , $field_str);


			
			foreach ($query->result_array() as $row)
			{
				$val_str = '';

				$i = 0;
				foreach ($row as $v)
				{
					
					if ($v === NULL)
					{
						$val_str .= 'NULL';
					}
					else
					{
						
						if ($is_int[$i] == FALSE)
						{
							$val_str .= $this->db->escape($v);
						}
						else
						{
							$val_str .= $v;
						}
					}

					
					$val_str .= ', ';
					$i++;
				}

				
				$val_str = preg_replace( "/, $/" , "" , $val_str);

				
				$output .= 'INSERT INTO '.$table.' ('.$field_str.') VALUES ('.$val_str.');'.$newline;
			}

			$output .= $newline.$newline;
		}

		return $output;
	}
}

