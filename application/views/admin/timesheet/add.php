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
                    <a href="<?php echo site_url('admin/timesheet/manage'); ?>">Manage Timesheet</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Timesheet - Add Entry</span>
                        </div>
                    </div>                    
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <form action="<?php echo site_url('admin/timesheet/add'); ?>" method="post" id="create_timesheet_form"  class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-body">

                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>


                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Entry Date
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-group input-small input-daterange">
                                            <input type="text" name="entry_date" id="entry_date" value="<?php
                                                if (set_value('entry_date')) {
                                                    echo set_value('entry_date');
                                                } 
                                                else {
                                                    echo date('Y-m-d');
                                                }
                                            ?>" class="form-control" readonly maxlength="15">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error entry_date_error"> <?php echo form_error("entry_date"); ?> </span>
                                    </div>
                                </div>

                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Employee
                                        <span class="required"> * </span>
                                    </label>
                                    <div  class="col-md-3 emp_dropdown">
                                        
                                        
                                    </div>
                                </div>


                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Working Hours ( HH:MM )
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-1">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" <?php echo $disabled; ?> class="form-control" value="<?php echo set_value('working_hours'); ?>" name="working_hours" maxlength="20" id="working_hours" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("working_hours"); ?> </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Remark
                                        <span class="required">  </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control" rows="5" id="remark" name="remark" maxlength="500"><?php echo set_value('remark'); ?></textarea>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/timesheet/manage'); ?>'" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>


    <script>

        $(document).ready(function () {

            $("#working_hours").mask("99:99",{placeholder:"HH:MM"});


            // with date only.
            $("#entry_date").datetimepicker({
                format: 'yyyy-mm-dd',
                startDate: '-1m',
                endDate: '+0d',
                minView: 2, // this forces the picker to not go any further than days view
                pickerPosition: "bottom-left"

            }).on('changeDate', function () {
                   
                    check_working_employees();

            });

            $('#entry_date').on('changeDate', function (ev) {
                
                $(this).datetimepicker('hide');
            });

            // with time only.
//            $('#start_time').timepicker({
//                defaultTime: '<?php //echo $sys_config['SHIFT_START_TIME']; ?>',
//                minuteStep: 1,
//                secondStep: 1,
//                showInputs: true,
//                disableFocus: true
//            });
//
//            $('#end_time').timepicker({
//                defaultTime: '<?php //echo $sys_config['SHIFT_END_TIME']; ?>',
//                minuteStep: 1,
//                secondStep: 1,
//                showInputs: true,
//                disableFocus: true
//            });

            
//            $('#employees').on('change', function() {
//                
//                alert('djsfkasdlf');
//            });
            
            check_working_employees();

        });


//        function calculate_extra_hr() {
//
//            var param = $("#create_timesheet_form").serialize();
//            $.post("<?php //echo site_url('admin/timesheet/calculate_extra_hr'); ?>", param, function (response) {
//
//                var data = $.parseJSON(response);
//
//                if (data.status == 'success') {
//                    $("#extra_hour").val(data.extra_hr);
//                }
//            });
//        }

        function check_working_employees(){

            var param={entry_date:$("#entry_date").val()};

            $.post("<?php echo site_url('admin/timesheet/check_working_employees'); ?>",param,function(response){

                      $(".emp_dropdown").html(response+'<span class="help-block employees_error error"> <?php echo form_error("employees"); ?> </span>');  
    
            });
        }
        
        
        function get_assign_hrs(el){
            
            var emp_id = $(el).val();
            var entry_date = $("#entry_date").val();
            
            $.post("<?php echo site_url('admin/timesheet/assign_hrs'); ?>",{"emp_id": emp_id, "entry_date" : entry_date},function(hrs){

                $("#working_hours").val(hrs);
            });
        }

        

//        function check_assign_time(emp_id){
//
//             var param={emp_id:emp_id,entry_date:$("#entry_date").val()};
//
//            $.post("<?php //echo site_url('admin/timesheet/check_emp_assign_time'); ?>",param,function(response){
//
//                      var data = $.parseJSON(response);
//                      $("#start_time").val(data.start_time);
//                      $("#end_time").val(data.end_time);
//            });
//        }


    </script>












