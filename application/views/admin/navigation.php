<div class="sidebar-background">

	<div class="primary-sidebar-background">

	</div>

</div>

<div class="primary-sidebar">

	

    <br />

    <div style="text-align:center;">

    	<a href="<?php echo base_url();?>">

        	<img src="<?php echo base_url();?>uploads/hmslg.png" />

        </a>

    </div>

   	<br />

	<ul class="nav nav-collapse collapse nav-collapse-primary">

    

        

        

		<li class="<?php if($page_name == 'dashboard')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/dashboard" >

					<i class="icon-home icon-2x"></i>

					<span><?php echo ('Dashboard');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_department')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_department" >

					<i class="icon-hospital icon-2x"></i>

					<span><?php echo ('Department');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_doctor')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_doctor" >

					<i class="icon-user-md icon-2x"></i>

					<span><?php echo ('Doctor');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_patient')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_patient" >

					<i class="icon-user icon-2x"></i>

					<span><?php echo ('Patient');?></span>

				</a>

		</li>

        

       

		<li class="<?php if($page_name == 'manage_nurse')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_nurse" >

					<img src="<?php echo base_url();?>uploads/nurseicon.png"width="30" height="30"color="white"; />

					<span><?php echo ('Nurse');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_laboratorist')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_laboratorist" >

					<img src="<?php echo base_url();?>uploads/labo.png"width="30" height="3"color="white"; />

                    <span><?php echo ('Laboratorist');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_accountant')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_accountant" >

					<i class="icon-money icon-2x"></i>

					<span><?php echo ('Accountant');?></span>

				</a>

		</li>

        

        

		

		<li class="dark-nav <?php if(	$page_name == 'view_appointment' 	|| 

										$page_name == 'view_payment' 		|| 

										$page_name == 'view_bed_status' 	||  

										$page_name == 'view_report'  )echo 'active';?>">

			<span class="glow"></span>

            <a class="accordion-toggle  " data-toggle="collapse" href="#view_hospital_submenu" >

                <i class="icon-ambulance icon-2x"></i>

                <span><?php echo ('Monitor Hospital');?><i class="icon-caret-down"></i></span>

            </a>

            

            <ul id="view_hospital_submenu" class="collapse <?php if(	$page_name == 'view_appointment' 	|| 

																		$page_name == 'view_payment' 		|| 

																		$page_name == 'view_bed_status' 	||   

																		$page_name == 'view_report'  )echo 'in';?>">

                <li class="<?php if($page_name == 'view_appointment')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_appointment">

                      <i class="icon-exchange"></i> <?php echo ('Appointments');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'view_payment')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_payment">

                      <i class="icon-money"></i> <?php echo ('Payments');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'view_bed_status')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_bed_status">

                      <i class="icon-hdd"></i> <?php echo ('Bed Status');?>

                  </a>

                </li>



                <li class="<?php if($page_name == 'view_report' && $report_type	==	'operation')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_report/operation">

                      <i class="icon-reorder"></i> <?php echo ('Operation');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'view_report' && $report_type	==	'birth')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_report/birth">

                      <i class="icon-github-alt"></i> <?php echo ('Birth Reports');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'view_report' && $report_type	==	'death')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/view_report/death">

                      <i class="icon-user"></i> <?php echo ('Death Reports');?>

                  </a>

                </li>

            </ul>

		</li>

        

        

        

		<li class="dark-nav <?php if(	$page_name == 'manage_email_template' 	|| 

										$page_name == 'manage_noticeboard' 		||

										$page_name == 'system_settings'  )echo 'active';?>">

			<span class="glow"></span>

            <a class="accordion-toggle  " data-toggle="collapse" href="#settings_submenu" >

                <i class="icon-cog icon-2x"></i>

                <span><?php echo ('Settings');?><i class="icon-caret-down"></i></span>

            </a>

            

            <ul id="settings_submenu" class="collapse <?php if(	$page_name == 'manage_email_template' 	|| 

																$page_name == 'manage_noticeboard' 		||

																$page_name == 'system_settings'  )echo 'in';?>">

                

                <li class="<?php if($page_name == 'manage_noticeboard')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/manage_noticeboard">

                      <i class="icon-file"></i> <?php echo ('Manage Noticeboard');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'system_settings')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?admin/system_settings">

                      <i class="icon-cog"></i> <?php echo ('System Settings');?>

                  </a>

                </li>

            </ul>

		</li>



		

		<li class="<?php if($page_name == 'manage_profile')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?admin/manage_profile" >

					<i class="icon-lock icon-2x"></i>

					<span><?php echo ('Profile');?></span>

				</a>

		</li>

		

	</ul>

	

</div>