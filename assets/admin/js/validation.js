function hrefBack() {
	history.go(-1);	
}

function displayCursor()
{
	document.frmLogin.userName.focus();
}
function show_all(sitename)
{
	window.location.href= sitename;
}	

function validationLogin()
{
	if(document.getElementById('userName').value=="")
	{
		alert("Please enter username");
		document.getElementById('userName').focus();
		return false;
	}
		
	if(document.getElementById('userPassword').value=="")
	{
		alert("Please enter valid password");
		document.getElementById('userPassword').focus();
		return false;
	}
}


function validationEmail() 
{
    document.getElementById('email').innerHTML = '';
    var email = $("#email").val();

    var reEmail=/^[0-9a-zA-Z_\.-]+\@[0-9a-zA-Z_\.-]+\.[0-9a-zA-Z_\.-]+$/;
		if(!reEmail.test(email)) {
                    jQuery('#error_msg').html('Please enter correct email id');
                    return false;
		}
		return true;
}


function validationAddAdmin()
{ 
	document.getElementById('chk_userName').innerHTML = '';
	document.getElementById('chk_userEmail').innerHTML = '';
	document.getElementById('chk_password').innerHTML = '';
	
	if(document.frmUser.userName.value=="")
	{
		document.getElementById('chk_userName').innerHTML = 'Please enter username';
		document.frmUser.userName.focus();
		return false;
	} 

	if(document.frmUser.userEmail.value=="")
	{
		document.getElementById('chk_userEmail').innerHTML = 'Please enter email Id';
		document.frmUser.userEmail.focus();
		return false;
	}

	var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	str = document.getElementById('userEmail').value;
	if(!str.match(emailRegEx)){
		document.getElementById('chk_userEmail').innerHTML = 'Please enter valid email Id';
		document.frmUser.userEmail.focus();
		return false;		
	}
	
	if(document.frmUser.password.value=="")
	{
		document.getElementById('chk_password').innerHTML = 'Please enter password';
		document.frmUser.password.focus();
		return false;
	}
		
	if(document.frmUser.password.value.length<6)
	{
		document.getElementById('chk_password').innerHTML = "It should atleast 6 character";
		document.frmUser.password.select();
		document.frmUser.password.focus();
		return false;
	}
}

function validationEditAdmin()
{ 
	document.getElementById('chk_userName').innerHTML = '';
	document.getElementById('chk_userEmail').innerHTML = '';
	document.getElementById('chk_password').innerHTML = '';
	
	if(document.frmUser.userName.value=="")
	{
		document.getElementById('chk_userName').innerHTML = 'Please enter username';
		document.frmUser.userName.focus();
		return false;
	} 

	if(document.frmUser.userEmail.value=="")
	{
		document.getElementById('chk_userEmail').innerHTML = 'Please enter email Id';
		document.frmUser.userEmail.focus();
		return false;
	}

	var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	str = document.getElementById('userEmail').value;
	if(!str.match(emailRegEx)){
		document.getElementById('chk_userEmail').innerHTML = 'Please enter valid email Id';
		document.frmUser.userEmail.focus();
		return false;		
	}
			
	if(document.frmUser.password.value!="" && document.frmUser.password.value.length<6)
	{
		document.getElementById('chk_password').innerHTML = "It should atleast 6 character";
		document.frmUser.password.select();
		document.frmUser.password.focus();
		return false;
	}
}



function validationCategory()
{
	if(document.getElementById('categoryName').value=="")
	{
		document.getElementById('chk_categoryName').innerHTML = "Please enter category name";
		document.getElementById('categoryName').focus();
		return false;
	}		
}


