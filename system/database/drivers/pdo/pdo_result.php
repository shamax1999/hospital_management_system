<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_pdo_result extends CI_DB_result {

	public $num_rows;

	
	public function num_rows()
	{
		if (is_int($this->num_rows))
		{
			return $this->num_rows;
		}
		elseif (($this->num_rows = $this->result_id->rowCount()) > 0)
		{
			return $this->num_rows;
		}

		$this->num_rows = count($this->result_id->fetchAll());
		$this->result_id->execute();
		return $this->num_rows;
	}

	
	function num_fields()
	{
		return $this->result_id->columnCount();
	}

	
	function list_fields()
	{
		if ($this->db->db_debug)
		{
			return $this->db->display_error('db_unsuported_feature');
		}
		return FALSE;
	}

	
	function field_data()
	{
		$data = array();
	
		try
		{
			for($i = 0; $i < $this->num_fields(); $i++)
			{
				$data[] = $this->result_id->getColumnMeta($i);
			}
			
			return $data;
		}
		catch (Exception $e)
		{
			if ($this->db->db_debug)
			{
				return $this->db->display_error('db_unsuported_feature');
			}
			return FALSE;
		}
	}

	
	function free_result()
	{
		if (is_object($this->result_id))
		{
			$this->result_id = FALSE;
		}
	}

	
	function _data_seek($n = 0)
	{
		return FALSE;
	}

	
	function _fetch_assoc()
	{
		return $this->result_id->fetch(PDO::FETCH_ASSOC);
	}

	
	function _fetch_object()
	{	
		return $this->result_id->fetchObject();
	}

}

