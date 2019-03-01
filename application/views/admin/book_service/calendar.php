<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN THEME PANEL -->

        <!-- END THEME PANEL -->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?php echo site_url('admin/adminarea'); ?>">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="<?php echo site_url('admin/book_service/manage'); ?>">Service Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Show Calendar</span>
                </li>
            </ul>

        </div>

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>

                    <?php

                        $get = $this->input->get();

                       //echo  date_format(date_create($get['year'].'-'.$get['month'].'-'."01"),"M"); die;

                        $month = ($get['month'])? date_format(date_create($get['year'].'-'.$get['month'].'-'."01"),"M"):date('M');
                        $year = ($get['year'])?$get['year']:date('Y');
                        
                       
                    ?>


                    <span class="caption-subject bold uppercase">Availability Calendar &nbsp&nbsp( <?php echo $month.'-'.$year?> )</span>
                </div>
                <div class="actions">

                    <div class="btn-group">
                        <a href="<?php echo site_url('admin/book_service/manage');?>"><button  class="btn sbold gray"> < Go Back </button></a>
                    </div>

                    <div class="btn-group">
                        <button  class="btn sbold green" id="prev_btn" > < Prev </button>
                    </div>

                    <div class="btn-group">
                        <button  class="btn sbold green" id="next_btn"> Next > </button>
                    </div>
                </div>
            </div>
        </div>
     
       

        <?php
            $this->load->view('admin/book_service/availibility_tabs');
        ?>

    </div>

    <?php $this->load->view('admin/book_service/time-slots_popup'); ?>

    <script>

        var ajax_req;
        
        $(document).ready(function () {

           

            check_availibility();
            

            $("#next_btn").click(function(){

                window.location.href='<?php echo base_url('admin/book_service/calendar');?>?month=<?php echo $next_mm; ?>&year=<?php echo $next_yy; ?>';                

            });


            $("#prev_btn").click(function(){
  
               
                window.location.href='<?php echo base_url('admin/book_service/calendar');?>?month=<?php echo $prev_mm; ?>&year=<?php echo $prev_yy; ?>';                
                                

            });

        });


        function check_availibility()
        {
                $("#tab_1").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');
                $("#tab_2").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');
                
                var month = "<?php echo ($this->input->get('month') ? $this->input->get('month') : date("m")); ?>";
                var year = "<?php echo ($this->input->get('year') ? $this->input->get('year') : date("Y")); ?>";
                var param = {"month" : month, "year" : year};

                if(ajax_req){ ajax_req.abort(); }
                
                ajax_req = $.post("<?php echo site_url('admin/book_service/show_calendar'); ?>/", param, function (data) {

                    var d = JSON.parse(data);

                    $("#tab_1").html(d.employee);
                    $("#tab_2").html(d.vehicle);
                    
                    $('.popovers').popover();

                });
           
        }



        function show_time_slots(date, type, resource_id)
        {
                 if(ajax_req){ ajax_req.abort(); }

                 ajax_req = $.post("<?php echo site_url('admin/book_service/available_calendar_slots'); ?>", {"type": type, "date": date, "key": resource_id}, function (data) {

                    var d = JSON.parse(data);

                    $('#modal_time_slots .modal-sub_title').html('The following slots are available on '+date);
                    $('#modal_time_slots .modal-resource_name').html(d.resource_name);
                    $('#modal_time_slots #table_slots > tbody').html(d.slots);
                    $("#full_day").prop("checked", false);
                    $('#modal_time_slots').modal('show');
                });
        }


 

    </script>
