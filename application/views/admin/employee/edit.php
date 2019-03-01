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
                    <span>Edit Employee</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Edit Employee</span>
                        </div>

                    </div>
                    <?php 
                        $disabled = '';
                        $notice = '';
                        
                        if($details['left_company'] == 1) {

                            $disabled = 'disabled';
                            
                            echo '<div class="alert alert-danger"><h3>This emplyee is no longer available in the company.</h3></div>';
                        }
                    ?>
                    
                    <div class="portlet-body">
                        <ul class="nav nav-tabs">
                            <li>
                                <a href="#tab_1" data-toggle="tab"> Basic Info</a>
                            </li>
                            <li class="active">
                                <a href="#tab_2" data-toggle="tab"> Leave </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade" id="tab_1">
                                
                                <form action="<?php echo site_url('admin/employee/edit/' . $details['id']); ?>"  method="post" id="employee_form"  class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">

                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                        <div class="alert alert-success display-hide">
                                            <button class="close" data-close="alert"></button> Your form validation is successful! </div>

                                        <div class="form-group  margin-top-20">
                                            <label class="control-label col-md-3">Employee Photo </label>
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
                                                    <input type="text" <?php echo $disabled; ?> class="form-control" value="<?php echo $details['emp_name']; ?>" name="emp_name" maxlength="50" id="emp_name" data-required="1" />
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
                                                        'vacations' => 'Vacations');
                                                    echo form_dropdown('state', $options, strtolower($details['state']), 'class="form-control" '.$disabled.' id="state" data-required="1"');
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
                                                    echo form_dropdown('contract', $options, strtolower($details['contract']), 'class="form-control" '.$disabled.' id="contract" data-required="1"');
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
                                                    <input type="text" <?php echo $disabled; ?> class="form-control" value="<?php echo $details['monthly_hours']; ?>" name="monthly_hours" maxlength="20" id="monthly_hours" data-required="1" />
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
                                                    echo form_dropdown('category', $options, strtolower($details['category']), 'class="form-control" '.$disabled.' id="category" data-required="1"');
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
                                                    echo form_dropdown('status', $options, strtolower($details['status']), 'class="form-control" '.$disabled.' id="status" data-required="1"');
                                                    ?>
                                                    <span class="help-block error"> <?php echo form_error("status"); ?> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group  margin-top-20">
                                            <label class="control-label col-md-3">Left the company
                                                <span class="required"></span>
                                            </label>
                                            <div class="col-md-1">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <?php 
                                                        $checked = ($details['left_company'] == 1) ? 'checked' : '';
                                                    ?>
                                                    <input type="checkbox" class="form-control" <?php echo $disabled; ?> value="1" <?php echo $checked; ?> name="left_company" id="left_company"/>
                                                    <span class="help-block error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" <?php echo $disabled; ?> class="btn green">Submit</button>
                                            <button type="button" onclick="location.href = '<?php echo site_url('admin/employee/manage'); ?>'" class="btn default">Cancel</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="tab-pane active fade in" id="tab_2">
                                
                                <div class="col-md-4">
                                    <br/>
                                    <?php echo form_open('', array("id" => "leave_form", "class" => "form-horizontal")); ?>
                                
                                    <div class="form-body">

                                        <?php 
                                            if ($errors != "") {
                                                ?>
                                                    <div class="alert alert-danger">
                                                        <button class="close" data-close="alert"></button> <?php echo $errors; ?>  
                                                    </div>
                                                <?php
                                            } 
                                        ?>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Date From</label>
                                            <div class="col-md-4">
                                                <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="dd/mm/yyyy">
                                                    <input type="text" <?php echo $disabled; ?> class="form-control" name="start_date" id="start_date" value="<?php echo set_value('start_date'); ?>">
                                                    <span class="input-group-addon"> to </span>
                                                    <input type="text" <?php echo $disabled; ?> class="form-control" name="end_date" id="end_date" value="<?php echo set_value('end_date'); ?>"> </div>
                                                <!-- /input-group -->
                                                <span class="help-block"> <?php echo form_error("start_date"); ?> </span>
                                            </div>
                                        </div>

                                        <div class="form-group  margin-top-20">
                                            <label class="control-label col-md-4">Reason
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-8">
                                                <div class="input-icon right">
                                                    <i class="fa"></i>
                                                    <?php
                                                        echo form_textarea('reason', set_value('reason'), 'class="form-control" '.$disabled.' rows="4" style="width:320px;height:150px;" id="reason" data-required="1"');
                                                    ?>
                                                    <span class="help-block error"> <?php echo form_error("reason"); ?> </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-9">
                                                <input type="submit" name="btn_leave" class="btn green" <?php echo $disabled; ?> value="Submit"/>
                                                <a href="<?php echo site_url('admin/employee/manage'); ?>" class="btn grey">Cancel</a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php echo form_close(); ?>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-7">
                                    <div class="table-container">
                                        
                                        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_leaves">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="20"></th>
                                                    <th width="100">Leave Date</th>
                                                    <th>Leave Reason</th>
                                                    <th width="150">Created</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix margin-bottom-20"> </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
<!-- END CONTENT BODY -->
</div>

<script>
    
    var ServiceDatatablesAjax = function () {

        var handleDemo = function () {

            var grid = new Datatable();

            grid.init({
                src: $("#datatable_leaves"),
                onSuccess: function (grid, response) { 
                    //alert(response);
                    // grid:        grid object
                    // response:    json object of server side ajax response
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error  
                },
                onDataLoad: function (grid) {
                    // execute some code on ajax data load
                },
                loadingMessage: 'Loading...',
                dataTable: {
                    // save datatable state(pagination, sort, etc) in cookie.
                    "bStateSave": true,
                    destroy: true,
                    "dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                    // save custom filters to the state
                    "fnStateSaveParams": function (oSettings, sValue) {
                        $("#datatable_leaves tr.filter .form-control").each(function () {
                            sValue[$(this).attr('name')] = $(this).val();
                        });

                        return sValue;
                    },
                    // read the custom filters from saved state and populate the filter inputs
                    "fnStateLoadParams": function (oSettings, oData) {
                        //Load custom filters
                        $("#datatable_leaves tr.filter .form-control").each(function () {
                            var element = $(this);
                            if (oData[element.attr('name')]) {
                                element.val(oData[element.attr('name')]);
                            }
                        });

                        return true;
                    },
                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "pageLength": 10, // default record count per page
                    "ajax": {
                        "url": "<?php echo site_url('admin/employee/leave_ajax/'.$emp_id); ?>", // ajax source
                    },
                    "order": [
                        [1, "asc"]
                    ] 
                    // set first column as a default sort by asc
                }
            });

        }

        return {
            //main function to initiate the module
            init: function () {

                handleDemo();
            }

        };

    }();


    jQuery(document).ready(function () {
    
        $("#monthly_hours").mask("999:99",{placeholder:"000:00"});
    
        ServiceDatatablesAjax.init();
    });
    
</script>