<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_sqlsrv_result extends CI_DB_result {

	function num_rows()
	{
		return @sqlsrv_num_rows($this->result_id);
	}

	function num_fields()
	{
		return @sqlsrv_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		$field_names = array();
		foreach(sqlsrv_field_metadata($this->result_id) as $offset => $field)
		{
			$field_names[] = $field['Name'];
		}
		
		return $field_names;
	}

	function field_data()
	{
		$retval = array();
		foreach(sqlsrv_field_metadata($this->result_id) as $offset => $field)
		{
			$F 				= new stdClass();
			$F->name 		= $field['Name'];
			$F->type 		= $field['Type'];
			$F->max_length	= $field['Size'];
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
			sqlsrv_free_stmt($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		
	}

	
	function _fetch_assoc()
	{
		return sqlsrv_fetch_array($this->result_id, SQLSRV_FETCH_ASSOC);
	}

	
	function _fetch_object()
	{
		return sqlsrv_fetch_object($this->result_id);
	}

}

