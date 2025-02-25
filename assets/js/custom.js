$(function () {

//register form validation
    $("#btnRegister").click(function () {

        $("#register-form").validate({

            errorClass: 'text-danger',
            errorElement: 'div',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass('has-success');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                user_name: {required: true,
                    rangelength: [6, 12],
                    alphanumeric: true
                },

                fname: {
                    required: true,
                    rangelength: [2, 20],
                    alpha: true
                },
                lname: {
                    required: true,
                    rangelength: [2, 20],
                    alpha: true
                },
                dobmonth: {required: true},
                dobday: {required: true},
                dobyear: {required: true,
                    check_under_16: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    rangelength: [6, 12]
                },
//                re_password: {
//                    equalTo: "#psword",
//                    required: true
//                },
                address: {required: true},
                mobile: {
                    required: true,
                    minlength: [10],
                    maxlength: [14]
                },
                verification_code: {required: true},
                /*IC:{required:true},*/
                //passport:{required:true},
                country: {required: true},
                city: {required: true},
                state: {required: true},
//                zip: {
//                    required: true,
//                    alphanumeric: true
//                },
                t_c: {required: true}
            },

            groups: {
                day: "dobday dobmonth dobyear"
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "dobday" || element.attr("name") == "dobmonth" || element.attr("name") == "dobyear")
                    error.insertAfter("#dob");
                else if (element.attr("name") == "mobcdfdfile")
                    error.insertAfter("#MObresult");
                else if (element.attr("name") == "user_name")
                    error.insertAfter("#userName");
				else if (element.attr("name") == "password")
                    error.insertAfter("#Pass");
				else if (element.attr("name") == "t_c")
                    error.insertAfter("#termCon");
                else
                    error.insertAfter(element);
					
				//	console.log(element.attr("name"));
            },
		messages: {
		  t_c: {
		  required: "Please accept the T&C policy to continue.",
		 }
		},

            submitHandler: function (form) {
                form.submit();
            }
        });
    }); //register form validation ends here



    $.validator.addMethod("check_under_16", function (value, element, param) {
        var day = $("#dobday").val();
        var month = $("#dobmonth").val();
        var year = $("#dobyear").val();
        var mydate = new Date();
        mydate.setFullYear(year, month - 1, day);
        var currdate = new Date();
        currdate.setFullYear(currdate.getFullYear() - 18);
        //console.log(currdate);
        if (currdate > mydate)
            return true;
        else
            return this.optional(element);
    }, lang.you_must_be_18);


