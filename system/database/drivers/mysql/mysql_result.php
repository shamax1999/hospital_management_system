<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class CI_DB_mysql_result extends CI_DB_result {

	
	function num_rows()
	{
		return @mysql_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @mysql_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		$field_names = array();
		while ($field = mysql_fetch_field($this->result_id))
		{
			$field_names[] = $field->name;
		}

		return $field_names;
	}

	
	function field_data()
	{
		$retval = array();
		while ($field = mysql_fetch_object($this->result_id))
		{
			preg_match('/([a-zA-Z]+)(\(\d+\))?/', $field->Type, $matches);

			$type = (array_key_exists(1, $matches)) ? $matches[1] : NULL;
			$length = (array_key_exists(2, $matches)) ? preg_replace('/[^\d]/', '', $matches[2]) : NULL;

			$F				= new stdClass();
			$F->name		= $field->Field;
			$F->type		= $type;
			$F->default		= $field->Default;
			$F->max_length	= $length;
			$F->primary_key = ( $field->Key == 'PRI' ? 1 : 0 );

			$retval[] = $F;
		}

		return $retval;
	}

	
	function free_result()
	{
		if (is_resource($this->result_id))
		{
			mysql_free_result($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return mysql_data_seek($this->result_id, $n);
	}

	
	function _fetch_assoc()
	{
		return mysql_fetch_assoc($this->result_id);
	}

	
	function _fetch_object()
	{
		return mysql_fetch_object($this->result_id);
	}

}
