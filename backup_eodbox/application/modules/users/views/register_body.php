<script src="https://apis.google.com/js/client:platform.js?onload=renderButton" async defer></script>
<script>

    var check_unique_email_url = "<?php echo $this->general->lang_uri('/users/login/check_unique_email'); ?>";
    var check_email_username_url = "<?php echo $this->general->lang_uri('/users/register/check_username') ?>";
    var check_email_url = "<?php echo $this->general->lang_uri('/users/register/check_email') ?>";


</script>


<!--============= Hero Section Starts Here =============-->
    <div class="hero-section">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="<?php echo $this->general->lang_uri();?>">Home</a>
                </li>
                
                <li>
                    <span>Sign Up</span>
                </li>
                
            </ul>
        </div>
        <div class="bg_img hero-bg bottom_center" data-background="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>/banner/hero-bg.png"></div>
    </div>
    <!--============= Hero Section Ends Here =============-->


    <!--============= Account Section Starts Here =============-->
    <section class="account-section padding-bottom">
        <div class="container">
            <div class="account-wrapper mt--100 mt-lg--440">
                <div class="left-side">
                    <div class="section-header">
                        <h2 class="title">SIGN UP</h2>
                        <p>We're happy you're here! Register in only two clicks.</p>
                    </div>
                    <ul class="login-with">
                        <li>
                            <a href="javascript:void(0);" onclick="return facebookLogin();"><i class="fab fa-facebook"></i>Log in with Facebook</a>
                        </li>
                        <li>
                        <?php /*?><div id="gSignIn"></div><?php */?>
                            <a href="javascript:void(0);" onclick="return googlelogin();"><i class="fab fa-google-plus"></i>Log in with Google</a>
                        </li>
                    </ul>
                    <div role="alert" id="error_block" class="alert alert-danger" style="display:none;">
        <span aria-label="Close" data-dismiss="alert" class="close" type="button">×</span>
        <i class="fa fa-warning">&nbsp;</i><span id="error_message"></span></div>
                    <div class="or">
                        <span>Or</span>
                    </div>
                    <form class="login-form" id="register-form" method="post">
                    <h4 class="mb-3">Personal Details</h4>
                    <div class="row">
                    
              <div class="col-md-6 mb-3">
                <label for="firstName">Gender</label>
                <select name="gender" class="form-control" id="gender" required>
                    <option value="" selected="selected">Select Gender</option>                    
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                  </select>
                  <?= form_error('gender') ?>
              </div>
              
            </div>
                    <div class="row">
                    
              <div class="col-md-6 mb-3">
                <label for="firstName"><?php echo lang('register_fname'); ?></label>
                <input type="text" class="form-control" name="fname" value="<?php echo set_value('fname'); ?>" required>
                <?= form_error('fname') ?>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName"><?php echo lang('register_lname'); ?></label>
                <input type="text" name="lname" value="<?php echo set_value('lname'); ?>" class="form-control">
                  <?= form_error('lname') ?>
              </div>
            </div>
            
            		<div class="row">
                    
              <div class="col-md-6 mb-3">
                <label for="firstName"><?php echo lang('email'); ?></label>
                <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" class="form-control">
                  <?= form_error('email') ?>
                  <span id="emailExist"></span>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName"><?php echo lang('dob'); ?></label>

                    <div class="row"> 
                      <div class="col-md-4" style="padding-right:5px;">
                      <?php
                                                $dobyear = $this->input->post('dobyear') ? $this->input->post('dobyear') : '';
                                                $dobmonth = $this->input->post('dobmonth') ? $this->input->post('dobmonth') : '';
                                                $dobday = $this->input->post('dobday') ? $this->input->post('dobday') : '';
                                                ?>
                      <select name="dobday" id="dobday" class="form-control">
                        <option value=""><?php echo lang('day'); ?></option>
                        <?php
                                                    for ($j = 1; $j <= 31; $j++) {
                                                        ?>
                        <option value="<?php echo date("d", mktime(0, 0, 0, 1, $j, 2000)) ?>" <?php if (date("d", mktime(0, 0, 0, 1, $j, 2000)) == $dobday) echo 'selected="selected"'; ?>><?php echo date("d", mktime(0, 0, 0, 1, $j, 2000)) ?></option>
                        <?php
                                                    }
                                                    ?>
                      </select>
                      <?= form_error('dobday') ?>
                      </div> 
                      <div class="col-md-4" style="padding:0px 5px;">
                      <select name="dobmonth" id="dobmonth" class="select_mid form-control" >
                        <option value=""><?php echo lang('month'); ?></option>
                        <?php
                                                    foreach ($this->general->get_month('en') as $key => $val) {
                                                        ?>
                        <option value="<?php echo $key; ?>" <?php if ($key == $dobmonth) echo 'selected="selected"'; ?>><?php echo $val; ?></option>
                        <?php }
                                                    ?>
                      </select>
                      <?= form_error('dobmonth') ?>
                      </div> 
                      <div class="col-md-4" style="padding-left:5px;">
                      <select name="dobyear" id="dobyear" class="select_last form-control">
                        <option value=""><?php echo lang('year'); ?></option>
                        <?php
                                                    for ($j = date("Y"); $j > 1900; $j--) {
                                                        ?>
                        <option value="<?php echo $j; ?>" <?php if ($j == $dobyear) echo 'selected="selected"'; ?>><?php echo $j; ?></option>
                        <?php }
                                                    ?>
                      </select>
                      <?= form_error('dobyear') ?>
                      
                      </div> 
                      <div id="dob" style="padding-left:15px;"></div>
                    </div>
                    
              </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                  <label><?php echo lang('country'); ?></label>
                  <select name="country" class="country form-control" id="sel_country" required>
                    <option value="" selected="selected"><?php echo lang('select_a_country'); ?></option>
                    <?php
                                        $d_flt = '5';
                                        foreach ($this->general->get_country() as $val) {
                                            ?>
                    <option value="<?php echo $val->id; ?>" <?php if ($val->id == '5') echo 'selected'; ?>  <?php echo set_select('country', $val->id); ?> data-country_code="<?php echo $val->country_code; ?>">
                    <?php echo $val->country; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <?= form_error('country') ?>
                </div>
                <div class="col-md-6 mb-3"  id="sel_state">
                  <label><?php echo lang('state'); ?></label>
                  <select name="state" class="country form-control">
                    <option value="" selected="selected"><?php echo lang('select_a_state'); ?></option>
                    <?php
                                        foreach ($this->general->get_indian_states() as $val) {
                                            ?>
                    <option value="<?php echo $val->city_state; ?>"  <?php echo set_select('country', $val->city_state); ?>><?php echo $val->city_state; ?></option>
                    <?php } ?>
                  </select>
                  <?= form_error('state') ?>
                </div>
              </div>
              
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label><?php echo lang('profile_city'); ?></label>
                    <input type="text" name="city" value="<?php echo set_value('city'); ?>" class="form-control">
                    <?= form_error('city') ?>
                  </div>
                  <?php /*?><div class="col-md-6 mb-3">
                    <label><?php echo lang('mobile_number'); ?></label>
                    <input type="text" name="mobile" id="mobile" placeholder="Ex. 9812345678" value="<?php echo set_value('mobile'); ?>" class="form-control" maxlength="15" disabled>
                      <input type="hidden" name="final_mobile" value="" id="final_mobile">
                      <?= form_error('mobile') ?>
                  </div><?php */?>
                </div>
                <h4 class="mb-3">Login Details</h4>
             
                        <div class="form-group mt-30" style="margin-bottom:0px;">
                            <label for="login-email"><i class="far fa-user"></i></label>
                            <input type="text" name="user_name"  value="<?php echo set_value('user_name'); ?>" placeholder="<?php echo lang('user_name'); ?>">
                            
                        </div>
                        <span id="userName"></span>
                        <?= form_error('user_name') ?>
                        <div class="form-group mt-30" style="margin-bottom:0px;">
                            <label for="login-pass"><i class="fas fa-lock"></i></label>
                            <input type="password" name="password" id="login-pass" value="<?php echo set_value('password'); ?>" placeholder="Password">
                            <span class="pass-type"><i class="fas fa-eye"></i></span>
                            
                        </div>
                        <span id="Pass"></span>
                        <?= form_error('password') ?>
                        <div class=" mt-30 mb-30">
                        <div class="form-group checkgroup">
                            <input type="checkbox" id="t_c" name="t_c" value="yes">
                            
                            <label for="check">
                                By Registering you agree to abide by eodbox Policies.
                            </label>
                            
                        </div>
                        <div id="termCon" class="text-danger"></div>
                  			<?= form_error('t_c') ?>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="custom-button" id="btnRegister"><?php echo lang('sign_up'); ?></button>
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div style="text-align: center;">
                            <span>Visit:</span>
                            <?php 
							 $first_row_cms =  $this->general->get_cms_lists(array('3','4','43','44')); 
							 if($first_row_cms){foreach($first_row_cms as $fcms){?>
							 <span><a href="<?php echo $this->general->lang_uri("/page/".$fcms->cms_slug);?> "><?php echo $fcms->heading;?></a></span>, 
							 <?php }}?>
                        </div>

                    </form>
                </div>
                <div class="right-side cl-white">
                    <div class="section-header mb-0">
                        <h3 class="title mt-0">ALREADY HAVE AN ACCOUNT?</h3>
                        <p>Log in and go to your Dashboard.</p>
                        <a href="<?php echo $this->general->lang_uri('/users/login')?>" class="custom-button transparent">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Account Section Ends Here =============-->
<div class="modal fade" id="verification_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h3 class="modal-title" id="Login_ModalLabel"><?php echo lang('mobile_verification'); ?></h3>
      </div>
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
<script>
    $(document).ready(function () {
        $("#mobile").prop("disabled", false);
        $("#mobile").val('+91');
        //$("#sel_state").css('visibility', 'hidden');
        $('#mobile_verify').on('click', function () {
            //alert();
            var mobile_number = $('#mobile').val();
            mobile_number = mobile_number.replace(/\s+/g, "");
//            alert(mobile_number);
            var characterReg = /^(\+91-|\+91|0)?\d{10}$/;
            //if(mobile_number.length == 13 && characterReg.test(mobile_number))
//            alert(mobile_number.length);
            if (mobile_number.length >= 10 && mobile_number.length <= 14 && characterReg.test(mobile_number))
            {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo $this->general->lang_uri('/users/register/mobile_verification'); ?>",
                    data: {mobile: mobile_number},
                    async: true,
                    beforeSend: function () {

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
            } else {
                $('.modal-body').html('Enter valid mobile number');
                $('#verification_model').modal();
            }

        });


        $("#mobile").keyup(function () {
            num = $(this).val();
            c_code = '+91';//$('#sel_country').find(':selected').data('country_code');

            if (num.substring(0, 1) != '+' && num.length <= 10) {
                $("#mobile").val(c_code + num);
            } else {
                $("#mobile").val(num);
            }
//            $("#final_mobile").val(final_num);

        });


        $("#sel_country").change(function () {
            sel = $(this).val();
            if (sel != '') {
                $("#mobile").prop("disabled", false);
                c_code = $('#sel_country').find(':selected').data('country_code');
                $("#mobile").val(c_code);

                if (sel == '5') {
                    $("#sel_state").css('visibility', 'visible');

                } else {
                    $("#sel_state").css('visibility', 'hidden');
                }
            } else {
                $("#mobile").prop("disabled", true);
            }
        });
    });
</script>

<script type="text/javascript">
    var FacebookAppID = '<?php echo FACEBOOK_APP_ID; ?>';
    var login_add = '<?php echo $this->general->lang_uri('/users/login/check_unique_email/') ?>';
    var urlGoogleLogin = '<?php echo $this->general->lang_uri("/users/login/google_login/"); ?>';
    var check_existing_user = '<?php echo $this->general->lang_uri("/users/login/check_existing_user/"); ?>';
    var googleAppKey = '<?php echo GOOGLE_APP_KEY; ?>';
    var googleClientId = '<?php echo GOOGLE_CLIENT_ID; ?>';
</script>

<script>var urlFacebookLogin = "<?php echo $this->general->lang_uri('/users/login/facebook_login') ?>";</script>
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH); ?>facebook_login.js"></script> 
<script type="text/javascript" src="<?php echo site_url(MAIN_JS_DIR_FULL_PATH); ?>google_login.js"></script> 