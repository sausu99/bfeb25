<script>
 
  $(document).ready(function(){ 
    $('#myModal').modal('show');
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
      $('#email_error').html("<?php echo lang('invalid_email'); ?>");
     pat_err=1;
    }
   
$.post( "<?php echo $this->general->lang_uri('/users/login/check_unique_email') ?>",{u_email:$('#a_email').val()}, function(num) {
  console.log(num+"-"+uniquness_err);
  if(num>0){
  
 $('#email_error').html("<?php echo lang('email_already_exist'); ?>");
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
        $("#date_error").html("<?php echo lang('age_valid_18'); ?>") ;
      else
       $("#date_error").html('') ;
    if($('#a_day').val() && $('#a_month').val() && $('#a_year').val() && $('#a_email').val() && currldate > myldate && pat_err==0 && uniquness_err==0 ){
     $('#a_submit').prop("disabled",false);
   }else{
    $('#a_submit').prop("disabled",true);        
  }
});
  });
</script>
<script>
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
      url:'<?php echo $this->general->lang_uri('/users/register/check_username')?>',
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
          UsAvailResult.html('<span class="text-danger">"<?php echo lang('username_already_use'); ?>"</span>');
          
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
      url  : '<?php echo $this->general->lang_uri('/users/register/check_email')?>',
      success: function(responseText){ // Get the result and asign to each cases
        if(responseText == 0){
          EmailAvailResult.html('');
          email_error=0;
           check_function(user_error,email_error);
        }
        else if(responseText > 0){
         
          email_error=1;
           check_function(user_error,email_error);
          EmailAvailResult.html('<span class="text-danger">"<?php echo lang('username_already_use'); ?>"</span>');
          
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


</script>

<?php
//$this->session->set_userdata('fb_data', $fb_data);
$fb_user_register = $this->session->userdata('me');


$fb_data = array("first_name"=>$fb_user_register['first_name'],"last_name"=>$fb_user_register['last_name'],"email"=>$fb_user_register['email'],
                  "username"=>$fb_user_register['username'],"country"=>$this->session->userdata('country') );

if(!$this->session->userdata('is_fb_user'))
{
  $this->session->set_userdata('is_fb_user', 'No');
}

?>
  <div class="main-content">
    <div class="main_body">
      <div class="container">
      <div class="row">
      <div class="sec_title relative"><div class="skew_bg"></div> <?php echo lang('join_us_today'); ?></div>
        <div class="product_container login-page">
            <div class="col-sm-8">
            <form method="post" class="register-page" id="register-form">
            <h3><?php echo lang('login_detail'); ?></h3>
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('user_name'); ?></p> <input type="text"  name="user_name"  value="<?php echo set_value('user_name'); ?>" class="form-control" id="unamec">
              <?=form_error('user_name')?>
               <span id="userExist"></span>
            </div>
            <div class="col-xs-6"><p><?php echo lang('email'); ?></p> <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" class="form-control">
              <?=form_error('email')?>
              <span id="emailExist"></span>
            </div>
            </div>
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('password'); ?></p> 
            <input type="password" name="password" id="psword" value="<?php echo set_value('password');?>" class="form-control">
            <?=form_error('password')?>
            </div>
            <div class="col-xs-6"><p><?php echo lang('confirm_password'); ?></p> <input type="password" name="re_password" value="<?php echo set_value('re_password'); ?>" class="form-control">
            <?=form_error('re_password')?>
            </div>
            </div>
            
            <h3><?php echo lang('personal_details'); ?></h3>
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('register_fname'); ?></p> <input type="text" name="fname" value="<?php echo set_value('fname'); ?>" class="form-control">
             <?=form_error('fname')?>
            </div>
            <div class="col-xs-6"><p><?php echo lang('register_lname'); ?></p> <input type="text" name="lname" value="<?php echo set_value('lname'); ?>" class="form-control">
                 <?=form_error('lname')?>
            </div>
            </div>
            <div class="bg_n">
            <div class="row">
            <div class="col-xs-6 form-group"><p><?php echo lang('mobile_number'); ?></p>
            <div class="row">
            <span class="col-xs-8">
            <input type="number" name="mobile" id="mobile" value="<?php echo set_value('mobile'); ?>" class="form-control" maxlength="10">
             <?=form_error('mobile')?>
            </span>
            <span class="col-xs-4">
            <a hidden="javascript:void(0);" class="btn btn-info btn-block" id="mobile_verify"><?php echo lang('verify'); ?></a>
            </span>
            </div>
            </div>
            
            <div class="col-xs-6 form-group"><p><?php echo lang('verify_code'); ?></p> 
            <input type="number" name="verification_code" value="<?php echo set_value('verification_code'); ?>" class="form-control">
             <?=form_error('verification_code')?>
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-xs-6"><p><?php echo lang('dob'); ?></p>
            <div class="row">
            <span class="col-xs-4">
            <?php  
              $dobyear=$this->input->post('dobyear')?$this->input->post('dobyear'):'';
              $dobmonth=$this->input->post('dobmonth')?$this->input->post('dobmonth'):'';
              $dobday=$this->input->post('dobday')?$this->input->post('dobday'):'';
            ?>
            <select name="dobday" id="dobday" class="form-control">
             <option value=""><?php echo lang('day'); ?></option>
                    <?php             
                     for ($j = 1; $j <= 31; $j++) {             
                    ?>
                    <option value="<?php echo date("d", mktime(0, 0, 0, 1, $j, 2000))?>" <?php if(date("d", mktime(0, 0, 0, 1, $j, 2000)) == $dobday)echo 'selected="selected"'; ?>><?php echo date("d", mktime(0, 0, 0, 1, $j, 2000))?></option>
                      <?php   
                       }  
                       ?>       
              </select>
              <?=form_error('dobday')?>
            </span>
            <span class="col-xs-4">
            <select name="dobmonth" id="dobmonth" class="select_mid form-control" >
            <option value=""><?php echo lang('month'); ?></option>
            <?php 
            foreach($this->general->get_month('en') as $key=>$val) {      
            ?>
            <option value="<?php echo $key;?>" <?php if($key == $dobmonth)echo 'selected="selected"'; ?>><?php echo $val;?></option>
            <?php   
            } ?>
            </select>
              <?=form_error('dobmonth')?>
            </span>
            <span class="col-xs-4">
            <select name="dobyear" id="dobyear" class="select_last form-control">
              <option value=""><?php echo lang('year'); ?></option>
        <?php   
            for ($j = date("Y"); $j > 1900; $j--) {             
            ?>
              <option value="<?php echo $j;?>" <?php if($j == $dobyear)echo 'selected="selected"'; ?>><?php echo $j;?></option>
            <?php   
             }  ?>  

            </select>
            <?=form_error('dobyear')?>
            </span>

      </div> 
      <div id="dob"></div>           
            </div>
               
            </div>
            
            <h3><?php echo lang('address_detail'); ?></h3>

            <div class="row">
            <div class=" form-group col-xs-6"><p><?php echo lang('profile_address'); ?></p> 
            <input type="text" name="address" value="<?php echo set_value('address'); ?>" class="form-control">
             <?=form_error('address')?>
            </div>
            <div class="form-group col-xs-6"><p><?php echo lang('profile_address2'); ?></p> 
            <input type="text" name="address2" value="<?php echo set_value('address2'); ?>" class="form-control">
             
            </div>
            </div>
            <div class="row form-group">
            <div class="col-xs-4"><p><?php echo lang('city'); ?></p> 
            <input type="text" name="city" value="<?php echo set_value('city'); ?>" class="form-control">
            <?=form_error('city')?>
            </div>
            <div class="col-xs-4">
            <p><?php echo lang('profile_post_code'); ?></p> 
            <input type="text" name="zip" value="<?php echo set_value('zip'); ?>" class="form-control">
             <?=form_error('zip')?>
            </div>
            <div class="col-xs-4">
            <p>Country</p>
             <select name="country" class="country form-control">
                <option value="" selected="selected"><?php echo lang('select_a_country'); ?></option>
                <?php 
                    foreach($this->general->get_country() as $val) {      
                  ?>
                  <option value="<?php echo $val->id;?>"  <?php echo set_select('country',$val->id);?>><?php echo $val->country;?></option>
              <?php }  ?>
                </select>
                <?=form_error('country')?>
                </div>
            </div>
            
            <ul class="btn-block bg_n form-group">
            <li>
            <input type="checkbox" id="f-option1" name="t_c" value="yes"><label for="f-option1"><?php echo lang('agree_term_condition'); ?></label><div class="check" id="term_con"></div>
            <?=form_error('t_c')?>
            </li>
            <li><input type="checkbox" id="f-option2" name="selector" value="yes"><label for="f-option2"><?php echo lang('it_is_long_established_fact'); ?> </label><div class="check"></div></li>
             <div class="check"><div class="inside"></div></div></ul>            
            <button class="btn btn-danger main_btn" id="btnRegister" type="submit"><?php echo lang('sign_up'); ?></button>
      </form>
            </div>
            <div class="col-sm-4">
            <h3> <?php echo lang('signup_using_social_media'); ?></h3>
            <label>&nbsp;</label>
            <div id="reload_pag" style="display:block;text-align:center;margin:0 auto;">
            <img src="<?php echo site_url(SITE_LOGO_PATH).'loading1.gif'; ?>" alt="<?php echo SITE_NAME; ?>" style="max-height:60px;width:auto;">

            </div>

            <div class="well">
            <div class="social_share">
            <a href="<?php if(isset($twitter_login_url))echo $twitter_login_url;?>" class="btn btn-info btn-block text-left"><i class="fa fa-twitter"></i> <?php echo lang('twitter'); ?></a>

          <a href="javascript:void(0)" class="btn btn-danger btn-block text-left" onclick="return googlelogin();"><i class="fa fa-google"></i> <?php echo lang('google_+'); ?></a>

            <a href="javascript:void(0)" class="btn btn-primary btn-block text-left" onclick="return facebookLogin();"><i class="fa fa-facebook-f"></i> <?php echo lang('facebook'); ?></a>
                </div>
            </div>
            </div>
            <div class="clearfix"></div>
            </div>
    </div>
  </div>
  </div>
  </div>
  <div class="modal fade" id="verification_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3 class="modal-title" id="Login_ModalLabel"><?php echo lang('mobile_verification'); ?></h3></div>
            <div class="modal-body">
            <label></label>
      <input type="text" class="form-control">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger main_btn btn_verify" data-dismiss="modal"><?php echo lang('ok'); ?></button>
            </div>
        </div>
    </div>
</div>


<!-- email form for social login if they are not available -->
<div id="myModal" class="modal fade modal-order modal-info modal-warning social_reg" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content start-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" title="Closed">&times;</button>
        <h4 class="modal-title"><?php echo lang('register'); ?></h4>
      </div>
    <form method="post" action="<?php $this->general->lang_uri().'/users/login/twitter_login'?>" name='socio_login' id="socio_login">
      <div class="modal-body">
        <div class="email_msg"><?php echo lang('hello'); ?> <strong><?php if($twi_user) echo preg_replace("/[^a-zA-Z]/", " ", $twi_user)." ";?></strong><?php echo lang('provide_info'); ?></div>
        <div class="row">
         <div class="col-md-6 col-sm-6 col-xs-12 ">
           <div class="form-group">
              <label><?php echo lang('email'); ?></label>
              <input type="email" id="a_email" name="t_email" class="form-control a_email" />
              <span id="email_error" class="text-danger">
              </span>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">
            
            
              <label><?php echo lang('dob'); ?>*</label>
              <div class="select-sec row">
                <select class="form-control a_day" name="t_dob_day" id="a_day">
                  <option value="" selected="selected" ><?php echo lang('day'); ?></option>
                  <?php for($i=1;$i<=31;$i++){
                    echo "<option value=$i ".set_select('dob_day', $i)."/>$i</option>";
                  }?>
                </select>
                <select class="form-control select-mid a_month" name="t_dob_month" id="a_month">
                  <option value=""><?php echo lang('month'); ?></option>
                  <?php
                  $months = $this->general->get_month('en');
                  $i=1;
                  foreach ($months as $month) {
                    echo "<option value=$i".set_select('t_dob_month', $i).">$month</option>";
                    $i++;
                  }
                  ?>
                </select>
                <select class="form-control a_year" name="t_dob_year" id="a_year">
                  <option value="" selected="selected" ><?php echo lang('year'); ?></option>
                  <?php
                  for($i=gmdate("Y");$i>=1940;$i--){
                    echo "<option value=$i".set_select('dob_year',$i).">$i</option>";
                  }?>
                </select>
              </div> <span id="date_error" class="text-danger"></span>
            </div>
          </div> 
          <div>
          <input type="hidden" name='twi_id' value="<?php if($twitter_id)echo $twitter_id;?>">
          <input type="hidden" name='twi_user' value="<?php if($twi_user)echo $twi_user;?>">
           <input type="hidden" id="g_first_name">
           <input type="hidden" id="g_last_name">
           <input type="hidden" id="g_id">
         </div>
         <div class="clearfix"></div>
       </div>
     </div>
     <div class="modal-footer">
     <div class="row">
            <div class="col-xs-6 pull-right">
              <div class="button_sec">  
                <button type="submit" class="btn btn-danger main_btn text-uppercase" style="text-align:right!important" id="a_submit"><i class="fa fa-check"></i> <?php echo lang('login'); ?>
                </button>
                </div>
            </div>
      <div class="col-xs-6 pull-left"><a href="javascript:void(0)" class="btn btn-danger main_btn" data-dismiss="modal" ><i class="fa fa-times"></i> <span><?php echo lang('cancel'); ?></span></a></div>
      <div class="clearfix"></div>
      </div>
    </div>
    </form>
  </div>
</div>
</div>
<script>
    $(document).ready(function () {
        $('#mobile_verify').on('click', function () {
      //alert();
            var  mobile_number = $('#mobile').val();
            mobile_number = mobile_number.replace(/\s+/g, "");
      //alert(mobile_number);
      //var characterReg = /^[9|8][0-9]{13}$/;
            //if(mobile_number.length == 13 && characterReg.test(mobile_number))
      if(mobile_number.length >=10  &&  mobile_number.length <= 14)
      {
        
                $.ajax({
                    type: 'POST',
                    url: "<?php echo $this->general->lang_uri('/users/register/mobile_verification');?>",
                    data: {mobile: mobile_number},
                    async: true,
                    beforeSend: function(){
            
                        $('.modal-body').html('<i class="fa fa fa-spinner fa-spin"></i>');
                        $('#verification_model').modal();
                    },
                    success: function (data) {
              //alert(data);
                        if (data != '' || data != undefined || data != null) {
                            $('.modal-body').html(data);
                            $('#verification_model').modal();
                        }
                    }
                });
            }else{
                $('.modal-body').html("<?php echo lang('enter_valid_mob'); ?>");
                $('#verification_model').modal();
            }

        });
    });
</script>
<script type="text/javascript">
  var FacebookAppID = '<?php echo FACEBOOK_APP_ID; ?>'; 
  var login_add='<?php echo $this->general->lang_uri('/users/login/check_unique_email/') ?>';
  var urlGoogleLogin = '<?php echo $this->general->lang_uri("/users/login/google_login/");?>';
  var check_existing_user='<?php echo $this->general->lang_uri("/users/login/check_existing_user/");?>';
  var googleAppKey = '<?php echo GOOGLE_APP_KEY; ?>';
  var googleClientId = '<?php echo GOOGLE_CLIENT_ID; ?>'; 
</script>

<script>var urlFacebookLogin="<?php echo $this->general->lang_uri('/users/login/facebook_login')?>";</script>
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>facebook_login.js"></script> 
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH);?>google_login.js"></script> 