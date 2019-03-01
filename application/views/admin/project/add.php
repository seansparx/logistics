 
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
                    <a href="<?php echo site_url('admin/project/manage'); ?>">Contract Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Contract</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Create Contract</span>
                        </div>

                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <form action="<?php echo site_url('admin/project/add'); ?>" method="post" id="project_form"  class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Contract Name
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" name="code" maxlength="50" id="code" value="<?php echo set_value('code'); ?>" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("code"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Customer Name
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" name="cust_name" maxlength="50" id="cust_name" value="<?php echo set_value('cust_name'); ?>" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("cust_name"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Description
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control" name="description" maxlength="500" id="description" rows="5" data-required="1"><?php echo set_value('description'); ?></textarea>
                                            <span class="help-block error"> <?php echo form_error("description"); ?> </span>
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
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/project/manage'); ?>'" class="btn default">Cancel</button>
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
