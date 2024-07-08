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

				<a href="<?php echo base_url();?>index.php?nurse/dashboard" >

					<i class="icon-home icon-2x"></i>

					<span><?php echo ('Dashboard');?></span>

				</a>

		</li>

        

        

		<li class="<?php if($page_name == 'manage_patient')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?nurse/manage_patient" >

					<i class="icon-user icon-2x"></i>

					<span><?php echo ('Patient');?></span>

				</a>

		</li>

        

        

        

		<li class="dark-nav <?php if($page_name == 'manage_bed' || $page_name == 'manage_bed_allotment')echo 'active';?>">

			<span class="glow"></span>

            <a class="accordion-toggle  " data-toggle="collapse" href="#bed_submenu" >

                <i class="icon-hdd icon-2x"></i>

                <span><?php echo ('Bed Ward');?><i class="icon-caret-down"></i></span>

            </a>

            

            <ul id="bed_submenu" class="collapse <?php if($page_name == 'manage_bed' || $page_name == 'manage_bed_allotment')echo 'in';?>">

                <li class="<?php if($page_name == 'manage_bed')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?nurse/manage_bed">

                      <i class="icon-hdd"></i> <?php echo ('Manage Bed');?>

                  </a>

                </li>

                <li class="<?php if($page_name == 'manage_bed_allotment')echo 'active';?>">

                  <a href="<?php echo base_url();?>index.php?nurse/manage_bed_allotment">

                      <i class="icon-wrench"></i> <?php echo ('Manage Bed Allotment');?>

                  </a>

                </li>

            </ul>

		</li>

        

		<li class="<?php if($page_name == 'manage_report')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?nurse/manage_report" >

					<i class="icon-file icon-2x"></i>

					<span><?php echo ('Report');?></span>

				</a>

		</li>





		

		<li class="<?php if($page_name == 'manage_profile')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?nurse/manage_profile" >

					<i class="icon-lock icon-2x"></i>

					<span><?php echo ('Profile');?></span>

				</a>

		</li>

		

	</ul>

	

</div>