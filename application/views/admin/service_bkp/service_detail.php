                 
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
                    <a href="<?php echo site_url('admin/service/manage'); ?>">Service Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Service Detail</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->

        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('success') != "") { ?>
                    <div class="alert alert-success ">
                        <button class="close" data-close="alert"></button> <?= $this->session->flashdata('success') ?> </div>
                <?php } ?>

                <!-- Begin: Demo Datatable 1 -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">
                                Service Detail ( <?php echo $service->service_title;?> )
                            </span>
                        </div>
                        <div class="actions">
                            
                            <div class="btn-group">
                                <button onClick="location.href = '<?php echo site_url('admin/service/manage'); ?>'" id="sample_editable_1_new" class="btn sbold default"> < Go Back</button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
                                <span> </span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">- Select Action -</option>
                                    <option value="delete_all">Delete All</option>
                                </select>
                                <button class="btn btn-sm green table-group-action-submit">
                                    <i class="fa fa-check"></i> Submit</button>
                            </div>
                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_services">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th width="150">Day</th>
                                        <th width="130">Initial Time</th>
                                        <th width="130">Ending Time</th>
                                        <th>Contract Code</th>
                                        <th width="160">No. of Employees</th>                                        
                                        <th width="160">No. of Vehicles</th>
                                        <th width="180">Last Updated</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: Demo Datatable 1 -->




            </div>
        </div>


    </div>
</div>
<!-- END CONTENT BODY -->

<?php
$this->load->view('admin/service/modal_popup');
?>

<script>
    
    function show_vehicles(ids, detail_id)
    {
        $.post("<?php echo site_url('admin/service/show_vehicles'); ?>",{"ids":ids, "assign_id":detail_id}, function(htmls){
			
			
            $("#modal_vehicle_popup .modal-body").html(htmls);

            $('#modal_vehicle_popup').modal('show');
            
           
        });
        
        
    }
    
    
    function show_employees(ids, detail_id)
    {
        $.post("<?php echo site_url('admin/service/show_employees'); ?>",{"ids":ids,"assign_id":detail_id}, function(htmls){
            $("#modal_employee_popup .modal-body").html(htmls);
            $('#modal_employee_popup').modal('show');
            popup_assign_emp_form();
        });
    }    
        
    
   
   function service_detail_list(){
	   
	    var ServiceDatatablesAjax = function () {

        var handleDemo = function () {

            var grid = new Datatable();

            grid.init({
                src: $("#datatable_services"),
                onSuccess: function (grid, response) {
                    // grid:        grid object
                    // response:    json object of server side ajax response
                    // execute some code after table records loaded
                    if(response.status=='id_error'){
                        window.location.href="<?php echo base_url('admin/service/manage')?>";
                    }
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
                        $("#datatable_services tr.filter .form-control").each(function () {
                            sValue[$(this).attr('name')] = $(this).val();

                        });

                        return sValue;
                    },
                    // read the custom filters from saved state and populate the filter inputs
                    "fnStateLoadParams": function (oSettings, oData) {
                        //Load custom filters
                        $("#datatable_services tr.filter .form-control").each(function () {
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
                        "url": "<?php echo site_url('admin/service/service_detail_ajax/'.$service_id); ?>", // ajax source
                    },
                    "order": [
                        [1, "asc"]
                    ]// set first column as a default sort by asc
                }
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {

                e.preventDefault();

                var action = $(".table-group-action-input", grid.getTableWrapper());

                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.setAjaxParam("customActionType", "group_action");
                    grid.setAjaxParam("customActionName", action.val());
                    grid.setAjaxParam("id", grid.getSelectedRows());
                    grid.getDataTable().ajax.reload();
                    grid.clearAjaxParams();
                }
                else if (action.val() == "") {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
                else if (grid.getSelectedRowsCount() === 0) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
            });

            //grid.setAjaxParam("customActionType", "group_action");
            //grid.getDataTable().ajax.reload();
            //grid.clearAjaxParams();
        }


        return {
            //main function to initiate the module
            init: function () {
                
                handleDemo();
            }

        };

    }();

    jQuery(document).ready(function () {
        ServiceDatatablesAjax.init();
    });
}
   
 service_detail_list(); 


</script>
