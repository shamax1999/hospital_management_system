<?php

if (!defined('BASEPATH'))

	exit('No direct script access allowed');




class Doctor extends CI_Controller

{


	function __construct()

	{

		parent::__construct();

		$this->load->database();

		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');

		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

		$this->output->set_header('Pragma: no-cache');

		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	}

	
	public function index()

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($this->session->userdata('doctor_login') == 1)

			redirect(base_url() . 'index.php?doctor/dashboard', 'refresh');

	}

	
	function dashboard()   //doctor dashboard

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		$page_data['page_name']  = 'dashboard';

		$page_data['page_title'] = ('Doctor Dashboard');

		$this->load->view('index', $page_data);

	}

	

	function manage_patient($param1 = '', $param2 = '', $param3 = '')       //manage patient

	{

		if ($this->session->userdata('doctor_login') != 1)

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

			

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

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

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('patient', array(

				'patient_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('patient_id', $param2);

			$this->db->delete('patient');

			

			$this->session->set_flashdata('flash_message', ('Account Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_patient', 'refresh');

		}

		$page_data['page_name']  = 'manage_patient';

		$page_data['page_title'] = ('Manage Patient');

		$page_data['patients']   = $this->db->get('patient')->result_array();

		$this->load->view('index', $page_data);

	}

	
	function manage_appointment($param1 = '', $param2 = '', $param3 = '')    //manage appoinment

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		if ($param1 == 'create') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = strtotime($this->input->post('appointment_timestamp'));

			$this->db->insert('appointment', $data);

			$this->session->set_flashdata('flash_message', ('Appointment Created'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']             = $this->input->post('doctor_id');

			$data['patient_id']            = $this->input->post('patient_id');

			$data['appointment_timestamp'] = strtotime($this->input->post('appointment_timestamp'));

			$this->db->where('appointment_id', $param3);

			$this->db->update('appointment', $data);

			$this->session->set_flashdata('flash_message', ('Appointment Updated'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('appointment', array(

				'appointment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('appointment_id', $param2);

			$this->db->delete('appointment');

			$this->session->set_flashdata('flash_message', ('Appointment Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_appointment', 'refresh');

		}

		$page_data['page_name']    = 'manage_appointment';

		$page_data['page_title']   = ('Manage Appointment');

		$page_data['appointments'] = $this->db->get_where('appointment', array(

			'doctor_id' => $this->session->userdata('doctor_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	

	function manage_prescription($param1 = '', $param2 = '', $param3 = '')      //manage prescription

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		

		if ($param1 == 'create') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['creation_timestamp']         = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			

			$this->db->insert('prescription', $data);

			$this->session->set_flashdata('flash_message', ('Prescription Created'));

			

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['doctor_id']                  = $this->input->post('doctor_id');

			$data['patient_id']                 = $this->input->post('patient_id');

			$data['case_history']               = $this->input->post('case_history');

			$data['medication']                 = $this->input->post('medication');

			$data['medication_from_pharmacist'] = $this->input->post('medication_from_pharmacist');

			$data['description']                = $this->input->post('description');

			

			$this->db->where('prescription_id', $param3);

			$this->db->update('prescription', $data);

			$this->session->set_flashdata('flash_message', ('Prescription Updated'));

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('prescription', array(

				'prescription_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('prescription_id', $param2);

			$this->db->delete('prescription');

			$this->session->set_flashdata('flash_message', ('Prescription Deleted'));

			

			redirect(base_url() . 'index.php?doctor/manage_prescription', 'refresh');

		}

		$page_data['page_name']     = 'manage_prescription';

		$page_data['page_title']    = ('Manage Prescription');

		$page_data['prescriptions'] = $this->db->get('prescription')->result_array();

		$this->load->view('index', $page_data);

	}


	

	function manage_bed_allotment($param1 = '', $param2 = '', $param3 = '')  //bed allocate

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		if ($param1 == 'create') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->insert('bed_allotment', $data);

			$this->session->set_flashdata('flash_message', ('Bed Alloted'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

		}

		if ($param1 == 'edit' && $param2 == 'do_update') {

			$data['bed_id']              = $this->input->post('bed_id');

			$data['patient_id']          = $this->input->post('patient_id');

			$data['allotment_timestamp'] = $this->input->post('allotment_timestamp');

			$data['discharge_timestamp'] = $this->input->post('discharge_timestamp');

			$this->db->where('bed_allotment_id', $param3);

			$this->db->update('bed_allotment', $data);

			$this->session->set_flashdata('flash_message', ('Bed Allotment Updated'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

			

		} else if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('bed_allotment', array(

				'bed_allotment_id' => $param2

			))->result_array();

		}

		if ($param1 == 'delete') {

			$this->db->where('bed_allotment_id', $param2);

			$this->db->delete('bed_allotment');

			$this->session->set_flashdata('flash_message', ('Bed Allotment Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_bed_allotment', 'refresh');

		}

		$page_data['page_name']     = 'manage_bed_allotment';

		$page_data['page_title']    = ('Manage Bed Allotment');

		$page_data['bed_allotment'] = $this->db->get('bed_allotment')->result_array();

		$this->load->view('index', $page_data);

	}

	


	function manage_report($param1 = '', $param2 = '', $param3 = '')  //manage report

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');


		if ($param1 == 'create') {

			$data['type']        = $this->input->post('type');

			$data['description'] = $this->input->post('description');

			$data['timestamp']   = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));

			$data['doctor_id']   = $this->input->post('doctor_id');

			$data['patient_id']  = $this->input->post('patient_id');

			$this->db->insert('report', $data);

			$this->session->set_flashdata('flash_message', ('Report Created'));

			redirect(base_url() . 'index.php?doctor/manage_report', 'refresh');

		}

		if ($param1 == 'delete') {

			$this->db->where('report_id', $param2);

			$this->db->delete('report');

			$this->session->set_flashdata('flash_message', ('Report Deleted'));

			redirect(base_url() . 'index.php?doctor/manage_report', 'refresh');

		}

		$page_data['page_name']  = 'manage_report';

		$page_data['page_title'] = ('Manage Report');

		$page_data['reports']    = $this->db->get('report')->result_array();

		$this->load->view('index', $page_data);

	}



	function manage_profile($param1 = '', $param2 = '', $param3 = '')   //manage profile

	{

		if ($this->session->userdata('doctor_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == 'update_profile_info') {

			$data['name']    = $this->input->post('name');

			$data['email']   = $this->input->post('email');

			$data['address'] = $this->input->post('address');

			$data['phone']   = $this->input->post('phone');

			$data['profile'] = $this->input->post('profile');

			

			$this->db->where('doctor_id', $this->session->userdata('doctor_id'));

			$this->db->update('doctor', $data);

			$this->session->set_flashdata('flash_message', ('Profile Updated'));

			redirect(base_url() . base_url() . 'index.php?doctor/manage_profile/', 'refresh');

		}

		if ($param1 == 'change_password') {

			$data['password']             = $this->input->post('password');

			$data['new_password']         = $this->input->post('new_password');

			$data['confirm_new_password'] = $this->input->post('confirm_new_password');

			

			$current_password = $this->db->get_where('doctor', array(

				'doctor_id' => $this->session->userdata('doctor_id')

			))->row()->password;

			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {

				$this->db->where('doctor_id', $this->session->userdata('doctor_id'));

				$this->db->update('doctor', array(

					'password' => $data['new_password']

				));

				$this->session->set_flashdata('flash_message', ('Password Updated'));

			} else {

				$this->session->set_flashdata('flash_message', ('Password Mismatch'));

			}

			redirect(base_url() . base_url() . 'index.php?doctor/manage_profile/', 'refresh');

		}

		$page_data['page_name']    = 'manage_profile';

		$page_data['page_title']   = ('Manage Profile');

		$page_data['edit_profile'] = $this->db->get_where('doctor', array(

			'doctor_id' => $this->session->userdata('doctor_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

}