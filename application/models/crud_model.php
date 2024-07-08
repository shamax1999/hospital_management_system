<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
	function get_type_name_by_id($type,$type_id='',$field='name')
	{
		return	$this->db->get_where($type,array($type.'_id'=>$type_id))->row()->$field;	
	}
	
	
	function create_log($data)
	{
		$data['timestamp']	=	strtotime(date('Y-m-d').' '.date('H:i:s'));
		$data['ip']			=	$_SERVER["REMOTE_ADDR"];
		$location 			=	new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/'.$_SERVER["REMOTE_ADDR"]));
		$data['location']	=	$location->City.' , '.$location->CountryName;
		$this->db->insert('log' , $data);
	}
	function get_system_settings()
	{
		$query	=	$this->db->get('settings' );
		return $query->result_array();
	}
	
		
	
	
	function create_backup($type)
	{
		$this->load->dbutil();
		
		
		$options = array(
                'format'      => 'txt',             
                'add_drop'    => TRUE,              
                'add_insert'  => TRUE,              
                'newline'     => "\n"               
              );
		
		 
		if($type == 'all')
		{
			$tables = array('');
			$file_name	=	'system_backup';
		}
		else 
		{
			$tables = array('tables'	=>	array($type));
			$file_name	=	'backup_'.$type;
		}

		$backup =& $this->dbutil->backup(array_merge($options , $tables)); 


		$this->load->helper('download');
		force_download($file_name.'.sql', $backup);
	}
	
	
	
	function restore_backup()
	{
		move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
		$this->load->dbutil();
		
		
		$prefs = array(
            'filepath'						=> 'uploads/backup.sql',
			'delete_after_upload'			=> TRUE,
			'delimiter'						=> ';'
        );
		$restore =& $this->dbutil->restore($prefs); 
		unlink($prefs['filepath']);
	}
	
	
	function truncate($type)
	{
		if($type == 'all')
		{
			$this->db->truncate('student');
			$this->db->truncate('mark');
			$this->db->truncate('teacher');
			$this->db->truncate('subject');
			$this->db->truncate('class');
			$this->db->truncate('exam');
			$this->db->truncate('grade');
		}
		else
		{	
			$this->db->truncate($type);
		}
	}
	
	
	
	function get_image_url($type = '' , $id = '')
	{
		if(file_exists('uploads/'.$type.'_image/'.$id.'.jpg'))
			$image_url	=	base_url().'uploads/'.$type.'_image/'.$id.'.jpg';
		else
			$image_url	=	base_url().'uploads/user.jpg';
			
		return $image_url;
	}
	
	
}

