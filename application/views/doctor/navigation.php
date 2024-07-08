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

				<a href="<?php echo base_url();?>index.php?doctor/dashboard" >

					<i class="icon-home icon-2x"></i>

					<span><?php echo ('Dashboard');?></span>

				</a>

		</li>

        

       
        

		<li class="<?php if($page_name == 'manage_patient')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_patient" >

					<i class="icon-user icon-2x"></i>

					<span><?php echo ('Patient');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_appointment')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_appointment" >

					<i class="icon-edit icon-2x"></i>

					<span><?php echo ('Manage Appointment');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_prescription')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_prescription" >

					<i class="icon-stethoscope icon-2x"></i>

					<span><?php echo ('Manage Prescription');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_bed_allotment')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_bed_allotment" >

					<i class="icon-hdd icon-2x"></i>

					<span><?php echo ('Bed Allotment');?></span>

				</a>

		</li>

		

		

		<li class="<?php if($page_name == 'manage_report')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_report" >

					<i class="icon-file icon-2x"></i>

					<span><?php echo ('Manage Report');?></span>

				</a>

		</li>



		

		<li class="<?php if($page_name == 'manage_profile')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?doctor/manage_profile" >

					<i class="icon-lock icon-2x"></i>

					<span><?php echo ('Profile');?></span>

				</a>

		</li>

		

	</ul>

	

</div>