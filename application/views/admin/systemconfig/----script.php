 <?php $fs_db = unserialize(file_get_contents(base_url("htaccess.db"))); ?>
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
                                    <span>Manage Script</span>
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
                                            <span class="caption-subject font-dark sbold uppercase">Manage Script</span>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                    <div class="portlet-body">
                                        <!-- BEGIN FORM-->
                                        <form action="<?php echo site_url('admin/systemconfiguration/script');?>" id="script_form" method="post" class="form-horizontal">
                                            <div class="form-body">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                              <?php
                                             // pr($fs_db);
						if(isset($fs_db['from']) && count($fs_db['from']) > 0){
                                                    
							
								for($i = 0; $i < count($fs_db['from']); $i++) {
									 
                                                        if(trim($fs_db['from'][$i])) { ?>
                                                                        <div class="form-group">
                                                    <label class="control-label col-md-3">Current Url
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="from[]" id="from" value="<?php echo $fs_db['from'][$i]; ?>"/>
                                                         
                                                        <?php echo form_error("from"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Redirect To
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="to[]" id="to" value="<?php echo $fs_db['to'][$i]; ?>"/>
                                                         
                                                        <?php echo form_error("to"); ?>
                                                    </div>
                                                </div>
										
									<?php }
								} 
						}
					?>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Current Url
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input type="url" class="form-control" name="from[]" id="from" value=""/>
                                                         
                                                        <?php echo form_error("from"); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Redirect To
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4">
                                                        <input type="url" class="form-control" name="to[]" id="to" value=""/>
                                                         
                                                        <?php echo form_error("to"); ?>
                                                    </div>
                                                </div>
                                                
                                             </div>
                                              
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button name="save" type="submit" class="btn green">Submit</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                             
                                        </form>
                                        <!-- END FORM-->
                                    
                                    
                                    <!-- END VALIDATION STATES-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
