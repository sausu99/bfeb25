function facebookLogin(){$("#a_submit").attr("onclick","getUserInfo()"),FB.login(function(a){a.authResponse&&getUserInfo()},{scope:"email, user_birthday, user_location"})}function login_signup_facebook(a){a.id?$.ajax({url:urlFacebookLogin,type:"post",dataType:"json",data:a,success:function(a,e){""!=a.url&&($("#a_email").val(""),$("#a_year").val(""),$("#a_month").val(""),$("#a_day").val(""),$("#myModal").modal("hide"),window.location.href=a.url)}}):($("#register-message").show(),$("#register-message .message").html(lang.error_in_social_login))}function getUserInfo(){$("#myModal").modal("hide"),$("#reload_pag").show(),FB.api("/me",{fields:"id,first_name, last_name, email, gender, birthday, age_range"},function(a){a.age_range.min<21?($("#age_alert").attr("class","alert alert-danger"),$("#age_alert").html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>"+lang.must_be_18),$("#age_alert").show()):($("#reload_pag").show(),$.post(check_existing_user,{id:a.id},function(e){"exist"===JSON.parse(e).status?login_signup_facebook(a):$.post(login_add,{u_email:a.email},function(e){e>0?($("#age_alert").attr("class","alert alert-danger"),$("#age_alert").html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>The <b>"+a.email+"</b>"+lang.is_already_taken),$("#age_alert").show(),$("#reload_pag").hide()):(a.email||(a.email=$("#a_email").val()),$("#a_year").val()&&$("#a_month").val()&&$("#a_day").val()&&(a.birthday=$("#a_year").val()+"-"+$("#a_month").val()+"-"+$("#a_day").val()),a.email&&a.birthday?login_signup_facebook(a):(a.email&&($("#a_email").val(a.email),$("#a_email").prop("disabled",!0),$("#a_email").attr("placeholder",a.email)),$("#reload_pag").hide(),$("#myModal").modal("show")))})}))})}function clear_field(){$("#a_email").val(""),$("#a_year").val(""),$("#a_month").val(""),$("#a_day").val("")}function Logout(){FB.logout(function(){document.location.reload()})}window.fbAsyncInit=function(){FB.init({appId:FacebookAppID,channelUrl:"http://connect.facebook.net/en_US/all.js",status:!0,cookie:!0,xfbml:!0,version:"v2.10"})},function(a,e,l){var t,o=a.getElementsByTagName(e)[0];a.getElementById(l)||((t=a.createElement(e)).id=l,t.src="//connect.facebook.net/en_US/sdk.js",o.parentNode.insertBefore(t,o))}(document,"script","facebook-jssdk");