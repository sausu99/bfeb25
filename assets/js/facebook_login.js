function facebookLogin() {
    $("#a_submit").attr("onclick", "getUserInfo()"),
        FB.login(
            function (a) {
                a.authResponse && getUserInfo();
            },
            { scope: "email, user_birthday, user_location" }
        );
}

function login_signup_facebook(userData) {

   // console.log(userData.id);
	$.post(urlFacebookLogin, userData, function(data){ 
		console.log(data); 
		var data = JSON.parse(data);
		if(data.status == "success"){
			  if (data.redirect_to != '') {
				  window.location.href = data.redirect_to;
			  }	
		  }else{
			  $("#error_block").show();
			  $("#error_message").html(data.message);
		  }
	});
}

function getUserInfo() {
        FB.api("/me", { fields: "id,first_name, last_name, email, gender, birthday, age_range" }, function (response) {
           //console.log(response);
		   login_signup_facebook(response);
			
        });
}

function Logout() {
    FB.logout(function () {
        document.location.reload();
    });
}



window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : FacebookAppID, // FB App ID
      cookie     : true,  // enable cookies to allow the server to access the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v13.0' // use graph api version 2.10
    });
    
    // Check whether the user already logged in
    /*FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            //display user data
            getFbUserData();
        }
    });*/
};

// Load the JavaScript SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));