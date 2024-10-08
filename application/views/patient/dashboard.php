<div class="container-fluid padded">

	<div class="row-fluid">

        <div class="span30">


            <div class="action-nav-normal">

                <div class="row-fluid">

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?patient/view_doctor">

                        <i class="icon-user-md"></i>

                        <span><?php echo ('Doctor');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?patient/view_appointment">

                        <i class="icon-exchange"></i>

                        <span><?php echo ('Appointment');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?patient/view_prescription">

                        <i class="icon-stethoscope"></i>

                        <span><?php echo ('Prescription');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?patient/view_admit_history">

                        <i class="icon-hdd"></i>

                        <span><?php echo ('Admit History');?></span>

                        </a>

                    </div>

                

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?patient/view_invoice">

                        <i class="icon-credit-card"></i>

                        <span><?php echo ('View Invoice');?></span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

        

    </div>

    <hr />

    <div class="row-fluid">

    

        <div class="span6">

            <div class="box">

                <div class="box-header">

                    <span class="title">

                        <i class="icon-reorder"></i> <?php echo ('Noticeboard');?>

                    </span>

                </div>

                <div class="box-content scrollable" style="max-height: 500px; overflow-y: auto">

                

                    <?php 

                    $notices	=	$this->db->get('noticeboard')->result_array();

                    foreach($notices as $row):

                    ?>

                    <div class="box-section news with-icons">

                        <div class="avatar blue">

                            <i class="icon-tag icon-2x"></i>

                        </div>

                        <div class="news-time">

                            <span><?php echo date('d',$row['create_timestamp']);?></span> <?php echo date('M',$row['create_timestamp']);?>

                        </div>

                        <div class="news-content">

                            <div class="news-title">

                                <?php echo $row['notice_title'];?>

                            </div>

                            <div class="news-text">

                                 <?php echo $row['notice'];?>

                            </div>

                        </div>

                    </div>

                    <?php endforeach;?>

                </div>

            </div>

        </div>

    	

    </div>

</div>



  

  <script>

  $(document).ready(function() {


    $("#schedule_calendar").fullCalendar({

            header: {

                left: 	"prev,next",

                center: "title",

                right: 	"month,agendaWeek,agendaDay"

            },

            editable: 0,

            droppable: 0,

            events: [

					<?php 

                    $appointments	=	$this->db->get_where('appointment' , array('patient_id' => $this->session->userdata('patient_id')))->result_array();

                    foreach($appointments as $row):

                    ?>

					{

						title: "<?php echo ('Appointment').' : '.$this->crud_model->get_type_name_by_id('doctor' ,$row['doctor_id'], 'name' );?>",

						start: new Date(<?php echo date('Y',$row['appointment_timestamp']);?>, <?php echo date('m',$row['appointment_timestamp'])-1;?>, <?php echo date('d',$row['appointment_timestamp']);?>),

						end:	new Date(<?php echo date('Y',$row['appointment_timestamp']);?>, <?php echo date('m',$row['appointment_timestamp'])-1;?>, <?php echo date('d',$row['appointment_timestamp']);?>)  

            		},

					<?php

					endforeach;

					?>

					]

        })



});

  </script>