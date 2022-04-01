$(function(){
    jQuery.validator.setDefaults({
        highlight: function(element) {
            if($(element).closest('.input-group').length) {
				$(element).closest('.input-group').addClass('has-error');
            } 
            else {
                $(element).parent().addClass('has-error');
            }            
        },
        unhighlight: function(element) {
            if($(element).closest('.input-group').length) {
				$(element).closest('.input-group').removeClass('has-error');
            } 
            else {
                $(element).parent().removeClass('has-error');
            }    
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.closest('.input-group').length) {
				if(element.closest('.input-group').parent().find('.help-block').length)
				{
					element.closest('.input-group').parent().find('.help-block').remove();
				}
                error.insertAfter(element.closest('.input-group'));
            } 
            else {
				if(element.parent('.has-error').find('.help-block').length)
				{
					element.parent('.has-error').find('.help-block').remove();
				}
				error.insertAfter(element);
            }
        },
        onfocusout: false
    });

    $.validator.addMethod('validatePostelcode', function(value, element) {
    var patt = /\b((?:(?:gir)|(?:[a-pr-uwyz])(?:(?:[0-9](?:[a-hjkpstuw]|[0-9])?)|(?:[a-hk-y][0-9](?:[0-9]|[abehmnprv-y])?)))) ?([0-9][abd-hjlnp-uw-z]{2})\b/i;
  
    return res = patt.test(value); 
    },'Invalid postel code.');   

    function scrollOnError(validator) {
        $('html, body').animate({
            scrollTop: $(validator.errorList[0].element).offset().top - $('#header').height() - 150
        }, 500, function() {
            validator.errorList[0].element.focus();
        });
    };


    jQuery.validator.addMethod("email", function(value, element) {
        return this.optional( element ) || ( /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test( value ) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test( value ) );
    }, js_email);

    $.validator.addMethod('filesize', function(value, element, param) {
        // param = size (en bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param)
    });

    $.validator.addMethod('phoneUK', function(phone_number, element) {
        return this.optional(element) || phone_number.length > 9 &&
        phone_number.match(/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/);
        }, 'Please specify a valid phone number'
    );

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, 'Only alphabets are allowed.');
    
    $.validator.addMethod("reasonSpace", function(value, element) {
        return this.optional(element) || value == value.match(/^[^-\s][a-zA-Z0-9_\s-]+$/);
    }, 'Please Enter Valid Reason.');

    $.validator.addMethod("allowNumericWithDecimal", function(value, element) {
        return this.optional(element) || value == value.match(/^\+?[0-9]*\.?[0-9]+$/);
    }, 'Please Enter only digits.');
    
    $("#admin_login_form").validate({
        rules: {
            email: {
                required: true,
                email:true      
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "Email is required.",
                email:js_email
            },
            password: {
                required: "Password is required.",
            }
        }
    });

    $("#admin_forgot_form").validate({
        rules: {
            email: {
                required: true,
                email:true
            },
        },
        messages: {
            email: {
                required: "Email is required.",
                email:js_email
            },
        }
    });

    $('#admin_reset_password').validate({
        rules: {
            email: {
                required: true,
                email:true
            },
            password: {
                required: true,
                minlength:6,
                maxlength:20,
            },
            password_confirmation:{
                equalTo: "#password",
            },
        },
        messages: {
            email: {
                required: "Email is required.",
                email:js_email
            },
            password:{
                required: "Password is required.",
                minlength:js_pass_min.replace("%s",6),
                maxlength:js_pass_max.replace("%s",20),
            },
            password_confirmation: {
                equalTo:js_pass_mis_match
            },
        },
    });
    $("#frm_admin_add").validate({
        rules: {
            fname:{
                required: true,
                maxlength: 50,
                alpha: true,
            },
            lname:{
                required: true,
                maxlength: 50,
                alpha:true
            },
            contact:{
                number:true,
                minlength: 6,
                maxlength: 15,
            },
            email: {
                required: true,
                email:true,
                maxlength: 100,
            },
            password:{
                required: true,
                minlength:6,
                maxlength:20,
            },
            confirm_password:{
                equalTo: "#password",
            },
            file:{
                 filesize: 5242880, //give size in bytes
            }
        },
        messages: {
            fname: {
                required: "First name is required.",
                maxlength:js_pass_max.replace("%s",50),
            },
            lname: {
                required: "Last name is required.",
                maxlength:js_pass_max.replace("%s",50),
            },
            contact:{
                number:js_number,
                minlength:js_pass_min.replace("%s",6),
                maxlength:js_pass_max.replace("%s",15),
            },
            email: {
                required: "Email is required.",
                email:js_email,
                maxlength:js_pass_max.replace("%s",100),
            },
            password: {
                required: "Password is required.",
                minlength:js_pass_min.replace("%s",6),
                maxlength:js_pass_max.replace("%s",20),
            },
            confirm_password: {
                equalTo:js_pass_mis_match
            },
            file:{
                filesize: js_filesize.replace("%s",5),//give size in mb
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });

    $("#frm_admin_edit").validate({
        rules: {
            fname:{
                required: true,
                maxlength: 50,
                alpha : true,
            },
            lname:{
                required: true,
                maxlength: 50,
                alpha: true
            },
            contact:{
                number:true,
                minlength: 6,
                maxlength: 15,
            },
            password:{
                minlength:6,
                maxlength:20,
            },
            confirm_password:{
                equalTo: "#password",
            },
            file:{
                filesize: 5242880, //give size in bytes
            }
        },
        messages: {
            fname: {
                required: "First name is required.",
                maxlength:js_pass_max.replace("%s",50),
            },
            lname: {
                required: "last name is required.",
                maxlength:js_pass_max.replace("%s",50),
            },
            contact:{
                number:js_number,
                minlength:js_pass_min.replace("%s",6),
                maxlength:js_pass_max.replace("%s",15),
            },
            password: {
                minlength:js_pass_min.replace("%s",6),
                maxlength:js_pass_max.replace("%s",20),
            },
            confirm_password: {
                equalTo:js_pass_mis_match
            },
            file:{
                filesize: js_filesize.replace("%s",5),//give size in mb
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });

    $("#frm_mail_setting_add").validate({
        rules: {
            title: {
                required: true,
                maxlength:100
            },
            slug: {
                required: true,
                maxlength:100
            },
            module: {
                required: true,
                maxlength:100
            },
            from_email: {
                required: true,
                maxlength:100,
                email:true
            },
            to_email: {
                maxlength:100
            },
            from_text: {
                required: true,
                maxlength:100
            },
            subject: {
                required: true,
                maxlength:100
            },
            comment: {
                maxlength:500
            },
            mail_content : {
                required : true
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            slug: {
                required: "Slug is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            module: {
                required: "Module is required.",
                maxlength:js_max_lengths.replace("%s",50),
            },
            from_email: {
                required: "From email is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            to_email: {
                maxlength:js_max_lengths.replace("%s",100),
            },
            from_text: {
                required: "From text is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            subject: {
                required: "Subject is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            comment: {
                maxlength:js_max_lengths.replace("%s",500),
            },
            mail_content : {
                required : "Mail Content is required",
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });
    $("#frm_mail_setting_edit").validate({
        rules: {
            title: {
                required: true,
                maxlength:100
            },
            module: {
                required: true,
                maxlength:50
            },
            from_email: {
                required: true,
                maxlength:100,
                email:true
            },
            to_email: {
                maxlength:100
            },
            from_text: {
                required: true,
                maxlength:100
            },
            subject: {
                required: true,
                maxlength:100
            },
            comment: {
                maxlength:500
            },
            mail_content:{
                required: true,
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            module: {
                required: "Module is required.",
                maxlength:js_max_lengths.replace("%s",50),
            },
            from_email: {
                required: "From email is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            to_email: {
                maxlength:js_max_lengths.replace("%s",100),
            },
            from_text: {
                required: "From text is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            subject: {
                required: "Subject is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            comment: {
                maxlength:js_max_lengths.replace("%s",500),
            },
            mail_content:{
                required: "Mail Content is required",
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });
    $("#frm_webpages").validate({
        rules: {
            heading:{
                required: true,
                minlength:3,
                maxlength:200,
            },
            title:{
                required:true,
                minlength:3,
                maxlength:100,
            },
            meta_key:{
                required: true,
                minlength:3,
                maxlength:100,
            },
            meta_desc:{
                required: true,
                minlength:3,
                maxlength:500,
            },
            page_content:{
                required: true,
            }
        },
        messages: {
            heading:{
                required: "Heading is required.",
                minlength:js_min_lengths.replace("%s",3),
                maxlength:js_max_lengths.replace("%s",200),
            },
            title:{
                required: "Title is required.",
                minlength:js_min_lengths.replace("%s",3),
                maxlength:js_max_lengths.replace("%s",100),
            },
            meta_key:{
                required: "Meta key is required.",
                minlength:js_min_lengths.replace("%s",3),
                maxlength:js_max_lengths.replace("%s",100),
            },
            meta_desc:{
                required: "Meta description is required.",
                minlength:js_min_lengths.replace("%s",3),
                maxlength:js_max_lengths.replace("%s",500),
            },
            page_content:{
                required: "Page content is required.",
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });
    $("#frm_setting_add").validate({
        rules: {
            title: {
                required: true,
                maxlength:100
            },
            key: {
                required: true,
                maxlength:100
            },
            setting_type: {
                required: true,
            },
            value: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength:js_max_lengths.replace("%s",100),
            },
            key: {
                required: "Key is required.",
                maxlength:js_max_lengths.replace("%s",100)
            },
            setting_type: {
                required: "Settting type is required.",
            },
            value: {
                required: "Value is required.",
            },
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            scrollOnError(validator);
        }
    });
});