// Render Google Sign-in button
function renderButton() {
    gapi.signin2.render('gSignIn', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
    });
}

function googlelogin() {
	
    var myParams = {
        'clientid': googleClientId,
        'cookiepolicy': 'single_host_origin',
        'callback':  onSuccess,
		//'onsuccess': onSuccess,
        //'onfailure': onFailure,
        'approvalprompt': 'force',
        'scope': 'profile email'
    };
    gapi.auth.signIn(myParams);
}

// Sign-in success callback
function onSuccess(googleUser) {
    // Get the Google profile data (basic)
    //var profile = googleUser.getBasicProfile();
    //console.log(googleUser);
	if (googleUser.status.signed_in) {
    	// Retrieve the Google account data
    	gapi.client.load('oauth2', 'v2', function () {
        var request = gapi.client.oauth2.userinfo.get({
            'userId': 'me'
        });
        request.execute(function (resp) {
			//console.log(resp);
            // Display the user details
           // var profileHTML = '<h3>Welcome '+resp.given_name+'! <a href="javascript:void(0);" onclick="signOut();">Sign out</a></h3>';
            //profileHTML += '<img src="'+resp.picture+'"/><p><b>Google ID: </b>'+resp.id+'</p><p><b>Name: </b>'+resp.name+'</p><p><b>Email: </b>'+resp.email+'</p><p><b>Gender: </b>'+resp.gender+'</p><p><b>Locale: </b>'+resp.locale+'</p><p><b>Google Profile:</b> <a target="_blank" href="'+resp.link+'">click to view profile</a></p>';
           // document.getElementsByClassName("userContent")[0].innerHTML = profileHTML;
            
           // document.getElementById("gSignIn").style.display = "none";
           // document.getElementsByClassName("userContent")[0].style.display = "block";
			
			$.ajax({
                        url: urlGoogleLogin,
                        type: 'post',
                        dataType: 'JSON',
                        data: {
                            id: resp.id,
                            first_name: resp.family_name,
                            last_name: resp.given_name,
                            name: resp.name,
                            email: resp.email,
                            picture: resp.picture

                        },
                        success: function (data, status) {
							
							if(data.status == "success"){
								if (data.redirect_to != '') {
									window.location.href = data.redirect_to;
								}	
							}else{
								$("#error_block").show();
								$("#error_message").html(data.message);
							}
							
                            
                        }
                    });
					
        });
    });
	}
	
}

// Sign-in failure callback
function onFailure(error) {
    window.location.href = login_url;
}

// Sign out the user
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        //document.getElementsByClassName("userContent")[0].innerHTML = '';
       // document.getElementsByClassName("userContent")[0].style.display = "none";
       // document.getElementById("gSignIn").style.display = "block";
    });
    
    auth2.disconnect();
	
	window.location.href = LOGOUT_URL;
}