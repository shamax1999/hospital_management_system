<div class="box">

	<div class="box-header">


		<ul class="nav nav-tabs nav-tabs-left">
            
            <?php if(isset($edit_profile)):?>

			<li class="active">
                
                
                <a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 

					<?php echo ('Edit Appointment');?>

                    	</a></li>

            <?php endif;?>

			<li class="<?php if(!isset($edit_profile))echo 'active';?>">
                
                
                
                

            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 

					<?php echo ('Appointment List');?>

                    	</a></li>
            <li>

            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>

					<?php echo ('Add Appointment');?>

                    	</a></li>

		</ul>

    	
 
        

	</div>

	<div class="box-content padded">

		<div class="tab-content">
            
            
            <?php if(isset($edit_profile)):?>

			<div class="tab-pane box active" id="edit" style="padding: 5px">

                <div class="box-content">

                	<?php foreach($edit_profile as $row):?>

                    <?php echo form_open('patient/view_appointment/edit/do_update/'.$row['appointment_id'] , array('class' => 'form-horizontal validatable'));?>

                        <div class="padded">

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Patient');?></label>

                                <div class="controls" style="padding-top:6px;">

                                	<?php echo $this->crud_model->get_type_name_by_id('patient' ,$this->session->userdata('patient_id') , 'name');?>

                                    <input type="hidden" name="patient_id" value="<?php echo $this->session->userdata('patient_id');?>"  />

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Doctor');?></label>

                                <div class="controls">

                                    <select class="chzn-select" name="doctor_id">

										<?php 

										$this->db->order_by('account_opening_timestamp' , 'asc');

										$doctors	=	$this->db->get('doctor')->result_array();

										foreach($doctors as $row2):

										?>

                                        	<option value="<?php echo $row2['doctor_id'];?>" <?php if($row2['doctor_id'] == $row['doctor_id'])echo 'selected';?>>

												<?php echo $row2['name'];?></option>

                                        <?php

										endforeach;

										?>

									</select>

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Date');?></label>

                                <div class="controls">

                                    <input type="text" class="datepicker fill-up" name="appointment_timestamp" value="<?php echo date('m/d/Y', $row['appointment_timestamp']);?>"/>

                                </div>

                            </div>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-primary"><?php echo ('Edit Appointment');?></button>

                        </div>

                    <?php echo form_close();?>

                    <?php endforeach;?>

                </div>

			</div>

            <?php endif;?>

            
            
            
            
            
            
            
            
            
            
            
            

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">

				

                <table cellpadding="0" cellspacing="0" border="0" class="dTable responsive table-hover">

                	<thead>

                		<tr>

                    		<th><div>#</div></th>

                    		<th><div><?php echo ('Date');?></div></th>

                    		<th><div><?php echo ('Doctor');?></div></th>

                    		<th><div><?php echo ('Department');?></div></th>
                            
                            <th><div><?php echo ('Options');?></div></th>

						</tr>

					</thead>

                    <tbody>

                    	<?php $count = 1;foreach($appointments as $row):?>

                        <tr>

                            <td><?php echo $count++;?></td>

                            <td><?php echo date('d M,Y', $row['appointment_timestamp']);?></td>

							<td><?php echo $this->crud_model->get_type_name_by_id('doctor',$row['doctor_id'],'name');?></td>

							<td>

								<?php 

								$department_id	=	$this->crud_model->get_type_name_by_id('doctor',$row['doctor_id'],'department_id');

								$department_name=	$this->crud_model->get_type_name_by_id('department',$department_id,'name');

								echo $department_name;

								?>

                            </td>
                            
                            <td align="center">

                            	<a href="<?php echo base_url();?>index.php?patient/view_appointment/edit/<?php echo 
                               $row['appointment_id'];?>"

                                	rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit');?>" class="btn btn-primary">

                                		<i class="icon-wrench"></i>

                                </a>

                            	<a href="<?php echo base_url();?>index.php?patient/view_appointment/delete/<?php echo $row['appointment_id'];?>" onclick="return confirm('delete?')"

                                	rel="tooltip" data-placement="top" data-original-title="<?php echo ('Delete');?>" class="btn btn-danger">

                                		<i class="icon-trash"></i>

                                </a>

        					</td>
                            
                            
                            
                            
                            

                        </tr>

                        <?php endforeach;?>

                    </tbody>

                </table>

			</div>
            

            <div class="tab-pane box" id="add" style="padding: 5px">

                <div class="box-content">

                    <?php echo form_open('patient/view_appointment/create/' , array('class' => 'form-horizontal validatable'));?>

                        <div class="padded">

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Patient');?></label>

                                <div class="controls" style="padding-top:6px;">

                                	<?php echo $this->crud_model->get_type_name_by_id('patient' ,$this->session->userdata('patient_id') , 'name');?>

                                    <input type="hidden" name="patient_id" value="<?php echo $this->session->userdata('patient_id');?>"  />

                                </div>

                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Doctor');?></label>

                                <div class="controls">

                                    <select class="chzn-select" name="doctor_id">

										<?php 

										$this->db->order_by('doctor_id' , 'asc');

										$doctors	=	$this->db->get('doctor')->result_array();

										foreach($doctors as $row):

										?>

                                        	<option value="<?php echo $row['doctor_id'];?>"><?php echo $row['name'];?></option>

                                        <?php

										endforeach;

										?>

									</select>

                                </div>

                            </div>
                            
                            

                            <div class="control-group">

                                <label class="control-label"><?php echo ('Date');?></label>

                                <div class="controls">

                                    <input type="text" class="datepicker fill-up" name="appointment_timestamp"/>

                                </div>

                            </div>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-success"><?php echo ('Add Appointment');?></button>

                        </div>

                    <?php echo form_close();?>                

                </div>                

			</div>
            
            
		</div>

	</div>

</div>