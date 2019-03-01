 
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
                    <span>Edit</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Timesheet - Edit Entry</span>
                        </div>
                    </div>                    
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <form action="<?php echo site_url('admin/timesheet/edit') . "/" . $detail->id; ?>" method="post" id="create_timesheet_form"  class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-body">

                                <input type="hidden" name="entry_date" id="entry_date" value="<?php echo $detail->entry_date; ?>"/>
                                <input type="hidden" name="employees" id="employees" value="<?php echo $detail->emp_id; ?>"/>
                                
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>


                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Entry Date
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-group input-small input-daterange">
                                            <input type="text" readonly="readonly" disabled="disabled" value="<?php echo display_date($detail->entry_date); ?>" class="form-control" readonly maxlength="15">
                                        </div>
                                        <span class="help-block error entry_date_error"> <?php echo form_error("entry_date"); ?> </span>
                                    </div>
                                </div>

                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Employee
                                        <span class="required"> * </span>
                                    </label>
                                    <div id="emp_dropdown" class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php echo $employees; ?>

                                        </div>
                                        <span class="help-block employees_error error"> <?php echo form_error("employee"); ?> </span>
                                    </div>

                                </div>


                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Working Hours ( HH:MM )
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-1">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" <?php echo $disabled; ?> class="form-control" value="<?php echo date("H:i", strtotime($detail->total_hours)); ?>" name="working_hours" maxlength="20" id="working_hours" data-required="1" />
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
                                            <textarea class="form-control" rows="5" id="remark" name="remark" maxlength="500"><?php echo $detail->remarks; ?></textarea>
                                            <input type="hidden" name="id" value="<?php echo $detail->id; ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Update</button>
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

            // with time only.
//            $('#start_time, #end_time').timepicker({
//                defaultTime: '',
//                minuteStep: 1,
//                secondStep: 1,
//                showInputs: true,
//                disableFocus: true
//            });

             $("#working_hours").mask("99:99",{placeholder:"HH:MM"});

        });

//        function calculate_extra_hr() {
//
//            var param = $("#create_timesheet_form").serialize();
//
//            $.post("<?php //echo site_url('admin/timesheet/calculate_extra_hr'); ?>", param, function (response) {
//
//                var data = $.parseJSON(response);
//
//                if (data.status == 'success') {
//                    $("#extra_hour").val(data.extra_hr);
//                }
//
//
//            });
//        }



    </script>
