<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_oci8_result extends CI_DB_result {

	public $stmt_id;
	public $curs_id;
	public $limit_used;

	
	public function num_rows()
	{
		if ($this->num_rows === 0 && count($this->result_array()) > 0)
		{
			$this->num_rows = count($this->result_array());
			@oci_execute($this->stmt_id);

			if ($this->curs_id)
			{
				@oci_execute($this->curs_id);
			}
		}

		return $this->num_rows;
	}

	
	public function num_fields()
	{
		$count = @oci_num_fields($this->stmt_id);

		
		if ($this->limit_used)
		{
			$count = $count - 1;
		}

		return $count;
	}

	
	public function list_fields()
	{
		$field_names = array();
		for ($c = 1, $fieldCount = $this->num_fields(); $c <= $fieldCount; $c++)
		{
			$field_names[] = oci_field_name($this->stmt_id, $c);
		}
		return $field_names;
	}

	
	public function field_data()
	{
		$retval = array();
		for ($c = 1, $fieldCount = $this->num_fields(); $c <= $fieldCount; $c++)
		{
			$F			= new stdClass();
			$F->name		= oci_field_name($this->stmt_id, $c);
			$F->type		= oci_field_type($this->stmt_id, $c);
			$F->max_length		= oci_field_size($this->stmt_id, $c);

			$retval[] = $F;
		}

		return $retval;
	}

	
	public function free_result()
	{
		if (is_resource($this->result_id))
		{
			oci_free_statement($this->result_id);
			$this->result_id = FALSE;
		}
	}

	
	protected function _fetch_assoc()
	{
		$id = ($this->curs_id) ? $this->curs_id : $this->stmt_id;
		return oci_fetch_assoc($id);
	}

	
	protected function _fetch_object()
	{
		$id = ($this->curs_id) ? $this->curs_id : $this->stmt_id;
		return @oci_fetch_object($id);
	}

	
	public function result_array()
	{
		if (count($this->result_array) > 0)
		{
			return $this->result_array;
		}

		$row = NULL;
		while ($row = $this->_fetch_assoc())
		{
			$this->result_array[] = $row;
		}

		return $this->result_array;
	}

	
	protected function _data_seek($n = 0)
	{
		return FALSE; 
	}

}

