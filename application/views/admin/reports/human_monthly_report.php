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
                            <span class="caption-subject font-dark bold uppercase">HR Report</span>
                        </div>
                        <div class="actions">
                            
                            <?php echo form_open('', array("id" => "filter_form")); ?>
                            
                                <div class="btn-group">
                                    <?php
                                    for ($m = 0; $m <= 15; $m++) {

                                        $time = strtotime("-" . $m . " month");

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
                    
                    <center><h3>HR Report ( <?php echo date("F", strtotime($from_date)); ?>  - <?php echo date("Y", strtotime($from_date)); ?> )</h3></center>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><b>Employee Name</b></th>
                                            <?php
                                                for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                                                    echo '<th><div class="text-center"><b>' . reports_date($d) . '</b></div></th>';
                                                }
                                            ?>
                                        <th><b>Total Hours</b></th>
                                        <th><b>Extra Hours</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($reports) > 0) {
                                        
                                        $i = 1;
                                        
                                        foreach ($reports as $emp_id => $row) {
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td width="30%"><a href="<?php echo site_url('admin/reports/human_resource/'.$emp_id); ?>"><?php echo $reports[$emp_id]['emp_name'] . ' (' . $reports[$emp_id]['emp_code'] . ')'; ?></a></td>
                                                    <?php

                                                        $assign_hours = 0;
                                                        $total_hours = 0;
                                                        $extra_hour = 0;

                                                        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                                                            ?>
                                                                <td>
                                                                    <?php
                                                                        if (isset($row[$d])) {

                                                                            echo ($reports[$emp_id][$d]->assign_hours > 0) ? '<span>'.date("H:i", strtotime($reports[$emp_id][$d]->assign_hours)).'</span> hrs.' : '-';
                                                                        } 
                                                                        else {

                                                                            echo '<p class="text-center"> - </p>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                            <?php

                                                            $ah = explode(":", $reports[$emp_id][$d]->assign_hours);

                                                            if($ah[0]) {

                                                                $assign_hours += ((intval($ah[0]) * 60) + intval($ah[1]));
                                                            }
                                                        }
                                                        
                                                        $eh = explode(":", $row['monthly_hours']);
                                                        
                                                        unset($eh[2]);
                                                        
                                                        if($eh[0] > 0){

                                                            $monthly_minuts = ((intval($eh[0]) * 60) + intval($eh[1]));
                                                        }

                                                        $extra = ($assign_hours - $monthly_minuts);
                                                        
                                                        $extra_hrs = ($extra > 0) ? convertToHoursMins($extra).' hrs.' : '-';
                                                    ?>
                                                    <td>
                                                        <b><?php echo ($assign_hours > 0) ? convertToHoursMins($assign_hours).' hrs.' : '-'; ?></b>
                                                    </td>
                                                    <td>
                                                        <b><?php echo $extra_hrs; ?></b>
                                                    </td>
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
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>    
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!-- END BORDERED TABLE PORTLET-->
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->

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
