
<link href="<?php echo base_url() ?>assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url() ?>assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>

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
                    <a href="<?php echo site_url('admin/settings/manage_role'); ?>">Role Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Role</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Create Role</span>
                        </div>

                    </div>

                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <form action="<?php echo site_url('admin/settings/add_role'); ?>" method="post" id="role_form"  class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Role Title
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" value="<?php echo set_value('role_title'); ?>" name="role_title" maxlength="50" id="first_name" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("role_title"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Description</label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control" name="description" maxlength="500" id="description"><?php echo set_value('description'); ?></textarea>
                                            <span class="help-block error"> <?php echo form_error("description"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-offset-2 col-md-5">
                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="icon-lock font-green-sharp"></i>
                                                    <span class="caption-subject font-green-sharp bold uppercase">Module Permissions</span>
                                                    <span class="help-block error"> <?php echo form_error("permissions"); ?> </span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <input readonly name="permissions" type="hidden" id="event_result" value="">
                                                <div id="role_modules" class="tree-demo">
                                                        <?php
                                                            if(count($menus) > 0){
                                                                
                                                                echo '<ul>';
                                                                
                                                                foreach ($menus as $menu) {
                                                                    ?>
                                                                    <li data-jstree='{ "module_id" : <?php echo $menu->id; ?>, "opened" : true }'> <?php echo $menu->display_name; ?>

                                                                        <?php
                                                                        if(count($menu->sub_menus) > 0){
                                                                            echo '<ul>';
                                                                                foreach($menu->sub_menus as $sub){
                                                                                    ?>
                                                                                    <li data-jstree='{ "module_id" : <?php echo $sub->id; ?>, "selected" : false }'> <?php echo $sub->display_name; ?></li>
                                                                                    <?php
                                                                                }
                                                                            echo '</ul>';
                                                                        }
                                                                        ?>
                                                                    </li>   
                                                                    <?php
                                                                }
                                                                
                                                                echo '</ul>';
                                                            }
                                                            
                                                        ?>
                                                </div>
                                            </div>
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
                                                $options = array('inactive' => 'Inactive', 'active' => 'Active');
                                                echo form_dropdown('status', $options, set_value('status'), 'class="form-control" id="status" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("status"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/settings/manage_role'); ?>'" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <script>
        
        $('#role_modules').jstree({
            "plugins" : [ "checkbox" ],
            "core": {
                "themes":{
                    "icons":false
                }
            }
        });

        $('#role_modules').on('changed.jstree', function (e, data) {

              var i, j, r = [];

              for(i = 0, j = data.selected.length; i < j; i++) {
                  r.push(data.instance.get_node(data.selected[i]).state.module_id);
              }


              $('#event_result').val(r.join(','));

        }).jstree();

    </script>