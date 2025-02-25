
  $(document).ready(function(){ 
      $('#reload_pag').hide(); 
     var uniquness_err=0;
      var pat_err=0;
      $('#a_submit').prop("disabled",true);
      $('#a_email').keyup(function(){
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
     if(emailPattern.test($('#a_email').val())){
      $('#email_error').html('')
       pat_err=0;
    }  else{
      $('#email_error').html(lang.invalid_email);
     pat_err=1;
    }
$.post( check_unique_email_url,{u_email:$('#a_email').val()}, function(num) {
  console.log(num+"-"+uniquness_err);
  if(num>0){
 $('#email_error').html(lang.email_already_exist);
  uniquness_err=1;
  }else{
    $('#email_error').html('');
     uniquness_err=0;
  }
  });
      });
    
    $(".a_email, .a_day, .a_month, .a_year").change(function(){
      var d = $("#a_day").val();
      var m = $("#a_month").val();
      var y = $("#a_year").val();
      var myldate = new Date();
      myldate.setFullYear(y, m-1, d);
      var currldate = new Date();
      currldate.setFullYear(currldate.getFullYear() - 18);
      if (currldate < myldate)
        $("#date_error").html(lang.you_must_be_18) ;
      else
       $("#date_error").html('') ;
    if($('#a_day').val() && $('#a_month').val() && $('#a_year').val() && $('#a_email').val() && currldate > myldate && pat_err==0 && uniquness_err==0 ){
     $('#a_submit').prop("disabled",false);
   }else{
    $('#a_submit').prop("disabled",true);        
  }
});
  });


var baseUrl = '<?php echo base_url();?>';
// chek number
function filterText(no,val){
  var validList = "1234567890.";    
  var outString = '';
  var inChar;
  var i;
  
  for(i=0; i <= val.length-1; i++) {
    inChar = val.charAt(i);
    
    if (validList.indexOf(inChar) != -1) {
      outString = outString + inChar;
    }
  }       
  document.getElementById(no).value = outString;
}

// check username and emaiil

jQuery(function($) {
  // username
   var email_error=0;
    var user_error=0;
     $('#unamec').change(function() { // Keyup function for check the user action in input
   // alert('here');
    var mem_user = $(this).val(); // Get id
    var UsAvailResult = $('#userExist'); // Get the ID of the result where we gonna display the results
      UsAvailResult.html('Loading..'); // Preloader, use can use loading animation here
      
      var UrlTo = 'username='+mem_user;
      $.ajax({ // Send the username val to another checker.php using Ajax in POST menthod
      type : 'POST',
      data : UrlTo,
      url:check_email_username_url,
      success: function(responseText){ // Get the result and asign to each cases
	  console.log(responseText);
        if(responseText == 0){
            email_error=0;
           check_function(user_error,email_error);
          UsAvailResult.html('');
        }
        else if(responseText > 0){
          email_error=1;
           check_function(user_error,email_error);
          UsAvailResult.html('<span class="text-danger">'+lang.username_already+'</span>');
          
        }
      }
    });
  });
  
  $('#email').change(function() { // Keyup function for check the user action in input
    //alert('here');
      var email_error=0;
    var user_error=0;

    var mem_email = $(this).val(); // Get id
    var EmailAvailResult = $('#emailExist'); // Get the ID of the result where we gonna display the results
      EmailAvailResult.html('Loading..'); // Preloader, use can use loading animation here
      
      var UrlTo2 = 'email='+mem_email;
      $.ajax({ // Send the username val to another checker.php using Ajax in POST menthod
      type : 'POST',
      data : UrlTo2,
      url  : check_email_url,
      success: function(responseText){ // Get the result and asign to each cases
        if(responseText == 0){
          EmailAvailResult.html('');
          email_error=0;
           check_function(user_error,email_error);
        }
        else if(responseText > 0){
         
          email_error=1;
           check_function(user_error,email_error);
          EmailAvailResult.html('<span class="text-danger">'+lang.email_already_exist+'</span>');
          
        }
      }
    });
  });


  function check_function(user_error,email_error){
    if(user_error==0 && email_error==0)
          {
            $("#btnRegister").prop("disabled",false);
             $("#btnRegister" ).removeClass("disabled");
           
          }
        else
          {
            $('#btnRegister').prop('disabled', true);
             $("#btnRegister" ).addClass("disabled");

          }

}
  

});
