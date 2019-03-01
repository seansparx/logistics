<style>
    ul{ list-style: none; margin: 0; padding: 0}
</style>
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
                    <span>Reports</span>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Monthly Report</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"></h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row full-text">

            <div class="col-md-12">
                <!-- BEGIN BORDERED TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark bold uppercase">Monthly Report</span>
                        </div>
                        <div class="actions">
                            
                            <?php echo form_open('', array("id" => "filter_form")); ?>
                            
                                <div class="btn-group">
                                    <?php
                                        $options = array("employee" => "Employees", "vehicle" => "Vehicles", "contract" => "Contracts");

                                        echo form_dropdown('report_type', $options, $report_type, "class='bs-select form-control input-small' data-style='btn-primary'");
                                    ?>                                                    
                                </div>

                                <div class="btn-group">
                                    <?php
                                        for($m = 0; $m <= 15; $m++ ) {

                                            $time = strtotime("-".$m." month");

                                            $months[date("Y-m-01", $time)] = date("Y - F", $time);
                                        }

                                        echo form_dropdown('report_month', $months, $from_date, "class='bs-select form-control input-small'");
                                    ?>
                                </div>

                                <div class="btn-group">
                                    <div class="input-group input-medium">
                                        
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">Go</button>
                                        </span>

                                        <span class="input-group-btn">
                                           <button  class="btn btn-success print_btn" type="button">Print Report</button>
                                        </span>
                                        
                                        <span class="input-group-btn">
                                            <button name="export_xls" value="excel" class="btn purple" type="submit"><i class="fa fa-file-excel-o"></i> Download</button>
                                        </span>

                                    </div>
                                </div>

                            <?php echo form_close(); ?>
                            
                        </div>
                    </div>
                    <center><h3><?php echo date("F - Y", strtotime($from_date)); ?></h3></center>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            
                            <?php 
                                if($report_type == 'contract') {
                                    ?>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Contract Name </th>
                                                    <?php
                                                        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                                                             echo '<th><div class="text-center"><b>'.reports_date($d).'</b></div></th>';
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($reports) && count($reports) > 0) {

                                                        $i = 1;

                                                        foreach ($reports as $proj_id => $row) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $i++; ?></td>
                                                                    <td><?php echo $reports[$proj_id]['project_name']; ?></td>
                                                                        <?php
                                                                            for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {
                                                                                ?>
                                                                                <td>
                                                                                    <?php
                                                                                        if(isset($reports[$proj_id][$d])){

                                                                                            foreach ($reports[$proj_id][$d] as $report) {

                                                                                                echo '<ul style="width:120px;">';

                                                                                                    if($report->resource_type == 'employee'){

                                                                                                        echo '<li><b>' . emp_name($report->resource_id) . ' (Employee)</b></li>';
                                                                                                    }

                                                                                                    else if($report->resource_type == 'vehicle') {

                                                                                                        echo '<li><b>' . vehicle_name($report->resource_id) . ' (Vehicle)</b></li>';
                                                                                                    }

                                                                                                    echo '<li>Assign Hrs : ' . convertToHoursMins(($report->slots * 30)) . '</li>';

                                                                                                echo '</ul>';
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                </tr>    
                                                            <?php
                                                        }
                                                    } 
                                                    else {
                                                        ?>
                                                            <tr><td colspan="9">No report found</td></tr>    
                                                        <?php
                                                    }
                                                ?>  
                                                            <tr><td colspan="9">&nbsp;</td></tr>  
                                            </tbody>
                                        </table>
                                    <?php
                                }
                                else{
                                    ?>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> <?php echo ($report_type == 'vehicle') ? 'Vehicle' : 'Employee' ?> Name </th>
                                                    <?php
                                                        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                                                             echo '<th><div class="text-center"><b>'.reports_date($d).'</b></div></th>';
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($reports) && count($reports) > 0) {
                                                        $i = 1;
                                                        foreach ($reports as $emp_id => $row) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td><?php echo $reports[$emp_id]['emp_name'] . ' (' . $reports[$emp_id]['emp_code'] . ')'; ?></td>
                                                                <?php
                                                                    for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {
                                                                        ?>
                                                                        <td style="width:50% !important;">

                                                                                <?php
                                                                                    if(isset($reports[$emp_id][$d])){

                                                                                        foreach ($reports[$emp_id][$d] as $report) {

                                                                                            echo '<ul style="width:120px;">';
                                                                                            echo '<li><b>' . $report->project_name . '</b></li>';
                                                                                            echo '<li>Assign Hrs : ' . convertToHoursMins(($report->slots * 30)) . '</li>';
                                                                                            echo '</ul>';
                                                                                        }
                                                                                    }
                                                                                ?>

                                                                        </td>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </tr>    
                                                            <?php
                                                        }
                                                    } 
                                                    else {
                                                        ?>
                                                        <tr><td colspan="9">No report found</td></tr>    
                                                        <?php
                                                    }
                                                ?>  
                                               <tr><td colspan="9">&nbsp;</td></tr>  
                                            </tbody>
                                        </table>
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
jQuery(function($) { 'use strict';
    $('.print_btn').on('click', function() {
        $(".full-text").print({
            globalStyles : false,
            mediaPrint : true,
            stylesheet : "<?php echo base_url();?>/assets/admin/css/print.css",
            iframe : false,
            noPrintSelector : ".portlet-title",
            deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
        });
    }); 
});

</script>
