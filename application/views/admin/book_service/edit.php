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
                    <span>Add Service</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->

        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Book Service</span>
                        </div>
                    </div>                    
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php echo form_open('', array("id" => "create_service_form", "class" => "form-horizontal")); ?>

                        <div class="form-body">

                            <?php 
                                if ($errors != "") {
                                    ?>
                                        <div class="alert alert-danger">
                                            <button class="close" data-close="alert"></button> <?php echo $errors; ?>  
                                        </div>
                                    <?php 
                                }
                                
                                if ($this->session->flashdata('success')) {
                                    ?>
                                        <div class="alert alert-success display">
                                            <button class="close" data-close="alert"></button> <?php echo $this->session->flashdata('success'); ?>
                                        </div>
                                    <?php 
                                }
                            ?>
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Select Date<span class="required"> * </span></label>
                                <div class="col-md-4">
                                    <div class="input-group input-large date-picker input-daterange" data-date-start-date="<?php echo date("d-m-Y", gmt_to_local()); ?>" data-date="<?php echo date('d/m/Y');?>" data-date-format="dd/mm/yyyy">

                                        <input type="text" class="form-control" name="start_date" id="start_date" placeholder="dd/mm/yyyy" value="<?php echo display_date($detail->start_date); ?>">
                                        <span class="input-group-addon"> to </span>
                                        <input type="text" class="form-control" name="end_date" id="end_date" placeholder="dd/mm/yyyy" value="<?php echo display_date($detail->end_date); ?>"> </div>
                                    <!-- /input-group -->
                                    <span class="help-block"> <?php echo form_error("start_date"); ?> </span>
                                </div>
                            </div>
                            <div class="form-group  margin-top-20">
                                <label class="control-label col-md-3">Service Title</label>
                                <div class="col-md-3">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <input type="text" class="form-control" value="<?php echo $detail->service_title; ?>" name="service_title" maxlength="50" id="service_title" data-required="1" />
                                        <span class="help-block error"> <?php echo form_error("service_title"); ?> </span>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group  margin-top-20">
                                <label class="control-label col-md-3">Contract
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-3">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <?php
                                        $options = array("" => "-- Select contract --");
                                        if (is_array($projects)) {

                                            foreach ($projects as $row) {

                                                if ($row->status == 'active') {
                                                    $options[$row->id] = $row->code . ' (' . $row->customer_name . ')';
                                                }
                                            }
                                        }
                                        echo form_dropdown('project', $options, $detail->contract_id, 'class="bs-select form-control" data-live-search="true" data-size="8" id="project" data-required="1"');
                                        ?>
                                        <span class="help-block error"> <?php echo form_error("project"); ?> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group  margin-top-20">
                                <label class="control-label col-md-3">Department
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-3">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <?php
                                        $options = array("" => "-- Select Department --");

                                        if (is_array($departments)) {

                                            foreach ($departments as $row) {

                                                if ($row->status == 'active') {

                                                    $options[$row->id] = $row->name;
                                                }
                                            }
                                        }

                                        echo form_dropdown('department', $options, $detail->department_id, 'class="bs-select form-control" data-live-search="true" data-size="8" id="department" data-required="1"');
                                        ?>
                                        <span class="help-block error"> <?php echo form_error("department"); ?> </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn dark">Update</button>
                                    <a href="<?php echo site_url('admin/book_service/manage'); ?>" class="btn grey">Cancel</a>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" onclick="check_availibility();" class="btn green">Check Availability</button>
                                    <button type="button" onclick="get_working_resources();" class="btn green">Working Resources</button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->


        <?php
            $this->load->view('admin/book_service/availibility_tabs');
        ?>

    </div>

    <?php $this->load->view('admin/book_service/time-slots_popup'); ?>

    <script>

        var ajax_req;
        
        $(document).ready(function () {
            
            check_availibility();

        });


        function check_availibility()
        {
                if ($("#create_service_form").valid()) {

                        $("#tab_1").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');
                        $("#tab_2").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');

                        var param = $("#create_service_form").serialize();

                        if(ajax_req){ ajax_req.abort(); }

                        ajax_req = $.post("<?php echo site_url('admin/book_service/check_availibility'); ?>", param, function (data) {

                            var d = JSON.parse(data);

                            $("#tab_1").html(d.employee);
                            $("#tab_2").html(d.vehicle);
                            
                            $('.popovers').popover();

                        });
                        
                        $(".nav-tabs > li:first > a").html('Employees Availability');
                        $(".nav-tabs > li:last > a").html('Vehicles Availability');
                }
        }
        
        
        function get_working_resources()
        {
                if ($("#create_service_form").valid()) {

                        $("#tab_1").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');
                        $("#tab_2").html('<center><img src="<?php echo base_url('images/ajax-loader.gif'); ?>"/> Please Wait..</center>');

                        var param = $("#create_service_form").serialize();

                        if(ajax_req){ ajax_req.abort(); }

                        ajax_req = $.post("<?php echo site_url('admin/book_service/get_working_resources'); ?>", param, function (data) {

                            var d = JSON.parse(data);

                            $("#tab_1").html(d.employee);
                            $("#tab_2").html(d.vehicle);
                            
                            $('.popovers').popover();

                        });
                        
                        $(".nav-tabs > li:first > a").html('Working Employees');
                        $(".nav-tabs > li:last > a").html('Working Vehicles');
                }
        }



        function show_time_slots(date, type, resource_id)
        {
                if(ajax_req){ ajax_req.abort(); }

                ajax_req = $.post("<?php echo site_url('admin/book_service/available_slots'); ?>", {"type": type, "date": date, "key": resource_id, "service_id" : <?php echo $detail->id; ?>}, function (data) {

                    var d = JSON.parse(data);

                    $('#modal_time_slots .modal-sub_title').html('The following slots are available on '+date);
                    $('#modal_time_slots .modal-resource_name').html(d.resource_name);
                    $('#modal_time_slots #table_slots > tbody').html(d.slots);
                    $("#full_day").prop("checked", false);
                    $('#modal_time_slots').modal('show');
                });
        }


        function book_now()
        {
                if($("input[name='slots[]']:checked").length == 0){

                    alert("Please select at least one time-slot.");
                    
                    return;
                }
            
                var param = $("#form_time_slots").serialize();

                if(ajax_req){ ajax_req.abort(); }

                ajax_req = $.post("<?php echo site_url('admin/book_service/book_slots/'.$edit_id); ?>", param, function (data) {

                    if (data) {
                        check_availibility();
                        $('#modal_time_slots').modal('hide');
                    }
                });
        }

    </script>
