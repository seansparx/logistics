 
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
                    <a href="<?php echo site_url('admin/holiday/manage'); ?>">Holiday Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Edit Holiday</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Edit Holiday</span>
                        </div>

                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <?php echo form_open('', array("id" => "holiday_form", "class" => "form-horizontal")); ?>
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Holiday Name
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" name="holiday_name" id="holiday_name" data-required="1" value="<?php echo $details['holiday_name']; ?>" maxlength="50">
                                            <?php echo form_error("holiday_name"); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Holiday Date
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-5">
                                        <div class="input-group input-small input-daterange">
                                            <input type="text" name="holiday_date" id="holiday_date" value="<?php echo $details['holiday_date']; ?>" class="form-control" readonly maxlength="15">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error entry_date_error"> <?php echo form_error("holiday_date"); ?> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/holiday/manage'); ?>'" class="btn default">Cancel</button>
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
    </div>
    <script>
    
        $("#holiday_date").datetimepicker({
                format: 'yyyy-mm-dd',
                minView: 2, // this forces the picker to not go any further than days view
                pickerPosition: "bottom-left"

            }).on('changeDate', function () {
                   
            });
    </script>