// JavaScript Document

//facebook login script
window.fbAsyncInit = function() {
    FB.init({
            appId: FacebookAppID, // Set YOUR APP ID
            // appId:'461473887544695',
            channelUrl: 'http://connect.facebook.net/en_US/all.js', // Channel File
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true, // parse XFBML
            version: 'v2.10' // use version 2.2
        });
};
function facebookLogin() {
  $('#a_submit').attr('onclick','getUserInfo()');
  FB.login(function(response) {
    if (response.authResponse) {
        getUserInfo();
    } else {
       // console.log('User cancelled login or did not fully authorize.');
    }
}, {
    scope: 'email, user_birthday, user_location'
});
}
function login_signup_facebook(response){
 // console.log("here");
 // console.log(response);
    if (response.id) {
       $.ajax({
        url: urlFacebookLogin,
        type: 'post',
        dataType: 'json',
        data: response,
        success: function(data, status) {
          
       //  console.log(data);
         if(data.url != ''){ 
            $('#a_email').val('');
            $('#a_year').val('');
            $('#a_month').val('');
            $('#a_day').val('');
            $('#myModal').modal('hide');
            window.location.href = data.url;   
           
        }   
    }
});
   } else {

    $('#register-message').show();
    $('#register-message .message').html(lang.error_in_social_login);
}

}
function getUserInfo() {

  $('#myModal').modal('hide');
   $('#reload_pag').show();

    //console.log('Inside getUserInfo function');
       // $('#myModal').modal({backdrop: 'static', keyboard: false});
       FB.api('/me',{fields: 'id,first_name, last_name, email, gender, birthday, age_range'}, function(response) {
       // console.log('asdf');
       //  console.log(response);
       //  console.log(response.id);
        if(response.age_range.min<21){
            $('#age_alert').attr('class','alert alert-danger');
            $('#age_alert').html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>"+lang.must_be_18)
            $('#age_alert').show();
        }
        else{
          $('#reload_pag').show();
         // console.log(response.birthday);
          $.post( check_existing_user,{id:response.id}, function(data) {
              var ee= JSON.parse(data);
              if(ee.status==='exist'){
                login_signup_facebook(response);
            }
            else{
                $.post( login_add,{u_email:response.email}, function(num) {
                    if(num>0){
                        $('#age_alert').attr('class','alert alert-danger');
                        $('#age_alert').html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>The <b>"+response.email+"</b>"+lang.is_already_taken);
                        $('#age_alert').show();
                        $('#reload_pag').hide();
                   }else{
                       if(!response.email){
                        response.email=$('#a_email').val();
                    }
                    if($('#a_year').val() && $('#a_month').val() && $('#a_day').val()){
                        response.birthday=$('#a_year').val()+"-"+$('#a_month').val()+"-"+$('#a_day').val();
                    }
                    if(response.email&&response.birthday){
                        login_signup_facebook(response);
                    }
                    else{
                    //console.log(response.email+response.birthday);
                    if(response.email){
                        $('#a_email').val(response.email);
                        $('#a_email').prop("disabled",true);
                        $('#a_email').attr('placeholder',response.email);
                    }
                    $('#reload_pag').hide();
                    $('#myModal').modal('show');
                }
            }
        });
            }
        });

      }

  });

   }
   function clear_field(){
    $('#a_email').val('');
    $('#a_year').val('');
    $('#a_month').val('');
    $('#a_day').val('');

}


function Logout() {
    FB.logout(function() {
        document.location.reload();
    });
}

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));