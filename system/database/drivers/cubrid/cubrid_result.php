<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_cubrid_result extends CI_DB_result {

	
	function num_rows()
	{
		return @cubrid_num_rows($this->result_id);
	}

	
	function num_fields()
	{
		return @cubrid_num_fields($this->result_id);
	}

	
	function list_fields()
	{
		return cubrid_column_names($this->result_id);
	}

	
	function field_data()
	{
		$retval = array();

		$tablePrimaryKeys = array();

		while ($field = cubrid_fetch_field($this->result_id))
		{
			$F				= new stdClass();
			$F->name		= $field->name;
			$F->type		= $field->type;
			$F->default		= $field->def;
			$F->max_length	= $field->max_length;

			
			$res = cubrid_query($this->conn_id,
				"SELECT COUNT(*) FROM db_index WHERE class_name = '" . $field->table .
				"' AND is_primary_key = 'YES' AND index_name = 'pk_" .
				$field->table . "_" . $field->name . "'"
			);

			if ($res)
			{
				$row = cubrid_fetch_array($res, CUBRID_NUM);
				$F->primary_key = ($row[0] > 0 ? 1 : null);
			}
			else
			{
				$F->primary_key = null;
			}

			if (is_resource($res))
			{
				cubrid_close_request($res);
				$this->result_id = FALSE;
			}

			$retval[] = $F;
		}

		return $retval;
	}

	
	function free_result()
	{
		if(is_resource($this->result_id) ||
			get_resource_type($this->result_id) == "Unknown" &&
			preg_match('/Resource id #/', strval($this->result_id)))
		{
			cubrid_close_request($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return cubrid_data_seek($this->result_id, $n);
	}

	
	function _fetch_assoc()
	{
		return cubrid_fetch_assoc($this->result_id);
	}

	
	function _fetch_object()
	{
		return cubrid_fetch_object($this->result_id);
	}

}

