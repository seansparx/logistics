 
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
                    <span>Edit Administrator</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Edit User</span>
                        </div>

                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <form action="" method="post" id="admin_form"  class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">User Role
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                echo form_dropdown('role', $roles_options, $edit_data->role_id, 'class="form-control" id="role" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("role"); ?> </span>
                                        </div>
                                    </div>
                                </div>    
                                <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-3">First Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" value="<?php echo $edit_data->first_name; ?>" name="first_name" maxlength="50" id="first_name" data-required="1" />
                                                <span class="help-block error"> <?php echo form_error("first_name"); ?> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-3">Last Name
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" value="<?php echo $edit_data->last_name; ?>" name="last_name" maxlength="50" id="last_name" data-required="1" />
                                                <span class="help-block error"> <?php echo form_error("last_name"); ?> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  margin-top-20">
                                        <label class="control-label col-md-3">Username
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" value="<?php echo $edit_data->username; ?>" name="username" maxlength="50" id="username" data-required="1" />
                                                <span class="help-block error"> <?php echo form_error("username"); ?> </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Email Address
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" value="<?php echo $edit_data->emailId; ?>" name="email_id" maxlength="100" id="email_id" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("email_id"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Status
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array(
                                                    '' => '-- Select Status --',
                                                    '1' => 'Active',
                                                    '0' => 'Inactive');
                                                echo form_dropdown('status', $options, $edit_data->status, 'class="form-control" id="status" data-required="1"');
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
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/settings/manage_users'); ?>'" class="btn default">Cancel</button>
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
    