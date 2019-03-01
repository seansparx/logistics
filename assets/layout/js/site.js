(function($) {

	var $windowWidth = $(window).width();
        
    $('.form-float').click(function(event) {
		
         if ($(event.target).attr('class') != 'dsclmr_modal')
        {
        event.stopPropagation()
        $('.wrapper').addClass('overlay-form');
        }
                

	});
	$(".wrapper").on('click', function(e) {
		$('.wrapper').removeClass('overlay-form');
	});

	$(".long-btn").click(function(event) {

		event.stopPropagation()
		$('.wrapper').addClass('overlay-form');
		$("html,body").animate({
			scrollTop : $('.form-float').offset().top
		}, 1000);

	});
	$('.dob-inital').click(function() {

		$(this).hide();
		$('.dob-visible .dob-wrap').show();
		 $('.date-select div.styledSelect').trigger('click').addClass('active');
		

	});
	$('#dob-daySelectBoxItOptions li').on('click', function() {
		$('#dob-daySelectBoxItOptions').hide();
	})

	$(".mobile-scroll").click(function(event) {
		if ($windowWidth < 767) {
			event.stopPropagation()
			$('.wrapper').addClass('overlay-form');
			$("html,body").animate({
				scrollTop : $('.form-float').offset().top
			}, 1000);
		}
	});

	$("#submit-btn").click(function(e) {
                add_missingLead();
		e.preventDefault();
		$('.dob-inital').hide();
		$('.error').hide();
		//validdob();
        if (validateform() && validdob())
        {
            lead_sumit();
           // $('#formdata').submit();
        }

	});
	$('#submit-btn-2').click(function(e) {
		e.preventDefault();
                add_missingLead();
		$('.error').hide();
		if(validateform())
		{
		lead_sumit();	
                //$('#formdata').submit();
			}
	});

	$('.free-quote').click(function(e) {
		e.preventDefault();

		$('.error').hide();
		var result = true;
		result = validdob1();

		if (result == true) {
			

			$('.banner-6').addClass('first-step')
			$('.cong-wrap').slideDown(500);
		
			if ($(window).width() > 768) 
			{	
				
				$("html,body").animate({
					scrollTop : $('.form-float').offset().top
				}, 1000);
			} 
			else if ($(window).width() <= 768) {
				$("html,body").animate({
					scrollTop : $('.form-float1').offset().top
				}, 1000);
			}

		}

	});

	$('#free-quote-8').click(function(e) {
		e.preventDefault();

		$('.error').hide();
		var result = true;
		result = validdob1();
		
		if (result == true) {
			$('.banner-8').addClass('banner-8-open');
			setTimeout(function() {
				$('.banner-8').removeClass('banner-8-open');
				$('.banner-8-wrap').addClass('banner-8-wrap-open');
				$('.cong-8').addClass('wide-box');
				$("html,body").animate({
					scrollTop : $('.form-float1').offset().top
				}, 1000);

			}, 2000);

		}

	});
        
        $('#submit_contact').click(function(e) {
		e.preventDefault();
		$('.error').hide();
		if(contactform())
		{
		contact_submit();	
                //$('#formdata').submit();
			}
	});
        
        function contactform(){
                var returnValue = true;
		var name = $('#cont_name').val();
                var phone = $('#cont_phone').val();
                var email = $('#cont_email').val();
                var message = $('#cont_message').val();
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (name == "") {
			$('#cont_name').after('<span class="error"> Enter name </span>');
			returnValue = false;
		}
                if (phone == "") {
			$('#cont_phone').after('<span class="error"> Enter phone </span>');
			returnValue = false;
		}
                if (email == "") {
			$('#cont_email').after('<span class="error"> Enter email </span>');
			returnValue = false;
		}
                if (!emailReg.test(email)) {
			$('#cont_email').after('<span class="error">Please enter a valid email address </span>');
			returnValue = false;
		}
                if (message == "") {
			$('#cont_message').after('<span class="error"> Enter Message </span>');
			returnValue = false;
		}
                return returnValue;
        }

	function validdob() {
		var returnValue = true;
		var dobday = $('#dob-day').val();

		var dobmonth = $('#dob-month').val();

		var dobyear = $('#dob-year').val();
                var today = new Date();
                var years = today.getFullYear();
                var year = years - dobyear;
		var smoke = $('#smoke-select').val();
               
		if (dobday == "") {
			$('.date-select .select').after('<span class="error"> Date select </span>');
			
			returnValue = false;
		}
		if (dobmonth == "") {
			$('.month-select .select').after('<span class="error"> Month select </span>');
			returnValue = false;
		}
		if (dobyear == "") {
			$('.year-select .select').after('<span class="error">Year select </span>');
			returnValue = false;
		}
                if (year < 18) {
			$('.year-select .select').after('<span class="error">Must be aged between 18 and 80 </span>');
			returnValue = false;
		}
		if (smoke == "") {
			$('.smoke-icon .select').after('<span class="error">Do you smoke? </span>');
			returnValue = false;
		}
		return returnValue;
	}

	function validdob1() {
		var returnValue = true;
		var dobday = $('#dob-day').val();

		var dobmonth = $('#dob-month').val();

		var dobyear = $('#dob-year').val();
                var today = new Date();
                var years = today.getFullYear();
                var year = years - dobyear;
                
		var smoke = $('#smoke-select').val();
                var required = $('#required').val();
                var required1 = $("#required :selected").text();
                $('#required1').val(required);
                $('#cover_required').html(required1);
                $('#do_smoke').html(smoke);
                
		if (dobday == "") {
			$('#dob-day').after('<span class="error"> Date select </span>');
			returnValue = false;
		}
		if (dobmonth == "") {
			$('#dob-month').after('<span class="error"> Month select </span>');
			returnValue = false;
		}
		if (dobyear == "") {
			$('#dob-year').after('<span class="error">Year select </span>');
			returnValue = false;
		}
                if (year < 18) {
			$('#dob-year').after('<span class="error">Must be aged between 18 and 80 </span>');
			returnValue = false;
		}
		if (dobyear != "" && dobmonth != "" && dobday != "") {
			$('#quote_bday_data').html(dobday+"."+dobmonth+"."+dobyear);
                        $('#your_dob').html(dobday+"."+dobmonth+"."+dobyear);
                        $('#dob-day1').val(dobday);
                        $('#dob-month1').val(dobmonth);
                        $('#dob-year1').val(dobyear);
		}
                
		if (smoke == "") {
			$('#smoke-select').after('<span class="error">Do you smoke? </span>');
			returnValue = false;
		}
                if(smoke !=""){
                    $('#smoke-select1').val(smoke);
                }
		
		return returnValue;
	}

	function validateform() {

		var nameReg = /^[A-Za-z ]+$/;
		var numberReg = /^[0-9]+$/;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
               // var ukReg = /^(\+?44|0){1}(1|2|7){1}\d{2}[\s-]?\d{3}[\s-]?\d{3,4}$/;
                var ukpostReg = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;

		var firstname = $('#first-name').val();

		var lastname = $('#last-name').val();

		var phoneno = $.trim($('#phone-no').val()).replace(/-|\s/g,"");
               //alert(phoneno.length);
		var email = $('#email').val();
		var address = $('#address').val();
		var postcode = $('#postcode').val();
		var returnValue = true;

		if (firstname == "") {
			$('#first-name').after('<span class="error"> Please enter your first name </span>');
			returnValue = false;

		} else if (!nameReg.test(firstname)) {
			$('#first-name').after('<span class="error"> Letters only  </span>');
			returnValue = false;

		}
		if (lastname == "") {
			$('#last-name').after('<span class="error"> Please enter your last name </span>');
			returnValue = false;

		} else if (!nameReg.test(lastname)) {
			$('#last-name').after('<span class="error"> Letters only  </span>');
			returnValue = false;

		}

		if (phoneno == "") {
			$('#phone-no').after('<span class="error">Please enter your Phone No. </span>');
			returnValue = false;

		} else if (!numberReg.test(phoneno)) {
			$('#phone-no').after('<span class="error">Numbers Only </span>');
			returnValue = false;
		}
//                else if (!ukReg.test(phoneno)) {
//			$('#phone-no').after('<span class="error">Please enter a valid UK Phone No. </span>');
//			returnValue = false;
//		}

                else if (phoneno.length < '10') {
                    //alert("ram");
			$('#phone-no').after('<span class="error">Please enter a valid Phone No. </span>');
			returnValue = false;
		}
		if (email == "") {
			$('#email').after('<span class="error">Please enter your Email </span>');
			returnValue = false;

		}
		if (!emailReg.test(email)) {
			$('#email').after('<span class="error">Please enter a valid email address </span>');
			returnValue = false;
		}
		if (address == "") {
			$('#address').after('<span class="error">Please enter your address </span>');
			returnValue = false;

		}
		if (postcode == "") {
			$('#postcode').after('<span class="error">Please enter your postcode </span>');
			returnValue = false;
                }else if (!ukpostReg.test(postcode)) {
			$('#postcode').after('<span class="error">Please enter a valid UK postcode </span>');
			returnValue = false;
		}
		
		return  returnValue;

	}


	

})(jQuery);

