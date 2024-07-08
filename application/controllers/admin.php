<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		//cache control
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	
	
	public function index()
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		if ($this->session->userdata('admin_login') == 1)
			redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
	}
	
	
	function dashboard()     //Admin dashboard
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		$page_data['page_name']  = 'dashboard';
		$page_data['page_title'] = ('Admin Dashboard');
		$this->load->view('index', $page_data);
	}
	
	
	function manage_department($param1 = '', $param2 = '', $param3 = '')    //Departments
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']        = $this->input->post('name');
			$data['description'] = $this->input->post('description');
			$this->db->insert('department', $data);
			$this->session->set_flashdata('flash_message', ('Department Opened'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']        = $this->input->post('name');
			$data['description'] = $this->input->post('description');
			$this->db->where('department_id', $param3);
			$this->db->update('department', $data);
			$this->session->set_flashdata('flash_message', ('Department Updated'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
			
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('department', array(
				'department_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('department_id', $param2);
			$this->db->delete('department');
			$this->session->set_flashdata('flash_message', ('Department Deleted'));
			redirect(base_url() . 'index.php?admin/manage_department', 'refresh');
		}
		$page_data['page_name']   = 'manage_department';
		$page_data['page_title']  = ('Manage Department');
		$page_data['departments'] = $this->db->get('department')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	function manage_doctor($param1 = '', $param2 = '', $param3 = '')    //Manage Doctors
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']          = $this->input->post('name');
			$data['email']         = $this->input->post('email');
			$data['password']      = $this->input->post('password');
			$data['address']       = $this->input->post('address');
			$data['phone']         = $this->input->post('phone');
			$data['department_id'] = $this->input->post('department_id');
			$data['profile']       = $this->input->post('profile');
			$this->db->insert('doctor', $data);
			$this->email_model->account_opening_email('doctor', $data['email']); 
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']          = $this->input->post('name');
			$data['email']         = $this->input->post('email');
			$data['password']      = $this->input->post('password');
			$data['address']       = $this->input->post('address');
			$data['phone']         = $this->input->post('phone');
			$data['department_id'] = $this->input->post('department_id');
			$data['profile']       = $this->input->post('profile');
			
			$this->db->where('doctor_id', $param3);
			$this->db->update('doctor', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('doctor', array(
				'doctor_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('doctor_id', $param2);
			$this->db->delete('doctor');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_doctor', 'refresh');
		}
		$page_data['page_name']  = 'manage_doctor';
		$page_data['page_title'] = ('Manage Doctor');
		$page_data['doctors']    = $this->db->get('doctor')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	
	function manage_patient($param1 = '', $param2 = '', $param3 = '')     //manage patient
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']                      = $this->input->post('name');
			$data['email']                     = $this->input->post('email');
			$data['password']                  = $this->input->post('password');
			$data['address']                   = $this->input->post('address');
			$data['phone']                     = $this->input->post('phone');
			$data['sex']                       = $this->input->post('sex');
			$data['birth_date']                = $this->input->post('birth_date');
			$data['age']                       = $this->input->post('age');
			$data['blood_group']               = $this->input->post('blood_group');
			$data['account_opening_timestamp'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));
			$this->db->insert('patient', $data);
			$this->email_model->account_opening_email('patient', $data['email']); 
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']        = $this->input->post('name');
			$data['email']       = $this->input->post('email');
			$data['password']    = $this->input->post('password');
			$data['address']     = $this->input->post('address');
			$data['phone']       = $this->input->post('phone');
			$data['sex']         = $this->input->post('sex');
			$data['birth_date']  = $this->input->post('birth_date');
			$data['age']         = $this->input->post('age');
			$data['blood_group'] = $this->input->post('blood_group');
			
			$this->db->where('patient_id', $param3);
			$this->db->update('patient', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('patient', array(
				'patient_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('patient_id', $param2);
			$this->db->delete('patient');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_patient', 'refresh');
		}
		$page_data['page_name']  = 'manage_patient';
		$page_data['page_title'] = ('Manage Patient');
		$page_data['patients']   = $this->db->get('patient')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	
	function manage_nurse($param1 = '', $param2 = '', $param3 = '')       //manage nurse
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->insert('nurse', $data);
			$this->email_model->account_opening_email('nurse', $data['email']); 
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->where('nurse_id', $param3);
			$this->db->update('nurse', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('nurse', array(
				'nurse_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('nurse_id', $param2);
			$this->db->delete('nurse');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_nurse', 'refresh');
		}
		$page_data['page_name']  = 'manage_nurse';
		$page_data['page_title'] = ('Manage Nurse');
		$page_data['nurses']     = $this->db->get('nurse')->result_array();
		$this->load->view('index', $page_data);
		
	}
	
	
	function manage_laboratorist($param1 = '', $param2 = '', $param3 = '')    //manage laboratorists
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->insert('laboratorist', $data);
			$this->email_model->account_opening_email('laboratorist', $data['email']); 
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->where('laboratorist_id', $param3);
			$this->db->update('laboratorist', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('laboratorist', array(
				'laboratorist_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('laboratorist_id', $param2);
			$this->db->delete('laboratorist');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			redirect(base_url() . 'index.php?admin/manage_laboratorist', 'refresh');
		}
		$page_data['page_name']     = 'manage_laboratorist';
		$page_data['page_title']    = ('Manage Laboratorist');
		$page_data['laboratorists'] = $this->db->get('laboratorist')->result_array();
		$this->load->view('index', $page_data);
	}
	
	function manage_accountant($param1 = '', $param2 = '', $param3 = '')      //manage accountant
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'create') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->insert('accountant', $data);
			$this->email_model->account_opening_email('accountant', $data['email']); 
			$this->session->set_flashdata('flash_message', ('Account Opened'));
			
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['name']     = $this->input->post('name');
			$data['email']    = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['address']  = $this->input->post('address');
			$data['phone']    = $this->input->post('phone');
			$this->db->where('accountant_id', $param3);
			$this->db->update('accountant', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('accountant', array(
				'accountant_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('accountant_id', $param2);
			$this->db->delete('accountant');
			$this->session->set_flashdata('flash_message', ('Account Deleted'));
			redirect(base_url() . 'index.php?admin/manage_accountant', 'refresh');
		}
		$page_data['page_name']   = 'manage_accountant';
		$page_data['page_title']  = ('Manage Accountant');
		$page_data['accountants'] = $this->db->get('accountant')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	function view_appointment($param1 = '', $param2 = '', $param3 = '')      //view appoinment
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']    = 'view_appointment';
		$page_data['page_title']   = ('View Appointment');
		$page_data['appointments'] = $this->db->get('appointment')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	function view_payment($param1 = '', $param2 = '', $param3 = '')      //view payment report
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']  = 'view_payment';
		$page_data['page_title'] = ('View Payment');
		$page_data['payments']   = $this->db->get('payment')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	function view_bed_status($param1 = '', $param2 = '', $param3 = '')   //view beds
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']      = 'view_bed_status';
		$page_data['bed_allotments'] = $this->db->get('bed_allotment')->result_array();
		$page_data['beds']           = $this->db->get('bed')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	
	function view_report($param1 = '', $param2 = '', $param3 = '')     //view report
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		$page_data['page_name']   = 'view_report';
		$page_data['page_title']  = ('View ' . $param1 . ' Report');
		$page_data['report_type'] = $param1;
		$page_data['reports']     = $this->db->get_where('report', array(
			'type' => $param1
		))->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	function manage_email_template($param1 = '', $param2 = '', $param3 = '')     //manage email
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param2 == 'do_update') {
			$this->db->where('task', $param1);
			$this->db->update('email_template', array(
				'body' => $this->input->post('body'),
				'subject' => $this->input->post('subject')
			));
			$this->session->set_flashdata('flash_message', ('Template Updated'));
			redirect(base_url() . 'index.php?admin/manage_email_template/' . $param1, 'refresh');
		}
		$page_data['page_name']     = 'manage_email_template';
		$page_data['page_title']    = ('Manage Email Template');
		$page_data['template']      = $this->db->get_where('email_template', array(
			'task' => $param1
		))->result_array();
		$page_data['template_task'] = $param1;
		$this->load->view('index', $page_data);
	}
	
	
	function manage_noticeboard($param1 = '', $param2 = '', $param3 = '')    //manage noticeboard
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param1 == 'create') {
			$data['notice_title']     = $this->input->post('notice_title');
			$data['notice']           = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->db->insert('noticeboard', $data);
			$this->session->set_flashdata('flash_message', ('Report Created'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		}
		if ($param1 == 'edit' && $param2 == 'do_update') {
			$data['notice_title']     = $this->input->post('notice_title');
			$data['notice']           = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->db->where('notice_id', $param3);
			$this->db->update('noticeboard', $data);
			$this->session->set_flashdata('flash_message', ('Notice Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_profile'] = $this->db->get_where('noticeboard', array(
				'notice_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->db->where('notice_id', $param2);
			$this->db->delete('noticeboard');
			$this->session->set_flashdata('flash_message', ('Notice Deleted'));
			
			redirect(base_url() . 'index.php?admin/manage_noticeboard', 'refresh');
		}
		$page_data['page_name']  = 'manage_noticeboard';
		$page_data['page_title'] = ('Manage Noticeboard');
		$page_data['notices']    = $this->db->get('noticeboard')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	
	function system_settings($param1 = '', $param2 = '', $param3 = '')    //settings
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param2 == 'do_update') {
			$this->db->where('type', $param1);
			$this->db->update('settings', array(
				'description' => $this->input->post('description')
			));
			$this->session->set_flashdata('flash_message', ('Settings Updated'));
			redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
		}
		if ($param1 == 'upload_logo') {
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
			$this->session->set_flashdata('flash_message', ('Settings Updated'));
			redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
		}
		$page_data['page_name']  = 'system_settings';
		$page_data['page_title'] = ('System Settings');
		$page_data['settings']   = $this->db->get('settings')->result_array();
		$this->load->view('index', $page_data);
	}
	
	
	function manage_profile($param1 = '', $param2 = '', $param3 = '')     //manage profile
	{
		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
			
		if ($param1 == 'update_profile_info') {
			$data['name']    = $this->input->post('name');
			$data['email']   = $this->input->post('email');
			$data['address'] = $this->input->post('address');
			$data['phone']   = $this->input->post('phone');
			
			$this->db->where('admin_id', $this->session->userdata('admin_id'));
			$this->db->update('admin', $data);
			$this->session->set_flashdata('flash_message', ('Account Updated'));
			
			redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
		}
		if ($param1 == 'change_password') {
			$data['password']             = $this->input->post('password');
			$data['new_password']         = $this->input->post('new_password');
			$data['confirm_new_password'] = $this->input->post('confirm_new_password');
			
			$current_password = $this->db->get_where('admin', array(
				'admin_id' => $this->session->userdata('admin_id')
			))->row()->password;
			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
				$this->db->where('admin_id', $this->session->userdata('admin_id'));
				$this->db->update('admin', array(
					'password' => $data['new_password']
				));
				$this->session->set_flashdata('flash_message', ('Password Updated'));
			} else {
				$this->session->set_flashdata('flash_message', ('Password Mismatch'));
			}
			
			redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
		}
		$page_data['page_name']    = 'manage_profile';
		$page_data['page_title']   = ('Manage Profile');
		$page_data['edit_profile'] = $this->db->get_where('admin', array(
			'admin_id' => $this->session->userdata('admin_id')
		))->result_array();
		$this->load->view('index', $page_data);
	}
	
}
