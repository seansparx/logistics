<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?php echo site_url('admin/adminarea'); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Tables</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Daily Report</h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row full-text">

            <div class="col-md-12">
                <!-- BEGIN BORDERED TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-bubble font-dark"></i>
                            <span class="caption-subject font-dark bold uppercase">Daily Report <small><?php echo display_date($report_date); ?></small></span>
                        </div>
                        <div class="actions">
                            <?php echo form_open('', array("id" => "filter_form")); ?>
                            <div class="btn-group">                                                    
                                <?php
                                    $options = array("project" => "Contract Daily Report", "employee" => "Employee Daily Report", "vehicle" => "Vehicle Daily Report");

                                    echo form_dropdown('report_type', $options, $report_type, "class='bs-select form-control input-medium' data-style='btn-primary'");
                                ?>                                                    
                            </div>
                            <div class="btn-group">
                                <div class="input-group input-medium">
                                    <input type="text" id="report_date" name="report_date" value="<?php echo $report_date ? $report_date : date("Y-m-d"); ?>" class="form-control" readonly>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="btn-group">
                                <div class="input-group input-medium">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">Go</button>
                                    </span>

                                    <span class="input-group-btn">
                                        <button  class="btn btn-success print_btn " type="button">Print Report</button>
                                    </span>

                                    <span class="input-group-btn">
                                        <button name="export_xls" value="excel" class="btn purple" type="submit"><i class="fa fa-file-excel-o"></i> Download</button>
                                    </span>
                                </div>
                            </div>



                            <?php echo form_close(); ?>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">

                            <?php

                            if (count($reports) > 0) {

                                $i = 1;

                                if ($report_type == 'vehicle') {
                                    ?>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th> #ID </th>
                                                <th> Vehicle </th>
                                                <th>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th> Contract Title </th>
                                                                <th> Service Title </th>
                                                                <th> Department </th>
                                                                <th> Assign Hours </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($reports as $vhl_id => $arr) {

                                                    ?>
                                                        <tr>
                                                            <td><?php echo code($vhl_id); ?></td>
                                                            <td><?php echo vehicle_name($vhl_id); ?></td>
                                                            <td>
                                                                <table>
                                                                    <tbody>
                                                                        <?php                                                                             
                                                                            foreach ($arr as $obj) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $obj->project_name; ?> </td>
                                                                                    <td><?php echo $obj->service_title; ?></td>
                                                                                    <td><?php echo $obj->department_name; ?></td>
                                                                                    <td><?php echo convertToHoursMins($obj->slots * 30); ?> hrs</td>
                                                                                </tr>    
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>    
                                                    <?php

                                                    
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } 
                                
                                else if ($report_type == 'employee') {
                                    ?>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th> #ID </th>
                                                <th> Employee Name </th>
                                                <th>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th> Contract Title </th>
                                                                <th> Service Title </th>
                                                                <th> Department </th>
                                                                <th> Assign Hours </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($reports as $emp_id => $arr) {

                                                    ?>
                                                        <tr>
                                                            <td><?php echo emp_code($emp_id); ?></td>
                                                            <td><?php echo emp_name($emp_id); ?></td>
                                                            <td>
                                                                <table>
                                                                    <tbody>
                                                                        <?php 
                                                                            foreach ($arr as $obj) {
                                                                                ?>

                                                                                    <tr>
                                                                                        <td><?php echo $obj->project_name; ?> </td>
                                                                                        <td><?php echo $obj->service_title; ?></td>
                                                                                        <td><?php echo $obj->department_name; ?></td>
                                                                                        <td><?php echo convertToHoursMins($obj->slots * 30); ?> hrs</td>
                                                                                    </tr>

                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>    
                                                    <?php

                                                    
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } 
                                
                                else {
                                    ?>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Contract Title </th>
                                                <th> Assigned Resources </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                //pr($reports);
                                                foreach ($reports as $obj) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $obj->project; ?></td>
                                                            <td>
                                                                <table>
                                                                    <tbody>
                                                                        <?php                                                                             
                                                                            if(count($obj->assign_employees) > 0){
                                                                                
                                                                                ?>
                                                                                    <tr>
                                                                                        <th>Employee</th>
                                                                                        <th>Service</th>
                                                                                        <th>Department</th>
                                                                                        <th>Assign hours</th>
                                                                                    </tr>    
                                                                                <?php
                                                                                
                                                                                foreach($obj->assign_employees as $emp) {

                                                                                    ?>
                                                                                        <tr>
                                                                                            <td><?php echo $emp->emp_name.' ('.emp_code($emp->resource_id).')'; ?></td>
                                                                                            <td><?php echo $emp->service_title; ?></td>
                                                                                            <td><?php echo $emp->department_name; ?></td>
                                                                                            <td><?php echo ($emp->timings ? $emp->timings.' hrs' : '__:__'); ?></td>
                                                                                        </tr>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                                        <tr height="10"><td colspan="5"></td></tr>
                                                                        <?php 
                                                                            if(count($obj->assign_vehicles) > 0) {

                                                                                ?>
                                                                                    <tr>
                                                                                        <th>Vehicle</th>
                                                                                        <th></th>                                                                            
                                                                                        <th></th>
                                                                                        <th></th>
                                                                                    </tr>    
                                                                                <?php
                                                                                foreach($obj->assign_vehicles as $vhl) {

                                                                                    ?>
                                                                                        <tr>
                                                                                            <td><?php echo $vhl->regn_number.' ('.$vhl->model.')'; ?></td>
                                                                                            <td><?php echo $vhl->service_title; ?></td>
                                                                                            <td><?php echo $vhl->department_name; ?></td>
                                                                                            <td><?php echo ($vhl->timings ? $vhl->timings.' hrs' : '__:__'); ?></td>
                                                                                        </tr>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                
                                                            </td>
                                                        </tr>
                                                    <?php                                                    
                                                }
                                            ?>
                                        </tbody>
                                    </table>
        <?php
    }
} else {
    ?>
                                <p>No report found</p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- END BORDERED TABLE PORTLET-->
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script>

    $(document).ready(function () {

        // with date only.
        $("#report_date").datetimepicker({
            format: 'yyyy-mm-dd',
            endDate: '+6m',
            minView: 2, // this forces the picker to not go any further than days view
            pickerPosition: "bottom-left"

        }).on('changeDate', function (selected) {

        });

    });

</script>


<!-- Printing jquery-->

<script type='text/javascript'>

    jQuery(function ($) {
        'use strict';
        $('.print_btn').on('click', function () {
            $(".full-text").print({
                globalStyles: false,
                mediaPrint: true,
                stylesheet: "<?php echo base_url(); ?>/assets/admin/css/print.css",
                iframe: false,
                noPrintSelector: ".portlet-title",
                deferred: $.Deferred().done(function () {
                    console.log('Printing done', arguments);
                })
            });
        });
    });

</script>