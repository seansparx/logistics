 
<script>

    $(document).ready(function () {

         $("#monthly_hours").mask("999:99",{placeholder:"000:00"});

    });

</script>

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
                    <a href="<?php echo site_url('admin/employee/manage'); ?>">Employee Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Employee</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Employee Registration Form</span>
                        </div>

                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <form action="<?php echo site_url('admin/employee/add'); ?>" method="post" id="employee_form"  class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Employee Photo</label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <input type="file" name="emp_pic" id="emp_pic" />
                                            <small>( Allowed jpg, png, gif only - max size 2048 kb )</small>
                                            <span class="help-block error"> 
                                            <?php 
                                                if ($pic_error != "") {

                                                    echo $pic_error;
                                                }
                                            ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Employee Name
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" value="<?php echo set_value('emp_name'); ?>" name="emp_name" maxlength="50" id="emp_name" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("emp_name"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">State
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array(
                                                    '' => '-- Select State --',
                                                    'on leave' => 'On Leave',
                                                    'assign' => 'Assign',
                                                    'vacations' => 'Vacations' );
                                                echo form_dropdown('state', $options, set_value('state'), 'class="form-control" id="state" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("state"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Contract
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array(
                                                    '' => '-- Select Contract --',
                                                    'fixed' => 'Fixed',
                                                    'part-time' => 'Part-Time');
                                                echo form_dropdown('contract', $options, set_value('contract'), 'class="form-control" id="contract" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("contract"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">No. of hours ( per month )
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-1">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" value="<?php echo $details['monthly_hours']; ?>" name="monthly_hours" maxlength="20" id="monthly_hours" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("monthly_hours"); ?> </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Category
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array(
                                                    '' => '-- Select Category --',
                                                    'driver' => 'Driver',
                                                    'services' => 'Services',
                                                    'exhibitions' => 'Exhibitions',
                                                    'art' => 'Art');
                                                echo form_dropdown('category', $options, set_value('category'), 'class="form-control" id="category" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("category"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Status
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array(
                                                    '' => '-- Select Status --',
                                                    'active' => 'Active',
                                                    'inactive' => 'Inactive');
                                                echo form_dropdown('status', $options, set_value('status'), 'class="form-control" id="status" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("status"); ?> </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/employee/manage'); ?>'" class="btn default">Cancel</button>
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
    
    