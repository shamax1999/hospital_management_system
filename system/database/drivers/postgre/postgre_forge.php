<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_postgre_forge extends CI_DB_forge {

	
	function _create_database($name)
	{
		return "CREATE DATABASE ".$name;
	}


	function _drop_database($name)
	{
		return "DROP DATABASE ".$name;
	}

	
	function _create_table($table, $fields, $primary_keys, $keys, $if_not_exists)
	{
		$sql = 'CREATE TABLE ';

		if ($if_not_exists === TRUE)
		{
			if ($this->db->table_exists($table))
			{
				return "SELECT * FROM $table"; 
			}
		}

		$sql .= $this->db->_escape_identifiers($table)." (";
		$current_field_count = 0;

		foreach ($fields as $field=>$attributes)
		{
			
			if (is_numeric($field))
			{
				$sql .= "\n\t$attributes";
			}
			else
			{
				$attributes = array_change_key_case($attributes, CASE_UPPER);

				$sql .= "\n\t".$this->db->_protect_identifiers($field);

				$is_unsigned = (array_key_exists('UNSIGNED', $attributes) && $attributes['UNSIGNED'] === TRUE);

				
				switch (strtoupper($attributes['TYPE']))
				{
					case 'TINYINT':
						$attributes['TYPE'] = 'SMALLINT';
						break;
					case 'SMALLINT':
						$attributes['TYPE'] = ($is_unsigned) ? 'INTEGER' : 'SMALLINT';
						break;
					case 'MEDIUMINT':
						$attributes['TYPE'] = 'INTEGER';
						break;
					case 'INT':
						$attributes['TYPE'] = ($is_unsigned) ? 'BIGINT' : 'INTEGER';
						break;
					case 'BIGINT':
						$attributes['TYPE'] = ($is_unsigned) ? 'NUMERIC' : 'BIGINT';
						break;
					case 'DOUBLE':
						$attributes['TYPE'] = 'DOUBLE PRECISION';
						break;
					case 'DATETIME':
						$attributes['TYPE'] = 'TIMESTAMP';
						break;
					case 'LONGTEXT':
						$attributes['TYPE'] = 'TEXT';
						break;
					case 'BLOB':
						$attributes['TYPE'] = 'BYTEA';
						break;
				}

				
				if (in_array($field, $primary_keys) && array_key_exists('AUTO_INCREMENT', $attributes) 
					&& $attributes['AUTO_INCREMENT'] === TRUE)
				{
					$sql .= ' SERIAL';
				}
				else
				{
					$sql .=  ' '.$attributes['TYPE'];
				}

				
				if (array_key_exists('CONSTRAINT', $attributes) && strpos($attributes['TYPE'], 'INT') === false)
				{
					$sql .= '('.$attributes['CONSTRAINT'].')';
				}

				if (array_key_exists('DEFAULT', $attributes))
				{
					$sql .= ' DEFAULT \''.$attributes['DEFAULT'].'\'';
				}

				if (array_key_exists('NULL', $attributes) && $attributes['NULL'] === TRUE)
				{
					$sql .= ' NULL';
				}
				else
				{
					$sql .= ' NOT NULL';
				}

				
				if (array_key_exists('UNIQUE', $attributes) && $attributes['UNIQUE'] === TRUE)
				{
					$sql .= ' UNIQUE';
				}
			}

			
			if (++$current_field_count < count($fields))
			{
				$sql .= ',';
			}
		}

		if (count($primary_keys) > 0)
		{
			
			foreach ($primary_keys as $index => $key)
			{
				$primary_keys[$index] = $this->db->_protect_identifiers($key);
			}

			$sql .= ",\n\tPRIMARY KEY (" . implode(', ', $primary_keys) . ")";
		}

		$sql .= "\n);";

		if (is_array($keys) && count($keys) > 0)
		{
			foreach ($keys as $key)
			{
				if (is_array($key))
				{
					$key = $this->db->_protect_identifiers($key);
				}
				else
				{
					$key = array($this->db->_protect_identifiers($key));
				}

				foreach ($key as $field)
				{
					$sql .= "CREATE INDEX " . $table . "_" . str_replace(array('"', "'"), '', $field) . "_index ON $table ($field); ";
				}
			}
		}

		return $sql;
	}

	
	function _drop_table($table)
	{
		return "DROP TABLE IF EXISTS ".$this->db->_escape_identifiers($table)." CASCADE";
	}

	
	function _alter_table($alter_type, $table, $column_name, $column_definition = '', $default_value = '', $null = '', $after_field = '')
	{
		$sql = 'ALTER TABLE '.$this->db->_protect_identifiers($table)." $alter_type ".$this->db->_protect_identifiers($column_name);

		
		if ($alter_type == 'DROP')
		{
			return $sql;
		}

		$sql .= " $column_definition";

		if ($default_value != '')
		{
			$sql .= " DEFAULT \"$default_value\"";
		}

		if ($null === NULL)
		{
			$sql .= ' NULL';
		}
		else
		{
			$sql .= ' NOT NULL';
		}

		if ($after_field != '')
		{
			$sql .= ' AFTER ' . $this->db->_protect_identifiers($after_field);
		}

		return $sql;

	}

	
	function _rename_table($table_name, $new_table_name)
	{
		$sql = 'ALTER TABLE '.$this->db->_protect_identifiers($table_name)." RENAME TO ".$this->db->_protect_identifiers($new_table_name);
		return $sql;
	}


}
