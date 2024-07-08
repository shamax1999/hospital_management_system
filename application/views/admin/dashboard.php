<div class="container-fluid padded">

	<div class="row-fluid">

        <div class="span30">

            <div class="action-nav-normal">

                <div class="row-fluid">

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_doctor">

                        <i class="icon-user-md"></i>

                        <span><?php echo ('Doctor');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_patient">

                        <i class="icon-user"></i>

                        <span><?php echo ('Patient');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_nurse">

                        <img src="<?php echo base_url();?>uploads/nurse2.png"width="30" height="30"color="white"; />

                        <span><?php echo ('Nurse');?></span>

                        </a>

                    </div>

            

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_laboratorist">

                        <img src="<?php echo base_url();?>uploads/testtube.png"width="35" height="35"color="white"; />

                        <span><?php echo ('Laboratorist');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_accountant">

                        <i class="icon-money"></i>

                        <span><?php echo ('Accountant');?></span>

                        </a>

                    </div>
                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_appointment">

                        <i class="icon-exchange"></i>

                        <span><?php echo ('Appointment');?></span>

                        </a>

                    </div>

                </div>

                <div class="row-fluid">

                    

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_payment">

                        <i class="icon-credit-card"></i>

                        <span><?php echo ('Payment');?></span>

                        </a>

                    </div>


                    

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_report/operation">

                        <i class="icon-reorder"></i>

                        <span><?php echo ('Operation Report');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_report/birth">

                        <img src="<?php echo base_url();?>uploads/birth.png"width="50" height="50"color="white"; />

                        <span><?php echo ('Birth Report');?></span>

                        </a>

                    </div>
                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_report/death">

                        <i class="icon-minus-sign"></i>

                        <span><?php echo ('Death Report');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/view_bed_status">

                        <i class="icon-hdd"></i>

                        <span><?php echo ('Bed Allotment');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?admin/manage_noticeboard">

                        <i class="icon-columns"></i>

                        <span><?php echo ('Noticeboard');?></span>

                        </a>

                    </div>


                </div>

                
            </div>

        </div>

        

    </div>

    <hr />

    <div class="row-fluid">

    


    	<!--noticeboard-->

        <div class="span6">

            <div class="box">

                <div class="box-header"> 

                    <span class="title">

                        <?php echo ('Noticeboard');?>

                    </span>

                </div>

                <div class="box-content scrollable" style="max-height: 500px; overflow-y: auto">

                

                    <?php 

					$this->db->order_by('create_timestamp' , 'desc');

                    $notices	=	$this->db->get('noticeboard')->result_array();

                    foreach($notices as $row):

                    ?>

                    <div class="box-section news with-icons">

                        <div class="avatar blue">

                            <i class="icon-pushpin icon-2x"></i>

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

  