var form_validation = function () {

        // employee form validation
       

            var employee_form = function() {
        
            var form2 = $('#employee_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
              messages: {
                    emp_name: {
                        required: jQuery.validator.format("Please enter employee name.")
                    },
                    state: {
                        required: jQuery.validator.format("Please select state.")
                    },
                    contract: {
                        required: jQuery.validator.format("Please select contract.")
                    },
                    category: {
                        required: jQuery.validator.format("Please select category.")
                    },
                    status: {
                        required: jQuery.validator.format("Please select status.")
                    }
                },
                rules: {
                    emp_name: {
                        minlength: 2,
                        required: true
                    },
                    state: {
                        required: true
                    },
                    contract: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    
                    if (cont) {
                        cont.after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }
        
        
     



           var vehicle_form = function() {
        
            var form2 = $('#vehicle_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                 messages: {
                    regn_number: {
                        required: jQuery.validator.format("Please enter registration number.")
                    },
                    model: {
                        required: jQuery.validator.format("Please enter model.")
                    },
                    status: {
                        required: jQuery.validator.format("Please select status.")
                    }
                },
                rules: {
                    regn_number: {
                        minlength: 2,
                        required: true
                    },
                    model: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    
                    if (cont) {
                        cont.after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }
        
        
        
        // project form validation
        var project_form = function() {
        
            var form2 = $('#project_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    
                    code: {
                        required: jQuery.validator.format("Enter contract code.")
                    },
                    cust_name: {
                        required: jQuery.validator.format("Enter customer name.")
                    },
                    description: {
                        required: jQuery.validator.format("Enter contract description.")
                    },
                    status: {
                        required: jQuery.validator.format("Please select status.")
                    }
                },
                rules: {
                    
                    code: {
                        minlength: 2,
                        maxlength: 50,
                        required: true
                    },
                    cust_name: {
                        minlength: 2,
                        maxlength: 50,
                        required: true
                    },
                    description:{
                        minlength: 10,
                        maxlength: 500,
                        required: true
                    },
                    status: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    
                    if (cont) {
                        cont.after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }


        // department form validation
      
            var department_form = function() {
        
            var form2 = $('#department_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
              
                  messages: {
                    name: {
                        required: jQuery.validator.format("Please enter department name.")
                    },
                    status: {
                        required: jQuery.validator.format("Please select status.")
                    }
                },
                rules: {
                    name: {
                        minlength: 2,
                        required: true
                    },
                    status: {
                        required: true
                    }
                },



                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    
                    if (cont) {
                        cont.after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }
        
        
        // administrator form validation
        var admin_form = function() {
        
            var form2 = $('#admin_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    role: {
                        required: jQuery.validator.format("Please select role.")
                    },
                    first_name: {
                        required: jQuery.validator.format("Please enter first name.")
                    },
                    last_name: {
                        required: jQuery.validator.format("Please enter last name.")
                    },
                    username: {
                        required: jQuery.validator.format("Please enter username.")
                    },
                    email_id: {
                        required: jQuery.validator.format("Please enter email address.")
                    },
                    new_password: {
                        required: jQuery.validator.format("Please enter password.")
                    },
                    confirm_password: {
                        required: jQuery.validator.format("Please re-type password."),
                        equalTo: jQuery.validator.format("Confirm password does not match.")
                    },
                    status: {
                        required: jQuery.validator.format("Please select status.")
                    }
                },
                rules: {
                    role: {
                        required: true
                    },
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    username: {
                        required: true,
                        minlength: 5
                    },
                    email_id: {
                        required: true,
                        email:true
                    },
                    new_password: {
                        required: true,
                        minlength: 8
                        
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo:"#new_password"
                    },
                    status: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    if (cont) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }
        
        
        // my profile info form validation
        var profile_info_form = function() {
        
            var form2 = $('#form_my_profile');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    first_name: {
                        required: jQuery.validator.format("Please enter your first name.")
                    },
                    last_name: {
                        required: jQuery.validator.format("Please enter your last name.")
                    },
                    email_id: {
                        required: jQuery.validator.format("Please enter your email address.")
                    }
                },
                rules: {
                    first_name: {
                        minlength: 2,
                        required: true
                    },
                    last_name: {
                        minlength: 2,
                        required: true
                    },
                    mobile: {
                        digits: true
                    },
                    email_id: {
                        required: true,
                        email:true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    //error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element);
                    if (cont) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });


        }
        

        // my profile info form validation
        var profile_password_form = function() {

            var form2 = $('#password_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {

                    current_password: {
                        required: jQuery.validator.format("Please enter your old password.")
                    },
                    new_password: {
                        required: jQuery.validator.format("Please enter your new password.")
                    },
                    confirm_password: {
                        required: jQuery.validator.format("Please re-enter your new password."),
                        equalTo: jQuery.validator.format("Password does not match.")
                    }
                },
                rules: {

                    current_password: {
                        minlength: 8,
                        maxlength: 30,
                        required: true
                    },
                    new_password: {
                        minlength: 8,
                        maxlength: 30,
                        required: true
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        maxlength: 30,
                        equalTo: "#new_password"
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    //error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element);
                    if (cont) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });
        }


        // my profile info form validation
        var create_service_form = function() {

            var form2 = $('#create_service_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    
                    
                    start_date: {
                        required: jQuery.validator.format("Select initial date.")
                    },
                    end_date: {
                        required: jQuery.validator.format("Select ending date.")
                    },
                    project: {
                        required: jQuery.validator.format("Select project.")
                    },
                    "employees[]": {
                        required: jQuery.validator.format("Select employee.")
                    },
                  
                    department: {
                        required: jQuery.validator.format("Select department.")
                    },
                    start_time: {
                        required: jQuery.validator.format("select initial time.")
                    },
                    end_time: {
                        required: jQuery.validator.format("Select ending time.")
                    }
                },
                rules: {

                 
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    "employees[]": {
                        required: true
                    },
                   
                    department: {
                        required: true
                    },
                    start_time: {
                        required: true
                    },
                    end_time: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    //error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element);
                    if (cont) {
                        cont.parent().after(error);
                    } else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });
        
        }



	// timesheet form validation
	

        var create_timesheet_form = function() {
			
            var form2 = $('#create_timesheet_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    entry_date: {
                        required: jQuery.validator.format("Enter entry date")
                    },
                      employees: {
                        required: jQuery.validator.format("PLease select employee name")
                    },
                    
                     start_time: {
                        required: jQuery.validator.format("Enter start time")
                    },
                     end_time: {
                        required: jQuery.validator.format("Enter end time")
                    },
                    extra_hour:{
                        required: jQuery.validator.format("Enter extra hours")
                    }
                    
                },
                rules: {

                    entry_date: {
                        required: true
                    },
                      start_time: {
                        required: true,
                        endtime_check : false
                    },
                       employees: {
                        required: true
                    },
                        end_time: {
                        required: true,
                        starttime_check:false
                    },
                    extra_hour:{
                        maxlength: 10,
                        required:true
                    }
                   
                   
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element);
                    
                    if(cont.attr('id') == 'extra_hour'){
                        cont.parent().next().next().children('label').html(error);
                    }
                    else if (cont) {
                        cont.parent().after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });
            
        }


        var leave_form = function() {
        
            var form2 = $('#leave_form, #checkup_form');
            var error2 = $('.alert-danger', form2);
            var success2 = $('.alert-success', form2);

            form2.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    start_date: {
                        required: jQuery.validator.format("Select Start-Date.")
                    },
                    end_date: {
                        required: jQuery.validator.format("Select End-Date.")
                    },
                    reason: {
                        required: jQuery.validator.format("Enter Reason.")
                    }
                },
                rules: {
                    start_date: {
                        required: true,
                        maxlength: 100
                    },
                    end_date: {
                        required: true,
                        maxlength: 100
                    },
                    reason:{
                        maxlength: 1000,
                        required: true
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-icon');
                    
                    if (cont) {
                        cont.after(error);
                    } 
                    else {
                        element.after(error);
                    }
                },
                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },
                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function (form) {
                    //success2.show();
                    error2.hide();
                    form.submit();
                }
            });
        }
        


            return {
                //main function to initiate the module
                init: function () {
                    
                    employee_form();
                    
                    vehicle_form();    
                    
                    project_form();
                    
                    department_form();
                    
                    admin_form();
                    
                    profile_info_form();
                    
                    profile_password_form();
                    
                    create_service_form();
                    
                    create_timesheet_form();
                    
                    leave_form();
                }

            };

        }();

        jQuery(document).ready(function () {
			
            form_validation.init();
            
        });
        
        
    $("#extra_hr_btn").click(function() {

        var extra_hour=$("#extra_hour").val();

        if(extra_hour==""){    
            $("#extra_hour").val('00:00:00');
        }

       if ($("#create_timesheet_form").valid()) {

          
                calculate_extra_hr(); 
           
           
                      
       }
   });
