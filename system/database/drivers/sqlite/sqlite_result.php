<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_sqlite_result extends CI_DB_result {

	function num_rows()
	{
		return @sqlite_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @sqlite_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		$field_names = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$field_names[] = sqlite_field_name($this->result_id, $i);
		}

		return $field_names;
	}

	function field_data()
	{
		$retval = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$F				= new stdClass();
			$F->name		= sqlite_field_name($this->result_id, $i);
			$F->type		= 'varchar';
			$F->max_length	= 0;
			$F->primary_key = 0;
			$F->default		= '';

			$retval[] = $F;
		}

		return $retval;
	}

	
	function free_result()
	{
		
	}

	
	function _data_seek($n = 0)
	{
		return sqlite_seek($this->result_id, $n);
	}

	
	function _fetch_assoc()
	{
		return sqlite_fetch_array($this->result_id);
	}

	
	function _fetch_object()
	{
		if (function_exists('sqlite_fetch_object'))
		{
			return sqlite_fetch_object($this->result_id);
		}
		else
		{
			$arr = sqlite_fetch_array($this->result_id, SQLITE_ASSOC);
			if (is_array($arr))
			{
				$obj = (object) $arr;
				return $obj;
			} else {
				return NULL;
			}
		}
	}

}
