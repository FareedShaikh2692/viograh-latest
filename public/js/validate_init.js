$(function() {
    jQuery.validator.setDefaults({
        highlight: function(element) {
           
            if ($(element).is(":radio")) {
                $(".form-check-inline").addClass('has-error');
            } else if ($(element).is("select")) {
                $(element).closest('.form-group').find('.select2-selection').addClass('has-error');
            } else {
                if ($(element).closest('.input-group').length) {
                    $(element).closest('.input-group').addClass('has-error');
                } else {

                    $(element).parent().addClass('has-error');
                }
            }
        },
        unhighlight: function(element) {
          
            if ($(element).is(":radio")) {
                $(".form-check-inline").removeClass('has-error');
            } else if ($(element).is("select")) {
                $(element).parent().find('.has-error').removeClass('has-error');
            } else {
                if ($(element).closest('.input-group').length) {
                    $(element).closest('.input-group').removeClass('has-error');
                } else {
                    $(element).parent().removeClass('has-error');
                }
            }
        },
        errorElement: 'label',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            
            if (element.is(":radio")) {
                console.log(element.parents('.cont'));
                error.appendTo(element.parents('.cont'));

            } else if ($(element).is("select")) {
                console.log(element.closest('.form-group').find('span.select2-container').parent().find('.help-block').length);
                error.insertAfter(element.closest('.form-group').find('span.select2-container'));
            } else {
                if (element.closest('.input-group').length) {

                    if (element.closest('.input-group').parent().find('.help-block').length) {
                        element.closest('.input-group').parent().find('.help-block').remove();
                    }
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    if (element.parent('.has-error').find('.help-block').length) {
                        element.parent('.has-error').find('.help-block').remove();
                    } else if ($(element).is("select")) {
                        element.closest('.form-group').find('.help-block').remove();
                    }
                    error.insertAfter(element);

                }
            }
        },
        onfocusout: false
    });

    $.validator.addMethod('validatePostelcode', function(value, element) {
        var patt = /\b((?:(?:gir)|(?:[a-pr-uwyz])(?:(?:[0-9](?:[a-hjkpstuw]|[0-9])?)|(?:[a-hk-y][0-9](?:[0-9]|[abehmnprv-y])?)))) ?([0-9][abd-hjlnp-uw-z]{2})\b/i;

        return res = patt.test(value);
    }, 'Invalid postel code.');
    /* 
        $.validator.addMethod("amountRex",function(value, element, regexp) {
            var patt = /^\d{1,8}(?:\.\d{1,2}?)?$/;
            return this.optional(element) || patt.test(value);
        },"Invalid amount."); */

    function scrollOnError(validator) {
        $('html, body').animate({
            scrollTop: $(validator.errorList[0].element).offset().top - $('#header').height() - 150
        }, 500, function() {
            validator.errorList[0].element.focus();
        });
    };


    jQuery.validator.addMethod("email", function(value, element) {
        return this.optional(element) || (/^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(value) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(value));
    }, js_email);

    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');

    $.validator.addMethod('phoneUK', function(phone_number, element) {
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/);
    }, 'Please specify a valid phone number');
    $.validator.addMethod('dial_code', function(value, element) {
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/);
    }, 'Please specify a valid phone number');
    $.validator.addMethod("dial_code_profile", function(value, element) {
        return this.optional(element) || value.match(/^[\d\(\)\-+]+$/);
    }, 'Please enter valid country code.');

    $.validator.addMethod("extension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "jpg|png|jpeg|mp4|ogv|webm";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Valid extensions are jpg, jpeg, png, mp4, ogv and webm.");

    $.validator.addMethod("myself_extension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "jpg|png|jpeg";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Valid extensions are jpg, jpeg and png.");

    $.validator.addMethod("blood", function(value, element) {
        return this.optional(element) || value.match(/^(A|B|AB|O)[+-]$/);
    }, 'Please Enter Valid blood group.');


    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value.match(/^[a-zA-Z\s]+$/);
    }, 'Only alphabets are allowed.');

    $.validator.addMethod("alphaComma", function(value, element) {
        return this.optional(element) || value.match(/^[\.a-zA-Z,!? ]*$/);
    }, 'Only alphabets are allowed.');

    $.validator.addMethod("reasonSpace", function(value, element) {
        return this.optional(element) || value.match(/^[^-\s][a-zA-Z0-9_\s-]+$/);
    }, 'Please Enter Valid Reason.');

    $.validator.addMethod('checkUserPassword', function(password, element) {
        return password.match(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/);
    }, js_pass_regex);

    $.validator.addMethod('alphaNumeric', function(value, element) {
        return !value.match(/^[0-9]+$/);
    }, 'Only numbers are not allowed.');

    $.validator.addMethod("allowNumericWithDecimal", function(value, element) {
        return this.optional(element) || value.match(/^\d+(\.\d{1,2})?$/);
    }, 'Please Enter only two digits after decimal.');

    /*  $.validator.addMethod("maxupload", function(value, element, param) {
         var length = ( element.files.length );
         return this.optional( element ) || length <= param;
     }, 'Maximum 5 documents are allowed.'); */

    jQuery.validator.addMethod('selectgender', function(value) {
        return (value != '');
    }, "Gender is required");

    jQuery.validator.addMethod('selectrelation', function(value) {
        return (value != '');
    }, "Relationship is required");

    /*  $.validator.addMethod("docextension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "jpg|png|jpeg|doc|xlsx|xls|ods|pdf|txt";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.");
 */

    $.validator.addMethod("allowWithDecimal", function(value, element) {
        return this.optional(element) || value.match(/^\d+(\.\d{1,2})?$/);
    }, 'Please Enter up to two decimals.');

    $.validator.addMethod("greaterZero", function(value, element) {
        return this.optional(element) || value.match(/[1-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/);
    }, 'Minimum amount should be 1.');

    $.validator.addMethod("greaterZeroNumber", function(value, element) {
        return this.optional(element) || value.match(/[1-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/);
    }, 'Dial code can not be only 0.');

    $.validator.addMethod("greaterZeroProfile", function(value, element) {
        return this.optional(element) || value.match(/[1-9]{1,3}([0-9]{3})?/);
    }, 'Phone number must be greater than 0.');

    $.validator.addMethod("treeExtension", function(value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "jpg|png|jpeg";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, "Valid extensions are jpg, jpeg, png.");

    $.validator.addMethod("commarule", function(value) {
        var n = value.split(",").length - 1;
        return (n < 5);
    }, "Maximum 5 items by comma separated are allowed.");

    $("#frm_user_register").validate({
        rules: {
            first_name: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                alpha: true,
                minlength: 2,
                maxlength: 32,
            },
            last_name: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                alpha: true,
                minlength: 2,
                maxlength: 32,
            },
            email: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                email: true
            },
            phone_number: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                number: true,
                minlength: 6,
                maxlength: 20,
            },
            birth_date: {
                required: true,
            },
            gender: {
                required: true,
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 255,
                checkUserPassword: true,
            },
            // confirm_password:{
            //     required: true,
            //     equalTo: "#password",
            // },
            accept: {
                required: true,
            }
        },
        messages: {
            first_name: {
                required: "First name is required.",
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 32),
            },
            last_name: {
                required: "Last name is required.",
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 32),
            },
            email: {
                required: "Email address is required.",
                email: js_email
            },
            phone_number: {
                required: "Mobile number is required.",
                number: "Only numeric allowed.",
                minlength: "Minimum 6 numerics are allowed.",
                maxlength: "Maximum 20 numerics are allowed.",
            },
            birth_date: {
                required: "Date of Birth is required.",
            },
            gender: {
                required: "Gender is required.",
            },
            password: {
                required: "Password is required.",
                minlength: js_pass_min.replace("%s", 6),
                maxlength: js_pass_max.replace("%s", 255),
            },
            // confirm_password: {
            //     required: "Confirm Password is required.",
            //     equalTo:js_pass_mis_match
            // },
            accept: {
                required: "Accept our Terms & Conditions and Privacy Policy!",
            }
        },
        submitHandler: function(form) {
            alert($('input[name="gender"]').val());
        }
    });

    $("#frm_privacy_edit").validate({
        rules: {
            profile: {
                required: true,
            },
            notification: {
                required: true,
            }
        },
        messages: {
            profile: {
                required: "Please choose atleast one profile type.",
            },
            notification: {
                required: "Please choose email notification.",
            }
        }
    })
    $("#frm_profile_edit").validate({
        rules: {
            first_name: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                alpha: true,
                maxlength: 50,
            },
            last_name: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                alpha: true,
                maxlength: 50,
            },

            phone_number_profile: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                number: true,
                minlength: 5,
                maxlength: 15,
                greaterZeroProfile: true,
            },
            birth_date: {
                required: true,
            },
            gender: {
                required: true,
            },
            address: {
                maxlength: 400,
            },
            about_me: {
                maxlength: 400,
            },
            places_lived: {
                maxlength: 100,
            },
            blood_group: {
                // blood:true,
            },
            profession: {
                maxlength: 200,
                minlength: 2,
            },
            dial_code: {
                required: true,
                dial_code_profile: true,
                minlength: 2,
                maxlength: 7,
                greaterZeroNumber: true,
            }

        },
        messages: {
            first_name: {
                required: "First name is required.",
                maxlength: "The Maximum Length of First Name is 50 Characters.",
            },
            last_name: {
                required: "Last name is required.",
                maxlength: "The Maximum Length of Last Name is 50 Characters.",
            },
            /*  email: {
                 required: "Email address is required.",
                 email:js_email,
                 maxlength: "Only 100 characters are allowed",
             }, */
            phone_number_profile: {
                required: "Phone number is required.",
                number: "Only numerics are allowed.",
                minlength: "Minimum 5 numerics are allowed.",
                maxlength: "Maximum 15 numerics are allowed.",
            },
            birth_date: {
                required: "Date of Birth is required.",
            },
            gender: {
                required: "Gender is required.",
            },
            address: {
                maxlength: "Maximum 400 characters are allowed.",
            },
            about_me: {
                maxlength: "Maximum 400 characters are allowed.",
            },
            places_lived: {
                maxlength: "Maximum 100 characters are allowed.",
            },
            profession: {
                maxlength: "Maximum 200 characters are allowed.",
                minlength: "Minimun 2 characters are allowed.",
            },
            blood_group: {
                maxlength: js_pass_min.replace("%s", 5),
                minlength: js_pass_max.replace("%s", 2),
            },
            dial_code: {
                required: "Country Code is required.",
                maxlength: 'Maximum 7 numerics are allowed.',
                minlength: 'Minimum 2 numerics are allowed.',
            }
        }
    });
    $("#frm_user_login").validate({
        rules: {
            email: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 255,
            },
        },
        messages: {
            email: {
                required: "Email address is required.",
                email: js_email
            },
            password: {
                required: "Password is required.",
                minlength: js_pass_min.replace("%s", 6),
                maxlength: js_pass_max.replace("%s", 255),
            },
        }
    });
    $("#frm_user_forgot_pass").validate({
        rules: {
            forgot_email: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                email: true
            },
        },
        messages: {
            forgot_email: {
                required: "Email address is required.",
                email: js_email
            },
        }
    });
    $("#frm_user_reset_pass").validate({
        rules: {
            email: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 255,
                checkUserPassword: true,
            },
            confirm_password: {
                equalTo: "#password",
            },
        },
        messages: {
            email: {
                required: "Email address is required.",
                email: js_email
            },
            password: {
                required: "New password is required.",
                minlength: js_pass_min.replace("%s", 6),
                maxlength: js_pass_max.replace("%s", 255),
            },
            confirm_password: {
                equalTo: js_pass_mis_match
            },
        },
    });
    $("#frm_wishlist_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },
            objective: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            wish: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            by_when: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
               /*  maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                }, */
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            objective: {
                required: "Objective is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            wish: {
                required: "Wish  is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            by_when: {
                required: "When is required.",
               // maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_wishlist_edit").validate({
        rules: {
            file: {
                extension: true,
            },

            objective: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            wish: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            by_when: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            objective: {
                required: "Objective is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            wish: {
                required: "Wish  is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            by_when: {
                required: "When  is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_idea_add").validate({
        rules: {
            file: {
                extension: true,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            big_idea: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            big_idea: {
                required: "Big idea  is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_idea_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,

            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            big_idea: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            big_idea: {
                required: "Big idea  is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_diary_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //  maxupload: 5,

            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_moments_add").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
            },
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_moment_edit").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
            },

        },
        messages: {

            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },

        }
    });
    $("#frm_diary_edit").validate({
        rules: {
            file: {
                extension: true,
            },

            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_lifelesson_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_lifelesson_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_dream_add").validate({
        rules: {
            file: {
                extension: true,
            },
            /*"uploaddoc[]":{
                 maxupload: 5,
               
            },*/
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_dream_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //  maxupload: 5,

            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_experience_add").validate({
        rules: {
            file: {
                extension: true,
            },
            /*"uploaddoc[]":{
                 maxupload: 5,
               
            },*/
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_experience_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            date: {
                required: "Date is required.",
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_first_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_first_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //  maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_last_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //maxupload: 5,

            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_last_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //  maxupload: 5,
            },
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_spiritual_journey_add").validate({
        rules: {
            file: {
                extension: true,
            },

            when_started: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            why_started: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            who_influenced: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            practice: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            benefit: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            when_started: {
                required: "When Started is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            why_started: {
                required: "Why Started is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            who_influenced: {
                required: "Who Influenced is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            practice: {
                required: "Practice is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            benefit: {
                required: "Benefit is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_spiritual_journey_edit").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                //   maxupload: 5,
            },
            when_started: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            why_started: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            who_influenced: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            practice: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            benefit: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            when_started: {
                required: "When Started is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            why_started: {
                required: "Why Started is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            who_influenced: {
                required: "Who Influenced is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            practice: {
                required: "Practice is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            benefit: {
                required: "Benefit is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
        }
    });
    $("#frm_contact_us").validate({
        rules: {
            name: {
                required: true,
                alpha: true,
                minlength: 2,
                maxlength: 100,
            },
            email: {
                required: {
                    depends: function() {
                        $(this).val($.trim($(this).val()));
                        return true;
                    }
                },
                email: true,
                maxlength: 200,
            },
            contact_number: {
                number: true,
                minlength: 6,
                maxlength: 20,
            },
            subject: {
                required: true,
                minlength: 2,
                maxlength: 200,
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 500,
            },
        },
        messages: {
            name: {
                required: "Name is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 100),
            },
            email: {
                required: "Email address is required.",
                email: js_email,
                maxlength: js_pass_max.replace("%s", 200),
            },
            contact_number: {
                number: "Only numeric allowed.",
                minlength: "Minimum 6 numerics are allowed.",
                maxlength: "Maximum 20 numerics are allowed.",
            },
            subject: {
                required: "Subject is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 200),
            },
            message: {
                required: "Message is required.",
                minlength: js_pass_min.replace("%s", 10),
                maxlength: js_pass_max.replace("%s", 500),
            },
        }
    });
    $("#commentform").validate({
        rules: {
            comment: {
                required: true,
                maxlength: js_pass_max.replace("%s", 500)
            },
        },
        messages: {
            comment: {
                required: "Comment is required.",
                maxlength: js_pass_max.replace("%s", 500),
            },
        }
    });
    $("#frm_myself_add").validate({
        rules: {
            file: {
                myself_extension: true,
            },
            essence_of_life: {
                minlength: 2,
                maxlength: 200,
            },
            biography: {
                minlength: 2,
                alphaNumeric: true,
            },
            place_of_birth: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            favourite_movie: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            favourite_song: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            favourite_book: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            favourite_eating_joints: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            hobbies: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            food: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            role_model: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            car: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            brand: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            tv_shows: {
                minlength: 2,
                maxlength: 400,
                commarule: true,
                // alphaNumeric: true,
            },
            actors: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            sports_person: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            politician: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            },
            diet: {
                minlength: 2,
                maxlength: 400,
                alphaNumeric: true,
                commarule: true,
            },
            zodiac_sign: {
                minlength: 2,
                maxlength: 200,
                alphaNumeric: true,
                commarule: true,
            }
        },
        messages: {
            essence_of_life: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            biography: {
                minlength: js_min_lengths.replace("%s", 2),
            },
            place_of_birth: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            favourite_movie: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            favourite_song: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            favourite_book: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            favourite_eating_joints: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            hobbies: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            food: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            role_model: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            car: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            brand: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            tv_shows: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            actors: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            sports_person: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            politician: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
            diet: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 400),
            },
            zodiac_sign: {
                minlength: js_min_lengths.replace("%s", 2),
                maxlength: js_max_lengths.replace("%s", 200),
            },
        }
    });
    $("#frm_education_add").validate({
        rules: {
            file: {
                extension: true,
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },
            name: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            start_date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            my_buddies: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            achievement: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [1] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }
        },
        messages: {
            name: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "Name of school is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            start_date: {
                required: "Start date is required.",

            },
            my_buddies: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "School Mates are required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            achievement: {
                minlength: js_min_lengths.replace("%s", 1),
                required: "Class/Grade is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                minlength: js_min_lengths.replace("%s", 10),
                required: "Description is required.",
            },
        },

    });

    $("#frm_career_add").validate({
        rules: {
            file: {
                extension: true,
            },
            name_of_organisation: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            role: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            start_date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },

            },
            buddies: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            achievement: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [1] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [500];
                },
                alphaNumeric: true,
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }

        },
        messages: {
            name_of_organisation: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "Name of organisation is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            role: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "Role is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            start_date: {
                required: "Start date is required.",

            },
            buddies: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "My colleagues is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            achievement: {
                minlength: js_min_lengths.replace("%s", 2),
                required: "My achievement is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                minlength: js_min_lengths.replace("%s", 10),
                required: "Description is required.",
            },
        }
    });
    $("#frm_password_edit").validate({
        rules: {
            current_password: {
                minlength: 6,
                required: true,
            },
            new_password: {
                required: true,
                minlength: 6,
                checkUserPassword: true,
                maxlength: 255,

            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            },
        },
        messages: {
            current_password: {
                minlength: js_min_lengths.replace("%s", 6),
                required: "Current password is required.",
            },
            new_password: {
                required: "New password is required.",
                minlength: js_pass_min.replace("%s", 6),
                maxlength: js_pass_max.replace("%s", 255),
            },
            confirm_password: {
                required: "Confirm password is required.",
                equalTo: "New password and confirm password must be same.",
            },

        }
    });
    $("#frm_asset_add").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                alphaNumeric: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },
            "uploaddoc[]": {
                //  maxupload: 5,
            },
            nominee_name: {
                required: function(element) {
                    return !$("#nominee_email").is(":blank");
                },
                alpha: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [3] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },

            nominee_email: {
                required: function(element) {
                    return !$("#nominee_name").is(":blank");
                },
                email: true
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            nominee_phone_number: {
                number: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [9] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [30];
                },
            },
            amount: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [200];
                },
                greaterZero: true,
            },

        },
        messages: {
            title: {
                required: "Title is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 100),
            },
            nominee_name: {
                minlength: js_pass_min.replace("%s", 3),
                maxlength: js_pass_max.replace("%s", 100),
                required: 'Nominee is required.',
            },
            nominee_email: {

                email: js_email,
                required: 'Email address is required.',
            },
            description: {
                minlength: js_min_lengths.replace("%s", 4),
                required: "Description is required.",
            },
            nominee_phone_number: {
                number: "Only numeric allowed.",
                minlength: "Minimum 9 numerics are allowed.",
                maxlength: "Maximum 15 numerics are allowed.",
            },
            amount: {
                required: "Amount  is required",
                maxlength: 'Maximum digits should be 15',
            },

        }
    });

    $("#frm_asset_edit").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                alphaNumeric: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },
            "uploaddoc[]": {
                //  maxupload: 5,
            },
            nominee_name: {
                required: function(element) {
                    return !$("#nominee_email").is(":blank");
                },
                alphaNumeric: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [3] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },

            nominee_email: {
                required: function(element) {
                    return !$("#nominee_name").is(":blank");
                },
                email: true
            },
            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            nominee_phone_number: {
                number: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [9] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [30];
                },
            },
            amount: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [200];
                },
                greaterZero: true,
            },

        },
        messages: {
            title: {
                required: "Title is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 100),
            },
            nominee_name: {
                minlength: js_pass_min.replace("%s", 3),
                maxlength: js_pass_max.replace("%s", 100),
                required: 'Nominee is required.',
            },
            nominee_email: {

                email: js_email,
                required: 'Email address is required.',

            },
            description: {
                minlength: js_min_lengths.replace("%s", 4),
                required: "Description is required.",
            },
            nominee_phone_number: {
                number: "Only numeric allowed.",
                minlength: "Minimum 9 numerics are allowed.",
                maxlength: "Maximum 15 numerics are allowed.",
            },
            amount: {
                required: "Amount  is required",
                maxlength: 'Maximum digits should be 15',
            },

        }
    });
    $("#frm_liability_add").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                alphaNumeric: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },
            "uploaddoc[]": {
                //maxupload: 5,
            },

            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            bank_name: {
                required: function(element) {
                    return !$("#account_number").is(":blank");
                },
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
                alpha: true,
            },
            account_number: {
                required: function(element) {
                    return !$("#bank_name").is(":blank");
                },
                number: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [8] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [30] : [100];
                },
            },
            amount: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [100];
                },
                greaterZero: true,

            },

        },
        messages: {
            title: {
                required: "Title is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 100),
            },

            description: {
                minlength: js_min_lengths.replace("%s", 4),
                required: "Description is required.",
            },
            bank_name: {
                minlength: "Minimum 4 characters are allowed.",
                maxlength: "Maximum 100 characters are allowed.",
                required: 'Bank name is required.',
            },
            account_number: {
                number: "Only numeric allowed.",
                minlength: "Minimum 8 numerics are allowed.",
                maxlength: "Maximum 30 numerics are allowed.",
                required: 'Account number is required.',
            },
            amount: {
                required: "Amount  is required",
                maxlength: 'Maximum digits should be 15',
            },

        }
    });

    $("#frm_liability_edit").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                alphaNumeric: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [2] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
            },
            "uploaddoc[]": {
                // maxupload: 5,
            },

            description: {
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            },
            bank_name: {
                required: function(element) {
                    return !$("#account_number").is(":blank");
                },
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [4] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [100] : [500];
                },
                alpha: true,
            },
            account_number: {
                required: function(element) {
                    return !$("#bank_name").is(":blank");
                },
                number: true,
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [8] : [0];
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [30] : [100];
                },
            },
            amount: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [15] : [500];
                },
                greaterZero: true,
            },

        },
        messages: {
            title: {
                required: "Title is required.",
                minlength: js_pass_min.replace("%s", 2),
                maxlength: js_pass_max.replace("%s", 100),
            },

            description: {
                minlength: js_min_lengths.replace("%s", 4),
                required: "Description is required.",
            },
            bank_name: {
                minlength: "Minimum 4 characters are allowed.",
                maxlength: "Maximum 100 characters are allowed.",
                required: 'Bank name is required.',
            },
            account_number: {
                number: "Only numeric allowed.",
                minlength: "Minimum 8 numerics are allowed.",
                maxlength: "Maximum 30 numerics are allowed.",
                required: 'Account number is required.',
            },
            amount: {
                required: "Amount  is required",
                maxlength: 'Maximum digits should be 15',
            },

        }
    });
    $(".family_tree_form").validate({
        rules: {
            name: {
                required: true,
                alpha: true,
            },
            email: {
                email: true,
            },
            age: {
                required: true,
                number: true,
                range: [0.1, 150],
            },
            gender: {
                selectgender: true,
            },
            relation: {
                selectrelation: true,
            },
            contact: {
                number: true,
                minlength: 6,
                maxlength: 20,
            },
            image: {
                treeExtension: true,
                filesize: 5,
            },
        },
        messages: {
            name: {
                required: "Name is required.",
            },
            age: {
                required: "Age is required.",
                number: "Only numeric allowed.",
                range: "Please enter a value between 0.1 and 150.",
            },
            contact: {
                number: "Only numeric allowed.",
                minlength: "Minimum 6 numerics are allowed.",
                maxlength: "Maximum 20 numerics are allowed.",
            },
        }
    });
    $("#frm_milestone").validate({
        rules: {
            title: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                maxlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [200] : [1000];
                },
            },
            description: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
                minlength: function(element) {
                    return !$("#save_draft").is(":checked") ? [10] : [0];
                },
            },
            milestone_completion_date: {
                required: function(element) {
                    return !$("#save_draft").is(":checked");
                },
            }

        },
        messages: {
            title: {
                required: "Title is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            description: {
                required: "Description is required.",
                minlength: js_min_lengths.replace("%s", 10),
            },
            milestone_completion_date: {
                required: "Milestone completion date is required.",
            }
        }
    });
    $("#feedback_form").validate({
        rules: {
            type: {
                required: true
            },
            subject: {
                required: true,
                maxlength: 200,
            },
            message: {
                required: true,
                minlength: 10,
                maxlength: 1000,
            },
        },
        messages: {
            type: {
                required: "Type is required.",
            },
            subject: {
                required: "Subject is required.",
                maxlength: js_max_lengths.replace("%s", 200),
            },
            message: {
                required: "Message is required.",
                minlength: js_min_lengths.replace("%s", 10),
                maxlength: js_max_lengths.replace("%s", 1000),
            },
        }
    });
});