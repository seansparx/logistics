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
                    <span>Administrator Management</span>
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
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> Administrator Management</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                                <button onClick="location.href = '<?php echo site_url('admin/settings/add_user'); ?>'" id="sample_editable_1_new" class="btn sbold green"> Add User
                                    <i class="fa fa-plus"></i>
                                </button>
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
                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_employees">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th width="80">Avatar</th>
                                        <th>Role</th>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email Address</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                    <tr class="filter" role="row">
                                        <td rowspan="1" colspan="1"> </td>
                                        <td rowspan="1" colspan="1"> </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <input type="text" placeholder="Role" name="filter_role" class="form-control form-filter input-sm"> </div>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <input type="text" placeholder="Username" name="filter_username" class="form-control form-filter input-sm"> </div>
                                        </td>                                        
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <input type="text" placeholder="First Name" name="filter_firstname" class="form-control form-filter input-sm"> </div>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <input type="text" placeholder="Last Name" name="filter_lastname" class="form-control form-filter input-sm"> </div>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <input type="text" placeholder="Email Address" name="filter_email" class="form-control form-filter input-sm"> </div>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <select class="form-control form-filter input-sm" name="filter_status">
                                                    <option value=""> All </option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div data-date-format="dd/mm/yyyy" class="input-group date date-picker margin-bottom-5">
                                                <input type="text" placeholder="Date" name="filter_date_from" readonly="" class="form-control form-filter input-sm">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-sm default">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                        <td rowspan="1" colspan="1">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                                    <i class="fa fa-search"></i> Search</button>
                                                <button class="btn btn-sm red btn-outline filter-cancel">
                                                    <i class="fa fa-times"></i> Reset</button>
                                            </div>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>


    </div>
</div>
<!-- END CONTENT BODY -->
<script>
    var ServiceDatatablesAjax = function () {

        var handleDemo = function () {

            var grid = new Datatable();

            grid.init({
                src: $("#datatable_employees"),
                onSuccess: function (grid, response) {
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
                        $("#datatable_employees tr.filter .form-control").each(function () {
                            sValue[$(this).attr('name')] = $(this).val();
                        });

                        return sValue;
                    },
                    // read the custom filters from saved state and populate the filter inputs
                    "fnStateLoadParams": function (oSettings, oData) {
                        //Load custom filters
                        $("#datatable_employees tr.filter .form-control").each(function () {
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
                        "url": "<?php echo site_url('admin/settings/admin_ajax'); ?>", // ajax source
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


function status_get(status,id){

        var param = {};
        param['status'] = status;
        param['id'] = id;
       

       $.post("<?php echo site_url('admin/settings/user_active_status'); ?>", param, function(response) {

           var data = $.parseJSON(response);

           if (data.status == 'success') {
               ServiceDatatablesAjax.init();
           }
       });
}

</script>
