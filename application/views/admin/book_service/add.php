
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
                                    <div class="input-group input-large date-picker input-daterange" data-date-start-date="<?php echo date("d-m-Y", gmt_to_local()); ?>" data-date-format="dd/mm/yyyy">
                                        <input type="text" class="form-control" name="start_date" placeholder="dd/mm/yyyy" id="start_date" value="<?php echo set_value('start_date'); ?>">
                                        <span class="input-group-addon"> to </span>
                                        <input type="text" class="form-control" name="end_date" placeholder="dd/mm/yyyy" id="end_date" value="<?php echo set_value('end_date'); ?>"> 
                                    </div>
                                    <!-- /input-group -->
                                    <span class="help-block"> <?php echo form_error("start_date"); ?> </span>
                                </div>
                            </div>
                            <div class="form-group  margin-top-20">
                                <label class="control-label col-md-3">Service Title

                                </label>
                                <div class="col-md-3">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <input type="text" class="form-control" value="<?php echo set_value('service_title'); ?>" name="service_title" maxlength="50" id="service_title" data-required="1" />
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
                                        echo form_dropdown('project', $options, set_value('project'), 'class="bs-select form-control" data-live-search="true" data-size="8" id="project" data-required="1"');
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

                                        echo form_dropdown('department', $options, set_value('department'), 'class="bs-select form-control" data-live-search="true" data-size="8" id="department" data-required="1"');
                                        ?>
                                        <span class="help-block error"> <?php echo form_error("department"); ?> </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-9">
                                    <button type="submit" class="btn green">Check Availability</button>
                                    <a href="<?php echo site_url('admin/book_service/manage'); ?>" class="btn grey">Cancel</a>
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

        $(document).ready(function () {

            $( "#start_date" ).datepicker({ minDate: -2, maxDate: "+1M +10D" });

        });

    </script>
