<div class="container-fluid padded">

	<div class="row-fluid">

        <div class="span30">


            <div class="action-nav-normal">

                <div class="row-fluid">

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?nurse/manage_patient">

                        <i class="icon-user"></i>

                        <span><?php echo ('Patient');?></span>

                        </a>

                    </div>

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?nurse/manage_bed_allotment">

                        <i class="icon-hdd"></i>

                        <span><?php echo ('Bed Allotment');?></span>

                        </a>

                    </div>
                    
                    

                    <div class="span2 action-nav-button">

                        <a href="<?php echo base_url();?>index.php?nurse/manage_report">

                        <i class="icon-file"></i>

                        <span><?php echo ('Report');?></span>

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

                    $notices	=	$this->db->get('noticeboard')->result_array();

                    foreach($notices as $row):

                    ?>

					{

						title: "<?php echo $row['notice_title'];?>",

						start: new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>),

						end:	new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>)  

            		},

					<?php

					endforeach;

					?>

					]

        })



});

  </script>