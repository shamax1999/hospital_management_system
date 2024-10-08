<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_postgre_result extends CI_DB_result {

	
	function num_rows()
	{
		return @pg_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @pg_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		$field_names = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$field_names[] = pg_field_name($this->result_id, $i);
		}

		return $field_names;
	}

	
	function field_data()
	{
		$retval = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$F				= new stdClass();
			$F->name		= pg_field_name($this->result_id, $i);
			$F->type		= pg_field_type($this->result_id, $i);
			$F->max_length	= pg_field_size($this->result_id, $i);
			$F->primary_key = 0;
			$F->default		= '';

			$retval[] = $F;
		}

		return $retval;
	}

	
	function free_result()
	{
		if (is_resource($this->result_id))
		{
			pg_free_result($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return pg_result_seek($this->result_id, $n);
	}

	
	function _fetch_assoc()
	{
		return pg_fetch_assoc($this->result_id);
	}

	
	function _fetch_object()
	{
		return pg_fetch_object($this->result_id);
	}

}

