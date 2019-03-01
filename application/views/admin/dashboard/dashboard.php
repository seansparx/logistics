 <div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?php echo site_url('admin/adminarea'); ?>">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
            </ul>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Dashboard</span>
                        </div>
                        <div class="actions">
                        </div>
                    </div>
                    <div class="portlet-body">
                        
                        <div class="row">

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="dashboard-stat dashboard-stat-v2 blue">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="<?php echo $count['total_services'];?>"><?php echo $count['total_services'];?></span>
                                        </div>
                                        <div class="desc"> Total no of services </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                 <div class="dashboard-stat dashboard-stat-v2 red">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="<?php echo $count['total_vehicle'];?>"><?php echo $count['total_vehicle'];?></span>
                                        </div>
                                        <div class="desc"> Total no of vehicles </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                 <div class="dashboard-stat dashboard-stat-v2 green">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="<?php echo $count['total_emp'];?>" ><?php echo $count['total_emp'];?></span>
                                        </div>
                                        <div class="desc"> Total no of employees </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                 <div class="dashboard-stat dashboard-stat-v2 purple">
                                    <div class="visual">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="details">
                                        <div class="number">
                                            <span data-counter="counterup" data-value="<?php echo $count['total_project'];?>" ><?php echo $count['total_project'];?></span>
                                        </div>
                                        <div class="desc"> Total no of contracts</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>






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
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">Go</button>
                                    </span>

<!--                                    <span class="input-group-btn">
                                        <button  class="btn btn-success print_btn " type="button">Print Report</button>
                                    </span>-->



                                  <!--   <span class="input-group-btn">
                                        <button name="export_xls" value="excel" class="btn purple" type="submit"><i class="fa fa-file-excel-o"></i> Download</button>
                                    </span> -->

<!--                                      <span class="input-group-btn">
                                        <button class="btn purple" type="submit" name ="export_export" value="excel">Download</button>
                                    </span>-->
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
</div>



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




