
<link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />

<script src="<?php echo base_url() ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/pages/scripts/profile.min.js" type="text/javascript"></script>

<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> My Profile | Account
            <small>user account page</small>
        </h1>
        <!-- END PAGE TITLE-->




                <?php 

                
      
                    if ($this->session->flashdata('success') != "") { 
                        ?>
                 
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button> <?php echo $this->session->flashdata('success'); ?> 
                        </div>
                        <?php
                    }
                    if ($this->session->flashdata('error') != "") { 
                        ?>
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button> <?php echo $this->session->flashdata('error'); ?> 
                        </div>
                        <?php
                    }

                    //$active_tab = $this->session->flashdata('active_tab');
                ?>

        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet ">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <?php                                                             
                                $src = (trim($user_data->userImage) != '') ? base_url('uploads/users/'.$user_data->userImage) : base_url('assets/pages/media/profile/avatar.png'); 
                            ?>
                            <img src="<?php echo $src; ?>" class="img-responsive" alt=""> </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> <?php echo ucwords($user_data->first_name.' '.$user_data->last_name); ?> </div>
                            <div class="profile-usertitle-job"> <?php echo ucwords($user_data->role_title); ?> </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li>
                                    <a href="<?php echo site_url('admin/adminarea');?>">
                                        <i class="icon-home"></i> Dashboard </a>
                                </li>
                                <li class="active">
                                   <a href="<?php echo site_url('admin/settings/manage_users');?>">
                                        <i class="icon-settings"></i> Account Settings </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                    <!-- PORTLET MAIN -->
                    <div class="portlet light ">
                        <!-- STAT -->
                        <div class="row list-separated profile-stat">
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> <?php echo $total_projects; ?> </div>
                                <div class="uppercase profile-stat-text"> Projects </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> <?php echo $total_services; ?> </div>
                                <div class="uppercase profile-stat-text"> Services </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <div class="uppercase profile-stat-title"> <?php echo $total_vehicles; ?> </div>
                                <div class="uppercase profile-stat-text"> Vehicles </div>
                            </div>
                        </div>
                        <!-- END STAT -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="<?php echo (($active_tab == 1) ? 'active' : ''); ?>">
                                            <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                        </li>
                                        <li class="<?php echo (($active_tab == 2) ? 'active' : ''); ?>">
                                            <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                        </li>
                                        <li class="<?php echo (($active_tab == 3) ? 'active' : ''); ?>">
                                            <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        
                                        <div class="tab-pane <?php echo (($active_tab == 1) ? 'active' : ''); ?> col-md-10" id="tab_1_1">
                                            
                                            <?php echo form_open('', array("name" => "form_my_profile", "id" => "form_my_profile")); ?>
                                                <div class="form-group">
                                                    <label class="control-label">First Name<span class="required"> * </span></label>
                                                    <input type="text" placeholder="Enter your first name" name="first_name" id="first_name" value="<?php echo $user_data->first_name; ?>" class="form-control" /> 
                                                    <?php echo form_error('first_name'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Last Name<span class="required"> * </span></label>
                                                    <input type="text" placeholder="Enter your last name" name="last_name" id="last_name" value="<?php echo $user_data->last_name; ?>" class="form-control" /> 
                                                    <?php echo form_error('last_name'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Number</label>
                                                    <input type="text" placeholder="Enter your mobile number" name="mobile" id="mobile" value="<?php echo $user_data->mobile; ?>" class="form-control" /> 
                                                    <?php echo form_error('mobile'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Email Address<span class="required"> * </span></label>
                                                    <input type="text" placeholder="Enter your email address" name="email_id" id="email_id" value="<?php echo $user_data->emailId; ?>" class="form-control" /> 
                                                    <?php echo form_error('email_id'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">About</label>
                                                    <textarea class="form-control" rows="5" name="about" id="about" placeholder="Describe yourself"><?php echo $user_data->about; ?></textarea>
                                                    <?php echo form_error('about'); ?>
                                                </div>

                                                <div class="margiv-top-10">
                                                    <input type="submit" class="btn green" name="btn_info" value="Save Changes"/>
                                                    <a href="<?php echo site_url();?>" class="btn default"> Cancel </a>
                                                </div>
                                            <?php echo form_close(); ?>
                                            
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane <?php echo (($active_tab == 2) ? 'active' : ''); ?>" id="tab_1_2">
                                                <?php echo form_open_multipart('', array("name" => "avatar_form", "id" => "avatar_form")); ?>
                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                            <?php                                                             
                                                                $src = (trim($user_data->userImage) != '') ? base_url('uploads/users/'.$user_data->userImage) : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'; 
                                                            ?>
                                                            <img src="<?php echo $src; ?>" alt="" /> 
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="file" id="image_file" name="image_file" accept="image/*">
                                                            </span>
                                                            <a href="javascript:void(0);" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix margin-top-10">
                                                        <span class="label label-danger">NOTE! </span>
                                                        <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                    </div>
                                                </div>
                                                <div class="margin-top-10">
                                                    <input type="submit" name="btn_avatar" value="Submit" class="btn green"/>
                                                </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                        <!-- CHANGE PASSWORD TAB -->
                                        <div class="tab-pane <?php echo (($active_tab == 3) ? 'active' : ''); ?>" id="tab_1_3">
                                            <?php echo form_open('', array("name" => "password_form", "id" => "password_form")); ?>
                                                <div class="form-group">
                                                    <label class="control-label">Current Password<span class="required"> * </span></label>
                                                    <input type="password" name="current_password" id="current_password" class="form-control" /> 
                                                    <?php echo form_error('current_password'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">New Password<span class="required"> * </span></label>
                                                    <input type="password" name="new_password" id="new_password" class="form-control" /> 
                                                    <?php echo form_error('new_password'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Re-type New Password<span class="required"> * </span></label>
                                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" /> 
                                                    <?php echo form_error('confirm_password'); ?>
                                                </div>
                                                <div class="margin-top-10">
                                                    <input type="submit" name="btn_password" value="Change Password" class="btn green"/>
                                                </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>