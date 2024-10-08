<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_odbc_result extends CI_DB_result {

	
	function num_rows()
	{
		return @odbc_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @odbc_num_fields($this->result_id);
	}


	function list_fields()
	{
		$field_names = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$field_names[]	= odbc_field_name($this->result_id, $i);
		}

		return $field_names;
	}

	
	function field_data()
	{
		$retval = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$F				= new stdClass();
			$F->name		= odbc_field_name($this->result_id, $i);
			$F->type		= odbc_field_type($this->result_id, $i);
			$F->max_length	= odbc_field_len($this->result_id, $i);
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
			odbc_free_result($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return FALSE;
	}

	
	function _fetch_assoc()
	{
		if (function_exists('odbc_fetch_object'))
		{
			return odbc_fetch_array($this->result_id);
		}
		else
		{
			return $this->_odbc_fetch_array($this->result_id);
		}
	}

	
	function _fetch_object()
	{
		if (function_exists('odbc_fetch_object'))
		{
			return odbc_fetch_object($this->result_id);
		}
		else
		{
			return $this->_odbc_fetch_object($this->result_id);
		}
	}


	
	function _odbc_fetch_object(& $odbc_result) {
		$rs = array();
		$rs_obj = FALSE;
		if (odbc_fetch_into($odbc_result, $rs)) {
			foreach ($rs as $k=>$v) {
				$field_name= odbc_field_name($odbc_result, $k+1);
				$rs_obj->$field_name = $v;
			}
		}
		return $rs_obj;
	}

	function _odbc_fetch_array(& $odbc_result) {
		$rs = array();
		$rs_assoc = FALSE;
		if (odbc_fetch_into($odbc_result, $rs)) {
			$rs_assoc=array();
			foreach ($rs as $k=>$v) {
				$field_name= odbc_field_name($odbc_result, $k+1);
				$rs_assoc[$field_name] = $v;
			}
		}
		return $rs_assoc;
	}

}
