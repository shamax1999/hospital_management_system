<div class="sidebar-background">

	<div class="primary-sidebar-background">

	</div>

</div>

<div class="primary-sidebar">


    <br />

    <div style="text-align:center;">

    	<a href="<?php echo base_url();?>">

        	<img src="<?php echo base_url();?>uploads/hmslg.png"/>

        </a>

    </div>

   	<br />

	<ul class="nav nav-collapse collapse nav-collapse-primary">

    

        

       

		<li class="<?php if($page_name == 'dashboard')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?accountant/dashboard" >

					<i class="icon-desktop icon-2x"></i>

					<span><?php echo ('Dashboard');?></span>

				</a>

		</li>

        

        <!--manage invoice-->

		<li class="<?php if($page_name == 'manage_invoice')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?accountant/manage_invoice" >

					<i class="icon-list-alt icon-2x"></i>

					<span><?php echo ('Invoice / Take Payment');?></span>

				</a>

		</li>

        

        

        <!--view_payment-->

		<li class="<?php if($page_name == 'view_payment')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?accountant/view_payment" >

					<i class="icon-money icon-2x"></i>

					<span><?php echo ('View Payment');?></span>

				</a>

		</li>

        

        

		<!--manage own profile-->

		<li class="<?php if($page_name == 'manage_profile')echo 'dark-nav active';?>">

			<span class="glow"></span>

				<a href="<?php echo base_url();?>index.php?accountant/manage_profile" >

					<i class="icon-lock icon-2x"></i>

					<span><?php echo ('Profile');?></span>

				</a>

		</li>

		

	</ul>

	

</div>