<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_mssql_result extends CI_DB_result {

	
	function num_rows()
	{
		return @mssql_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @mssql_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		$field_names = array();
		while ($field = mssql_fetch_field($this->result_id))
		{
			$field_names[] = $field->name;
		}

		return $field_names;
	}

	
	function field_data()
	{
		$retval = array();
		while ($field = mssql_fetch_field($this->result_id))
		{
			$F				= new stdClass();
			$F->name		= $field->name;
			$F->type		= $field->type;
			$F->max_length	= $field->max_length;
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
			mssql_free_result($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return mssql_data_seek($this->result_id, $n);
	}

	
	function _fetch_assoc()
	{
		return mssql_fetch_assoc($this->result_id);
	}

	
	function _fetch_object()
	{
		return mssql_fetch_object($this->result_id);
	}

}

