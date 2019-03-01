var FormValidation = function() {
    var x = function() {
            var e = $("#variants_add"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    creative_type: {
                        required: !0
                    },
                    creative_type_name: {
                        required: !0
                    },
                    creative_text: {
                        required: !0
                    },
                    variant_type :{
						required: !0
						}
                    
                    
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-group");
                    i ? i.after(e) : r.after(e)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
					i.show(), r.hide()	
					add_new_creative();
					return;
                   // 
                }
            })
        },
                
                
                
    e = function() {
            var e = $("#brand_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    brand_name: {
                        minlength: 2,
                        required: !0
                    },
                    brand_slug: {
                       
                        required: !0
                    },
                    logo_image: {
                        
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-group");
                    i ? i.after(e) : r.after(e)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e.submit();
                }
            })
        },
                
      ee = function() {
            var e = $("#source_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    source_name: {
                        minlength: 2,
                        required: !0
                    },
                    value: {
                       
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-group");
                    i ? i.after(e) : r.after(e)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e.submit();
                }
            })
        },
        r = function() {
            var e = $("#footer_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    footer: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
      
       r1 = function() {
            var e = $("#disclaimer_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    disclaimer: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
                
       s = function() {
            var e = $("#script_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    to: {
                        required: !0
                    },
                    from: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
      k = function() {
            var e = $("#tracking_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    body_code: {
                        required: !0
                    },
                    head_code: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
      kk = function() {
            var e = $("#thanks_tracking_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    body_code: {
                        required: !0
                    },
                    head_code: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
     m = function() {
            var e = $("#responsive"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    campaign_name: {
                        required: !0
                    },
                                     
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide()
                    add_new_campaign();
                    return;
                }
            })
        },
                
      mm = function() {
            var e = $("#responsive_edit"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    campaign_name_edit: {
                        required: !0
                    },
                                     
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide()
                    add_new_campaign_edit();
                    return;
                }
            })
        },
     n = function() {
            var e = $("#variants_add_old"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    creative_type: {
                        required: !0
                    },
                    creative_type_name: {
                        required: !0
                    }
//                    creative_text: {
//                        required: !0
//                    }
                                     
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e.submit()
                }
            })
        },
      h = function() {
            var e = $("#text_code_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    logo_text1: {
                        required: !0
                    },
                    logo_text2: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
       l = function() {
            var e = $("#image_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    logo_image: {
                        required: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
        i = function() {
            var e = $("#form_sample_3"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
          e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
               rules: {
                    SITE_NAME: {
                        minlength: 2,
                        required: !0
                    },
                    SITE_EMAIL: {
                        required: !0,
                        email: !0
                    },
                    EMAIL_US: {
                        required: !0,
                        email: !0
                    },
                    VMFORM_HASH: {
                        required: !0
                    },
                    VM_LEAD_API: {
                        required: !0
                    },
                    VMFORM_SITEID: {
                        required: !0
                    },
                    AFFILIATED_CAMPAIGN_ID: {
                        required: !0
                    },
                    WSDL_API: {
                        required: !0
                    },
                    WSDL_API_KEYS: {
                        required: !0
                    }
                },
                messages: {
                    membership: {
                        required: "Please select a Membership type"
                    },
                    service: {
                        required: "Please select  at least 2 types of Service",
                        minlength: jQuery.validator.format("Please select  at least {0} types of Service")
                    }
                },
                errorPlacement: function(e, r) {
                    r.parents(".mt-radio-list") || r.parents(".mt-checkbox-list") ? (r.parents(".mt-radio-list")[0] && e.appendTo(r.parents(".mt-radio-list")[0]), r.parents(".mt-checkbox-list")[0] && e.appendTo(r.parents(".mt-checkbox-list")[0])) : r.parents(".mt-radio-inline") || r.parents(".mt-checkbox-inline") ? (r.parents(".mt-radio-inline")[0] && e.appendTo(r.parents(".mt-radio-inline")[0]), r.parents(".mt-checkbox-inline")[0] && e.appendTo(r.parents(".mt-checkbox-inline")[0])) : r.parent(".input-group").size() > 0 ? e.insertAfter(r.parent(".input-group")) : r.attr("data-error-container") ? e.appendTo(r.attr("data-error-container")) : e.insertAfter(r)
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
        u = function() {
            var e = $("#variants_edit"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    creative_variant_type_edit: {
                        required: !0
                    },
                    
//                    creative_text_edit: {
//                        required: !0
//                    },
                        variant_type_edit: {
                            required: !0
                        },
                        creative_type_name_edit: {
                            required: !0
                        },
                    
                    
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-group");
                    i ? i.after(e) : r.after(e)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
					i.show(), r.hide()	
					edit_new_creative();
					return;
                   // 
                }
            })
        },
        dept = function() {
            var e = $("#department_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    name: {
                        minlength: 2,
                        required: !0
                    },
                    description: {
                       
                        required: !0
                    }
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-group");
                    i ? i.after(e) : r.after(e)
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e.submit();
                }
            })
        },
   cont = function() {
            var e = $("#contact_form"),
                r = $(".alert-danger", e),
                i = $(".alert-success", e);
            e.validate({
                errorElement: "span",
                errorClass: "help-block help-block-error",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    name: {
                        required: !0
                    },
                    phone: {
                        required: !0
                    },
                    email: {
                        required: !0,
                         email: !0
                    }
                   
                },
                invalidHandler: function(e, t) {
                    i.hide(), r.show(), App.scrollTo(r, -200)
                },
                errorPlacement: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    i.removeClass("fa-check").addClass("fa-warning"), i.attr("data-original-title", e.text()).tooltip({
                        container: "body"
                    })
                },
                highlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
                },
                unhighlight: function(e) {},
                success: function(e, r) {
                    var i = $(r).parent(".input-icon").children("i");
                    $(r).closest(".form-group").removeClass("has-error").addClass("has-success"), i.removeClass("fa-warning").addClass("fa-check")
                },
                submitHandler: function(e) {
                    i.show(), r.hide(), e[0].submit()
                }
            })
        },
        t = function() {
            jQuery().wysihtml5 && $(".wysihtml5").size() > 0 && $(".wysihtml5").wysihtml5({
                stylesheets: ["../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            })
        };
    return {
        init: function() {
            t(), e(), ee(), r(),r1(), i(), k(), l(), h(), m(), n(), x(), u(),mm(), s(),kk(),cont(), dept()
        }
    }
}();
jQuery(document).ready(function() {
    FormValidation.init();
    new Clipboard('.clip-brd-btn');
});
