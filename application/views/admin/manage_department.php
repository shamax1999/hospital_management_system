<div class="box">
	<div class="box-header">
    
    	
		<ul class="nav nav-tabs nav-tabs-left">
        	<?php if(isset($edit_profile)):?>
			<li class="active">
            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 
					<?php echo ('Edit Department');?>
                    	</a></li>
            <?php endif;?>
			<li class="<?php if(!isset($edit_profile))echo 'active';?>">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ('Department List');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo ('Add Department');?>
                    	</a></li>
		</ul>
    	
        
	</div>
    <div class="box-content padded">
		<div class="tab-content">
        	
        	<?php if(isset($edit_profile)):?>
			<div class="tab-pane box active" id="edit" style="padding: 5px">
                <div class="box-content">
                	<?php foreach($edit_profile as $row):?>
                    <?php echo form_open('admin/manage_department/edit/do_update/'.$row['department_id'] , array('class' => 'form-horizontal validatable'));?>
                        <div class="padded">
                            <div class="control-group">
                              <label class="control-label"><?php echo ('Department Name');?></label>
                                <div class="controls">
                                   <input type="text" class="validate[required]" name="name" value="<?php echo $row['name'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Department Description');?></label>
                                <div class="controls">
                                    <input type="text" class="" name="description" value="<?php echo $row['description'];?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><?php echo ('Edit Department');?></button>
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
                    		<th><div><?php echo ('Department Name');?></div></th>
                    		<th><div><?php echo ('Description');?></div></th>
                    		<th><div><?php echo ('Options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($departments as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['description'];?></td>
							<td align="center">
                            	<a href="<?php echo base_url();?>index.php?admin/manage_department/edit/<?php echo $row['department_id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo ('Edit');?>" class="btn btn-primary">
                                		<i class="icon-wrench"></i>
                                </a>
                            	<a href="<?php echo base_url();?>index.php?admin/manage_department/delete/<?php echo $row['department_id'];?>" onclick="return confirm('delete?')"
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
                	<?php echo form_open('admin/manage_department/create' , array('class' =>'form-horizontal validatable'));?>
                        <div class="padded">
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Department Name');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="name"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo ('Department Description');?></label>
                                <div class="controls">
                                    <input type="text" class="" name="description"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><?php echo ('Add Department');?></button>
                        </div>
                    <?php echo form_close();?>                
                </div>                
			</div>
			

	  </div>
	</div>
</div>