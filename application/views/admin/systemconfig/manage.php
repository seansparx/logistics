 
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
                                    <a href="<?php echo site_url('admin/dashboard');?>">Dashboard</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>System Configurations</span>
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
                                <!-- BEGIN VALIDATION STATES-->
                                <div class="portlet light portlet-fit portlet-form bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject font-dark sbold uppercase">System Configurations</span>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="portlet-body">
                                        <!-- BEGIN FORM-->
                                        <form action="<?php echo site_url('admin/systemconfiguration/saverecord');?>" id="form_sample_3" method="post" class="form-horizontal">
                                            <div class="form-body">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                <input type="hidden" name="admin_id" id="admin_id" value="<?php isset($id)?$id:null ?>">
                                                <input type="hidden" name="adminLevelId" id="adminLevelId" value="<?php isset($adminLevelId)?$adminLevelId:null ?>">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Site Name
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input type="text" name="SITE_NAME" id="SITE_NAME" data-required="1" class="form-control" value="<?php echo $SITE_NAME; ?>" /> 
                                                        <?php echo form_error("SITE_NAME"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Site
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <div class="mt-radio-list" data-error-container="#form_2_membership_error">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="SITE_ONLINE" value="1" <?php if ($SITE_ONLINE == '1') { ?> checked="checked" <?php } ?> /> Online
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="SITE_ONLINE" value="0" <?php if ($SITE_ONLINE == '0') { ?> checked="checked" <?php } ?> /> Offline
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                        <div id="form_2_membership_error"> </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Site  Email
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>
                                                            <input type="email" name="SITE_EMAIL" id="SITE_EMAIL" class="form-control" placeholder="Email Address" value="<?php echo $SITE_EMAIL; ?>"> 
                                                            <?php echo form_error("SITE_EMAIL"); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Admin Email
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-envelope"></i>
                                                            </span>
                                                             <input type="email" name="EMAIL_US" id="EMAIL_US" class="form-control" placeholder="Email Address" value="<?php echo $EMAIL_US; ?>"> 
                                                            <?php echo form_error("EMAIL_US"); ?>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">VM LEAD API&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="VM_LEAD_API" id="VM_LEAD_API" type="text" class="form-control" value="<?php echo $VM_LEAD_API; ?>" />
                                                        <?php echo form_error("VM_LEAD_API"); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">VMFORM HASH&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="VMFORM_HASH" id="VMFORM_HASH" type="text" class="form-control" value="<?php echo $VMFORM_HASH; ?>" />
                                                        <?php echo form_error("VMFORM_HASH"); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">VMFORM SITEID&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="VMFORM_SITEID" id="VMFORM_SITEID" type="text" class="form-control" value="<?php echo $VMFORM_SITEID; ?>" />
                                                        <?php echo form_error("VMFORM_SITEID"); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">VM LEAD CAMP. ID&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="AFFILIATED_CAMPAIGN_ID" id="AFFILIATED_CAMPAIGN_ID" type="text" class="form-control" value="<?php echo $AFFILIATED_CAMPAIGN_ID; ?>" />
                                                        <?php echo form_error("AFFILIATED_CAMPAIGN_ID"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">UPLEAD API URL&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="WSDL_API" id="WSDL_API" type="text" class="form-control" value="<?php echo $WSDL_API; ?>" />
                                                        <?php echo form_error("WSDL_API"); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">UPLEAD API KEY&nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="WSDL_API_KEYS" id="WSDL_API_KEYS" type="text" class="form-control" value="<?php echo $WSDL_API_KEYS; ?>" />
                                                        <?php echo form_error("WSDL_API_KEYS"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">TARGET DB &nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="TARGET_DB" id="TARGET_DB" type="text" class="form-control" value="<?php echo $TARGET_DB; ?>" />
                                                        <?php echo form_error("TARGET_DB"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">TCH USER &nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="TCH_USER" id="TCH_USER" type="text" class="form-control" value="<?php echo $TCH_USER; ?>" />
                                                        <?php echo form_error("TCH_USER"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">TCH PASSWORD &nbsp;&nbsp;</label>
                                                    <div class="col-md-4">
                                                        <input name="TCH_PASS" id="TCH_PASS" type="text" class="form-control" value="<?php echo $TCH_PASS; ?>" />
                                                        <?php echo form_error("TCH_PASS"); ?>
                                                    </div>
                                                </div>
                                                
                                                
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green">Submit</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END FORM-->
                                    </div>
                                    
                                    <!-- END VALIDATION STATES-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
