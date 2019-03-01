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
                                    <div class="input-group input-medium">
                                        
                                        <span class="input-group-btn">
                                            <a href="<?php echo site_url('admin/reports/human_resource');?>" class="btn grey">< Go Back</a>
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
                    
                    <center><h3>HR Report ( <?php echo $emp_name; ?> )</h3></center>

                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%"> Date </th>
                                        <th width="15%"> Assign Time </th>
                                        <th width="15%"> Working Time </th>
                                        <th width="20%"> Extra Time </th>
                                        <th width="30%"> Remarks </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (count($reports) > 0) {

                                            $i = 1;
                                            
                                            foreach ($reports as $obj) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo display_date($obj->entry_date); ?></td>
                                                    <td><?php echo ($obj->assign_hours > 0) ? date("H:i", strtotime($obj->assign_hours)).' hrs' : '-'; ?></td>
                                                    <td><?php echo ($obj->total_hours > 0) ? date("H:i", strtotime($obj->total_hours)).' hrs' : '-'; ?></td>
                                                    <td><?php echo ($obj->extra_hour > 0) ? date("H:i", strtotime($obj->extra_hour)).' hrs' : '-'; ?></td>
                                                    <td><?php echo $obj->remarks; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } 
                                        else {
                                            ?>
                                                <tr><td colspan="6"><center>No report found</center></td></tr>    
                                            <?php
                                        }
                                    ?>
                                    <tr><td>&nbsp;</td></tr>    
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
