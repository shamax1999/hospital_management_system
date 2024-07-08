<?php

if (!defined('BASEPATH'))

	exit('No direct script access allowed');



class Patient extends CI_Controller

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

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($this->session->userdata('patient_login') == 1)

			redirect(base_url() . 'index.php?patient/dashboard', 'refresh');

	}

	


	function dashboard()     //patient dashboard

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

			

		$page_data['page_name']  = 'dashboard';

		$page_data['page_title'] = ('Patient Dashboard');

		$this->load->view('index', $page_data);

	}

	


	function view_appointment($param1 = '', $param2 = '', $param3 = '')    //view apoinment 

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		$page_data['page_name']    = 'view_appointment';

		$page_data['page_title']   = ('View Appointment');

		$page_data['appointments'] = $this->db->get_where('appointment', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	function view_prescription($param1 = '', $param2 = '', $param3 = '')      //manage prescription

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		if ($param1 == 'edit') {

			$page_data['edit_profile'] = $this->db->get_where('prescription', array(

				'prescription_id' => $param2

			))->result_array();

		}

		$page_data['page_name']     = 'view_prescription';

		$page_data['page_title']    = ('View Prescription');

		$page_data['prescriptions'] = $this->db->get_where('prescription', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	function view_doctor($param1 = '', $param2 = '', $param3 = '')         //view doctors

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		$page_data['page_name']  = 'view_doctor';

		$page_data['page_title'] = ('View Doctor');

		$page_data['doctors']    = $this->db->get('doctor')->result_array();

		

		$this->load->view('index', $page_data);

	}

	

	function view_admit_history($param1 = '', $param2 = '', $param3 = '')    //view admit history

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		$page_data['page_name']      = 'view_admit_history';

		$page_data['page_title']     = ('View Admit History');

		$page_data['bed_allotments'] = $this->db->get_where('bed_allotment', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}



	function view_invoice($param1 = '', $param2 = '', $param3 = '')     //view payment

	{


		if ($param1 == 'make_payment') {

			$invoice_id      = $this->input->post('invoice_id');


			$invoice_details = $this->db->get_where('invoice', array(

				'invoice_id' => $invoice_id

			))->row();


		}



		$page_data['page_name']  = 'view_invoice';

		$page_data['page_title'] = ('View Invoice');

		$page_data['invoices']   = $this->db->get_where('invoice', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	

	function payment_history($param1 = '', $param2 = '', $param3 = '')      //payment history

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		$page_data['page_name']  = 'payment_history';

		$page_data['page_title'] = ('Payment History');

		$page_data['payments']   = $this->db->get_where('payment', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

	

	

	function view_operation_history($param1 = '', $param2 = '', $param3 = '')      //view operation history

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		

		$page_data['page_name']  = 'view_operation_history';

		$page_data['page_title'] = ('View Operation History');

		$page_data['reports']    = $this->db->get_where('report', array(

			'patient_id' => $this->session->userdata('patient_id'),

			'type' => 'operation'

		))->result_array();

		$this->load->view('index', $page_data);

	}

	


	function manage_profile($param1 = '', $param2 = '', $param3 = '')   //manage profile

	{

		if ($this->session->userdata('patient_login') != 1)

			redirect(base_url() . 'index.php?login', 'refresh');

		if ($param1 == 'update_profile_info') {

			$data['name']        = $this->input->post('name');

			$data['email']       = $this->input->post('email');

			$data['address']     = $this->input->post('address');

			$data['phone']       = $this->input->post('phone');

			$data['sex']         = $this->input->post('sex');

			$data['birth_date']  = $this->input->post('birth_date');

			$data['age']         = $this->input->post('age');

			$data['blood_group'] = $this->input->post('blood_group');

			

			$this->db->where('patient_id', $this->session->userdata('patient_id'));

			$this->db->update('patient', $data);

			$this->session->set_flashdata('flash_message', ('Profile Updated'));

			redirect(base_url() . 'index.php?patient/manage_profile/', 'refresh');

		}

		if ($param1 == 'change_password') {

			$data['password']             = $this->input->post('password');

			$data['new_password']         = $this->input->post('new_password');

			$data['confirm_new_password'] = $this->input->post('confirm_new_password');

			

			$current_password = $this->db->get_where('patient', array(

				'patient_id' => $this->session->userdata('patient_id')

			))->row()->password;

			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {

				$this->db->where('patient_id', $this->session->userdata('patient_id'));

				$this->db->update('patient', array(

					'password' => $data['new_password']

				));

				$this->session->set_flashdata('flash_message', ('Password Updated'));

			} else {

				$this->session->set_flashdata('flash_message', ('Password Mismatch'));

			}

			redirect(base_url() . 'index.php?patient/manage_profile/', 'refresh');

		}

		$page_data['page_name']    = 'manage_profile';

		$page_data['page_title']   = ('Manage Profile');

		$page_data['edit_profile'] = $this->db->get_where('patient', array(

			'patient_id' => $this->session->userdata('patient_id')

		))->result_array();

		$this->load->view('index', $page_data);

	}

}