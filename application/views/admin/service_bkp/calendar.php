 
<?php
if ($data_type == 'vehicle') {
    $page_title = '<span>Vehicles Availability</span>';
} 
else if ($data_type == 'employee') {
    $page_title = '<span>Employees Availability</span>';
}
?>
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
                    <?php echo $page_title; ?>                    
                </li>
            </ul>
            <div class="page-toolbar">
                <div class="btn-group pull-right">
                    <button onClick="location.href = '<?php echo site_url('admin/service/manage'); ?>'" id="sample_editable_1_new" class="btn sbold blue"> < Go Back</button>
                </div>

                <div class="btn-group pull-right">
                    <?php
                        if ($data_type == 'vehicle') {
                            ?>
                            <button onClick="location.href = '<?php echo site_url('admin/service/calendar/employee'); ?>'" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-user"></i> Employees Availability</button>
                            <?php
                        } 
                        else if ($data_type == 'employee') {
                            ?>
                            <button onClick="location.href = '<?php echo site_url('admin/service/calendar/vehicle'); ?>'" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-car"></i> Vehicles Availability</button>
                            <?php
                        }
                    ?>
                </div>
                
            </div>
        </div>
        <!-- END PAGE BAR -->

        <div class="row">
            
            <div class="col-md-12">
             
                
                <!-- BEGIN PORTLET-->
                <div class="portlet light calendar bordered">
                    <div class="portlet-title text-center">
                        <div class="">
                            <i class="icon-calendar font-dark hide"></i>
                            <span class="caption-subject font-dark bold uppercase"><?php echo $page_title; ?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="service_calendar"></div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>


    </div>
</div>
<!-- END CONTENT BODY -->
<style>
    .fc-widget-header{
        margin-bottom: 0px!important;
    }
    .fc-title{ float: none!important; font-size: 13px!important;}
    .fc-time{ font-size: 12px!important;}
</style>
<script>

    $(document).ready(function () {

        load_cal_data();
    });

    function load_cal_data(d)
    {
        $.getJSON("<?php echo site_url('admin/service/get_services') ?>", {"data_type": '<?php echo $data_type; ?>', "month": d}, function (data) {


             if(data.status=='no_record_found'){
                $("#service_calendar").text('No record found');
                $("#service_calendar").addClass('text-center');
                return false;
             }   


            $('#service_calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                navLinks: true, // can click day/week names to navigate views
                businessHours: true, // display business hours
                editable: false,
                eventLimit: true, // for all non-agenda views
                viewRender: function (view, element) {

                    if (view.intervalStart.format()) {

                        load_cal_data(view.intervalStart.format());

                    }
                }

            });

            $('#service_calendar').fullCalendar('removeEvents');
            $('#service_calendar').fullCalendar('addEventSource', data, true);



        });



    }

</script>


