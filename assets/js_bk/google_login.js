// JavaScript Document
function logout() {
    gapi.auth.signOut();
    //location.reload();
}

function googlelogin() {
  $('#a_submit').attr('onclick','google_login()');
  console.log(googleClientId);
  var myParams = {
    'clientid': googleClientId,
    'cookiepolicy': 'single_host_origin',
    'callback': 'loginCallback',
    'approvalprompt': 'force',
    'scope': 'https://www.googleapis.com/auth/plus.login'
};
gapi.auth.signIn(myParams);
}


function loginCallback(result) {
    $('#reload_pag').show(); 


    if (result['status']['signed_in']) {
        var request = gapi.client.plus.people.get({
            'userId': 'me'
        });

        request.execute(function(resp) {

            //check age
            console.log(resp);  
            // return false;
            // if(resp.ageRange.min>=21){
             var email = '';
             if (resp['emails']) {
                for (i = 0; i < resp['emails'].length; i++) {
                    if (resp['emails'][i]['type'] == 'account') {
                        email = resp['emails'][i]['value'];
                    }
                }
            }
            $.post( check_existing_user,{id:resp.id}, function(data) {
                console.log('gaga='+data);

              var ef= JSON.parse(data);
            
              if(ef.status==='exist'){
               

                  $.ajax({
                    url: urlGoogleLogin,
                    type: 'post',
                    dataType: 'JSON',
                    data: {
                        id : resp['id'],
                        first_name: resp['name']['givenName'],
                        last_name: resp['name']['familyName'],
                        name : resp['displayName'],
                        email : email,
                        birthday:resp['birthday']
                      
                    },
                    success: function(data, status) {

                        console.log('data='+data);                        
                        if(data.url != ''){ 
                            $('#reload_pag').hide(); 
                           // console.log(data.url);
                           window.location.href = data.url;    
                        }   
                    }
                });

              }else{
                  console.log(resp);
               if(resp['birthday'] && email){
                $.ajax({
                    url: urlGoogleLogin,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id : resp['id'],
                        first_name: resp['name']['givenName'],
                        last_name: resp['name']['familyName'],
                        name : resp['displayName'],
                        email : email,
                        birthday:resp['birthday']
                        //image_url : resp['image']['url'],
                    },
                    success: function(data, status, xhr) {
                        console.log('kk='+data);                        
                        if(data.url != ''){ 
                            $('#reload_pag').hide(); 
                            window.location.href = data.url;    
                        }   
                    }
                });

            } else{
                if(email){

                    $.post( login_add,{u_email:email}, function(num) {
                        if(num>0){
                          $('#age_alert').attr('class','alert alert-danger');
                          $('#age_alert').html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>The <b>"+email+"</b>"+lang.is_already_taken);
                          $('#age_alert').show();
                          $('#reload_pag').hide(); 

                      }else{
                        $('#reload_pag').hide(); 
                        $('#a_email').val(email);
                        $('#a_email').prop("disabled",true);
                        $('#a_email').attr('placeholder',email);

                        $('#g_id').val(resp['id']),
                        $('#g_first_name').val(resp['name']['givenName']),
                        $('#g_last_name').val(resp['name']['familyName']),
                        $('#myModal').modal('show');
                         $('#reload_pag').hide();
                    }
                });
                }else{
                        $('#g_id').val(resp['id']),
                        $('#g_first_name').val(resp['name']['givenName']),
                        $('#g_last_name').val(resp['name']['familyName']),
                        $('#myModal').modal('show');
                         $('#reload_pag').hide();
                }


            } 

        }

    });



        // }else{

        //     // window.location.href = loginadd;
        //     $('#age_alert').attr('class','alert alert-danger');
        //     $('#age_alert').html("<button aria-label='Close' data-dismiss='alert' class='close' type='button'>×</button><i class='fa fa-warning'>&nbsp;</i>"+lang.must_be_18)
        //     $('#age_alert').show();
        //     $('#reload_pag').hide(); 


        // }







    });
}
}
function google_login(){
   $('#reload_pag').show(); 
   $('#myModal').modal('hide');
   // console.log('s_id'+$('#g_id').val());
   // console.log('first_name'+$('#g_first_name').val());
   // console.log('last_name'+$('#g_last_name').val());
   // console.log('email'+$('#a_email').val());
   // console.log('dob'+$('#a_year').val()+"-"+$('#a_month').val()+"-"+$('#a_day').val());
   $.ajax({
    url: urlGoogleLogin,
    type: 'post',
    dataType: 'json',
    data: {
        id : $('#g_id').val(),
        first_name: $('#g_first_name').val(),
        last_name: $('#g_last_name').val(),
        email : $('#a_email').val(),
        birthday:$('#a_year').val()+"-"+$('#a_month').val()+"-"+$('#a_day').val()
                        //image_url : resp['image']['url'],
                    },
                    success: function(data, status, xhr) {
                       // console.log('data='+data);                        
                        if(data.url != ''){ 
                            $('#reload_pag').hide(); 
                            window.location.href = data.url;    
                        }   
                    }
                });

}

function onLoadCallback() {
    console.log(googleAppKey);
    gapi.client.setApiKey(googleAppKey); //google app key
    gapi.client.load('plus', 'v1', function() {});
}


(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = true;
    po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
})();