function lead_sumit()
{
    $.ajax({
                type: "POST",
                url: "http://10.0.4.4/CSS5515/leaddesign/form1Data",
                //url: "api.php?call=getfunction",
                data: $('#formdata').serialize(),
                cache: false,
                beforeSend: function() {
                    //alert('ss')
                    $('.main-overlay').css('display','block');
                            },
                success: function (result) {
                   
                    //alert(result);
                    var obj = $.parseJSON(result);
                    //alert(obj.form_data_id);
                    if ($.trim(obj.success) == 'N')
                    {
                        if ($.trim(obj.err_type) == 'first_name') {
                            $('#first-name').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                         if ($.trim(obj.err_type) == 'last_name') {
                            $('#last-name').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                        if ($.trim(obj.err_type) == 'address') {
                            $('#address').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                        if ($.trim(obj.err_type) == 'mobile') {
                            $('#phone-no').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                        if ($.trim(obj.err_type) == 'email') {
                            $('#email').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                         if ($.trim(obj.err_type) == 'postcode') {
                            $('#postcode').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                        if ($.trim(obj.err_type) == 'dob') {
                            $('#dob-day').after('<span class="error">' + obj.err_msg + '</span>');
                        }
                    }
                    else {
                       
                        window.location.href = "http://10.0.4.4/CSS5515/leaddesign/thanks/"+obj.form_data_id;
                    }
                     $('.main-overlay').css('display','none');



                }
            });
}

function contact_submit()
{
    $.ajax({
                type: "POST",
                url: "http://10.0.4.4/CSS5515/leaddesign/contactForm",
                //url: "api.php?call=getfunction",
                data: $('#contact_form').serialize(),
                cache: false,
                success: function (result) {
                   location.reload(); 
                }
            });
}