//contact us form validation
    $("#btnContact").click(function () {

        $("#contact-form").validate({

            errorClass: 'text-danger',
            errorElement: 'span',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass('has-success');

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                fname: {
                    required: true,
                    rangelength: [3, 50],
                    alpha: true

                },
                email: {
                    required: true,
                    email: true
                },
                /*mobile: {
                 required: true,
                 minlength:[8],
                 maxlength:[14]
                 },*/
                message: {required: true,
                    maxlength: [300]
                },
                /*security_code: {
                    required: true,
                    checkCaptcha: true
                },
*/
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });//contact us form validation ends here

    //login form validation
    $("#btnLogin").click(function () {
        $("#lgn-form").validate({

            errorClass: 'text-danger',
            errorElement: 'p',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass('has-success');

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                user_name: {
                    required: true,
                },
                password: {
                    required: true
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });//login form validation ends here



    //forget password form validation
    $("#btnForget").click(function () {

        $("#forget-form").validate({

            errorClass: 'text-danger',
            errorElement: 'p',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-xs-9").addClass('has-error').removeClass('has-success');

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.col-xs-9").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });//forget password form validation ends here


//my profile form validation
    $("#btnProfile").click(function () {

        $("#profile-form").validate({

            errorClass: 'text-danger',
            errorElement: 'p',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass('has-success');

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                gender: {required: true},

                fname: {
                    required: true,
                    rangelength: [2, 20],
                    alpha: true
                },
                lname: {
                    required: true,
                    rangelength: [2, 20],
                    alpha: true
                },
                dobmonth: {required: true},
                dobday: {required: true},
                dobyear: {required: true},

                email: {
                    required: true,
                    email: true
                },
                address: {required: true},

                phone: {
                    required: true,
                    phone: true
                },
                country: {required: true},
                city: {required: true},
                zip: {required: true}

            },
            groups: {
                day: "dobday dobmonth dobyear"
            },
            errorPlacement: function (error, element) {
                console.log(element.attr("name"));
                console.log(error);
                if (element.attr("name") == "dobday" || element.attr("name") == "dobmonth" || element.attr("name") == "dobyear")
                    error.insertAfter("#dob");
                // console.log(error.text());
                else if (element.attr("name") == "gender")
                    error.insertAfter("#Egen");
                else if (element.attr("name") == "mobile")
                    error.insertAfter("#MObresult");
                else
                    error.insertAfter(element);
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });//my profile form validation ends here



    //change password form validation
    $("#btnChangePassword").click(function () {

        $("#password-form").validate({

            errorClass: 'text-danger',
            errorElement: 'span',

            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error').removeClass('has-success');

            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").removeClass('has-error').addClass('has-success');
                $(element).parents(".text-danger").removeClass('has-error').addClass('has-success');
            },

            rules: {
                old_password: {
                    required: true,
                    rangelength: [4, 16]
                },
                new_password: {
                    required: true,
                    rangelength: [4, 16]
                            //equalTo: "#pass"
                },
                re_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });//change password validation ends here



//refer a friend validation
    $("#btnRefer").click(function () {

        var js_name = $("#name1").val();
        var js_name2 = $("#name2").val();
        var js_name3 = $("#name3").val();
        var js_name4 = $("#name4").val();

        var js_email = $("#email1").val();
        var js_email2 = $("#email2").val();
        var js_email3 = $("#email3").val();
        var js_email4 = $("#email4").val();

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        var is_submit = 'yes';


        //first name validation
        if (js_name == "")
        {
            $("#nameE").show();
            $("#nameE").html(lang.please_enter_name);
            is_submit = 'no';
        } else if (js_name.length < 2 || js_name.length > 20)
        {
            $("#nameE").show();
            $("#nameE").html(lang.name_should_be_2_20);
            is_submit = 'no';
        } else
        {
            $("#nameE").hide();
        }


        //email validation
        if (js_email == "") {
            $("#emailE").show();
            $("#emailE").html(lang.please_enter_email);

            is_submit = 'no';
        } else if (!emailReg.test(js_email))
        {
            $("#emailE").show();
            $("#emailE").html(lang.enter_valid_email);
            is_submit = 'no';
        } else
        {
            $("#emailE").hide();
        }

        if (!emailReg.test(js_email2))
        {
            $("#email2E").show();
            $("#email2E").html(lang.please_enter_email);
            is_submit = 'no';
        } else
        {
            $("#emai2E").hide();
        }

        if (!emailReg.test(js_email3))
        {
            $("#email3E").show();
            $("#email3E").html(lang.please_enter_email);
            is_submit = 'no';
        } else
        {
            $("#emai3E").hide();
        }

        if (!emailReg.test(js_email4))
        {
            $("#email4E").show();
            $("#email4E").html(lang.please_enter_email);
            is_submit = 'no';
        } else
        {
            $("#email4E").hide();
        }
        if (is_submit == 'no')
        {
            return false;
        }

    });//end of refer friend validation

//pay for won aution form validation
    $("#btnPayWon").click(function () {

        $("#shipping-frm").validate({

            errorPlacement: function (error, element) {

                if (element.attr("name") == "payment_type")
                {
                    $("#ptype").html(error);
                } else {
                    error.insertAfter(element);
                }
            },

            errorElement: 'span',

            rules: {
                payment_type: {required: true},

                ship_name: {
                    required: true,
                    rangelength: [2, 20],
                    alpha: true
                },
                ship_address: {required: true},

                ship_phone: {
                    required: true,
                    phone: true
                },
                ship_city: {required: true},
                ship_post_code: {required: true},
                security_code: {
                    required: true,
                    checkCaptcha: true
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });//end of pay for won aution form validation

});

$.validator.addMethod("phone", function (value, element) {
    return this.optional(element) || /^[9|8][0-9]{7}$/i.test(value);
}, "Invalid phone number");

/* $.validator.addMethod("phone", function (value, element) {
 return this.optional(element) || /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/i.test(value);
 }, "Invalid phone number");*/

//

$.validator.addMethod("alpha", function (value, element) {
    return this.optional(element) || /^[a-z _]+$/i.test(value);
}, "Please Enter only alphabets");

$.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
}, "Letters or numbers only please");



$.validator.addMethod("checkCaptcha", function (value, element, param) {
    return this.optional(element) || (value.toLowerCase() == capta.toLowerCase());
}, "Please enter a valid security code");



$(document).on('click', '.wthlst', function ()
{
    var userid = user_id;
    var product_id = $(this).data('productid');
    if (userid == undefined || userid == null || userid == '') {
        window.location.href = site_url + '/users/login';
    } else
    {
        $.ajax({
            url: site_url + "/home/add_watch_list",
            type: 'POST',
            datatype: 'html',
            data: {
                product_id: product_id,
                user_id: userid
            },
            success: function (datas) {
                data = jQuery.parseJSON(datas);
                console.log(data);
                if (data.status == 'success')
                {
                    if (data.operation == 'delete')
                    {
                        $('.with_' + product_id).removeClass('wthselect');
                    }
                    if (data.operation == 'insert')
                    {
                        $('.with_' + product_id).addClass('wthselect');
                    }

                } else
                {

                }
            }
        });
    }
})


$(document).on('click', '.wthlstrem', function ()
{
    // alert('test');
    // return false;
    var userid = user_id;
    var product_id = $(this).data('productid');
    if (userid == undefined || userid == null || userid == '') {
        window.location.href = site_url + '/users/login';
    } else
    {
        $.ajax({
            url: site_url + "/home/add_watch_list",
            type: 'POST',
            datatype: 'html',
            data: {
                product_id: product_id,
                user_id: userid
            },
            success: function (datas) {
                data = jQuery.parseJSON(datas);
                console.log(data);
                if (data.status == 'success')
                {
                    if (data.operation == 'delete')
                    {
                        // $('#wtlist_'+product_id).removeClass('wthselect');
                        $('#wtlist_' + product_id).fadeOut(1000, function () {
                            $(this).remove();
                        });

                    }

                } else
                {

                }
            }
        });
    }

})


$(document).on('click', '.btn_vote', function (e) {
    // alert('test');
    // return false;
    var productid = $(this).data('productid');
    var vote_type = $(this).data('votetype');
    // alert(vote_type);
    // return false;
    var userid = user_id;
    // alert(productid);

    if (userid == undefined || userid == null || userid == '') {
        window.location.href = site_url + '/users/login';
    } else
    {

        $.ajax({
            type: "POST",
            url: site_url + '/home/insert_vote_record',
            datatype: 'html',
            data: {
                product_id: productid,
                user_id: userid,
                vote_type: vote_type
            },
            success: function (jsons) {
                // console.log(jsons);
                data = jQuery.parseJSON(jsons);
                console.log(data.vote_type);
                votetype = data.vote_type;
                // alert(votetype);
                if (data.status == 'success')
                {

                    if (votetype == 'positive')
                    {
                        // alert('pos');
                        $('#vote_pos_' + productid).addClass('voteselect');
                        $('#vote_neg_' + productid).removeClass('voteselect_neg');
                    }
                    if (votetype == 'negative')
                    {
                        // alert('neg');
                        $('#vote_neg_' + productid).addClass('voteselect_neg');
                        $('#vote_pos_' + productid).removeClass('voteselect');
                    }

                    $('#votestatus_' + productid).css("display", "block");
                    $('#votestatus_' + productid).html(data.message);
                    $('#votepos_' + productid).html(data.vote_positive);
                    $('#voteneg_' + productid).html(data.vote_negative);
                    $('#total_vote_' + productid).html(data.total_vote);

                } else
                {
                    $('#votestatus_' + productid).css("display", "block");
                    $('#votestatus_' + productid).html(data.message);
                    $('#votepos_' + productid).html(data.vote_positive);
                    $('#voteneg_' + productid).html(data.vote_negative);
                    $('#total_vote_' + productid).html(data.total_vote);



                }
                setTimeout(function () {
                    $('#votestatus_' + productid).fadeOut('fast');
                }, 4000);

            },

        });
    }

})
