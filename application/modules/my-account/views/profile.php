<form name="profile_update" action="" method="post" class="login-form" enctype="multipart/form-data">
 <div class="row">
                        <div class="col-12">
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="header">
                                    <h4 class="title"><?php echo lang('profile_personal_details');?></h4>
                                    
                                </div>
                              <?php
							if ($this->session->flashdata('success_message')) {
								?>
                                <div class="alert alert-success alert-dismissible fade show">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><?php echo $this->session->flashdata('success_message') ?></p>
                        </div>
								
								<?php
							}
							?>  
                            
                            <?php
							if ($this->session->flashdata('error_message')) {
								?>
                                <div class="alert alert-danger alert-dismissible fade show">
                            <span class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </span>
                            <p><?php echo $this->session->flashdata('error_message') ?></p>
                        </div>
								
								<?php
							}
							?>
							
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                <?php $gender_post = set_value('gender', $profile->gender); ?>
                                    <label for="firstName"><?php echo lang('register_gender');?> <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" id="gender">
                                        <option value="" selected="selected">Select Gender</option>                    
                                        <option value="M" <?php if($gender_post == "M"){ echo 'selected="selected"';}?> ><?php echo lang('register_gender_male');?></option>
                                        <option value="F" <?php if($gender_post == "F"){ echo 'selected="selected"';}?>><?php echo lang('register_gender_female');?></option>
                                      </select>
                                      <?=form_error('gender') ?>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('register_fname'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="fname" value="<?php echo set_value("fname", $profile->first_name); ?>" class="form-control">
                                    <?= form_error('fname') ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('register_lname'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="lname" value="<?php echo set_value("lname", $profile->last_name, $profile->last_name); ?>" class="form-control">
                                    <?= form_error('lname') ?>
                                </div>
                            </div>
                            
                            <div class="row">
                    
              <div class="col-md-6 mb-3">
                <label for="firstName"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>
                <input type="text" name="email" id="email" value="<?php echo set_value('email', $profile->email); ?>" class="form-control">
                  <?= form_error('email') ?>
                  <span id="emailExist"></span>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName"><?php echo lang('dob'); ?> <span class="text-danger">*</span></label>

                    <div class="row"> 
                      <div class="col-md-4" style="padding-right:5px;">
                      
                      <?php
                                    $dobyear = $this->input->post('dobyear') ? $this->input->post('dobyear') : '';
                                    $dobmonth = $this->input->post('dobmonth') ? $this->input->post('dobmonth') : '';
                                    $dobday = $this->input->post('dobday') ? $this->input->post('dobday') : '';

                                    $db_dobyear = !empty($profile->dob_year) ? $profile->dob_year : '';
                                    $db_dob_month = !empty($profile->dob_month) ? $profile->dob_month : '';
                                    $db_dob_day = !empty($profile->dob_day) ? $profile->dob_day : '';

                                    $sel_dobyear = !empty($dobyear) ? $dobyear : $db_dobyear;
                                    $sel_dobmonth = !empty($dobmonth) ? $dobmonth : $db_dob_month;
                                    $sel_dobday = !empty($dobday) ? $dobday : $db_dob_day;
                                    ?>
                                    
                     
                      <select name="dobday" id="dobday" class="form-control" <?php if($db_dob_day){ echo 'disabled="disabled"';}?> >
                       <option value=""><?php echo lang('day'); ?></option>
                                        <?php
                                        for ($j = 1; $j <= 31; $j++) {
                                            ?>
                                            <option value="<?php echo date("d", mktime(0, 0, 0, 1, $j, 2000)) ?>" <?php if (date("d", mktime(0, 0, 0, 1, $j, 2000)) == $sel_dobday) echo 'selected="selected"'; ?>><?php echo date("d", mktime(0, 0, 0, 1, $j, 2000)) ?></option>
                                            <?php
                                        }
                                        ?>
                      </select>
                      <?= form_error('dobday') ?>
                      </div> 
                      <div class="col-md-4" style="padding:0px 5px;">
                      <select name="dobmonth" id="dobmonth" class="select_mid form-control"  <?php if($db_dob_month){ echo 'disabled="disabled"';}?>  >
                         <?php
                                        foreach ($this->general->get_month('en') as $key => $val) {
                                            ?>
                                            <option value="<?php echo $key; ?>" <?php if ($key == $sel_dobmonth) echo 'selected="selected"'; ?>><?php echo $val; ?></option>
                                        <?php }
                                        ?>
                                                    ?>
                      </select>
                      <?= form_error('dobmonth') ?>
                      </div> 
                      <div class="col-md-4" style="padding-left:5px;">
                      <select name="dobyear" id="dobyear" class="select_last form-control"  <?php if($db_dobyear){ echo 'disabled="disabled"';}?> >
                        <option value=""><?php echo lang('year'); ?></option>
						  <?php
                          for ($j = date("Y"); $j > 1900; $j--) {
                              ?>
                              <option value="<?php echo $j; ?>" <?php if ($j == $sel_dobyear) echo 'selected="selected"'; ?>><?php echo $j; ?></option>
                          <?php }
                          ?>
                      </select>
                      <?= form_error('dobyear') ?>
                      <input type="hidden" name="dob_status" value="<?php if($sel_dobyear){ echo "1";}else{echo "0";}?>" />
                      </div> 
                      <div id="dob" style="padding-left:15px;"></div>
                    </div>
                    
              </div>
            </div>
            
            				
              
                            <?php /*?><div class="row">                              
                              <div class="col-md-6 mb-3">
                                <label><?php echo lang('mobile_number'); ?> <span class="text-danger">*</span></label>
                                <?php
									$user_mobile = $profile->mobile;
									if($this->input->cookie(SESSION.'mobile_number'))
										$user_mobile = $this->input->cookie(SESSION.'mobile_number');
								?>
								
                                <input type="tel" name="mobile" id="mobile" class="form-control" value="<?php echo set_value("mobile", $user_mobile); ?>">
                                  <?= form_error('mobile') ?>
                                  <input type="hidden" name="mobile_old" value="<?php echo $profile->mobile;?>" />
                              </div>
                              <?php 
							  		//echo $this->input->cookie(SESSION.'verification_code');exit;
							  		if($this->session->userdata(SESSION.'verification_code')) 
									 $verify_field = 'style="display:block"'; 
									else if ($this->input->cookie(SESSION.'verification_code'))
									 $verify_field = 'style="display:block"'; 
									else
										$verify_field = 'style="display:none"'; 
									
							  ?>
                              <div class="col-md-6 mb-3" <?php echo $verify_field;?> >
                                <label><?php echo lang('verify_code'); ?> <span class="text-danger">*</span></label>
                                <input type="number" name="verification_code" value="<?php echo set_value('verification_code'); ?>" class="form-control">
                                <?= form_error('verification_code') ?> 
                              </div>
                            </div><?php */?>                                                       
                            
                            <div class="mt-30">
                                <div class="header">
                                    <h4 class="title"><?php echo lang('profile_billing_addr');?></h4>
                                    
                                </div>
                                
                            
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('profile_address'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" value="<?php echo set_value("address", $profile->address); ?>" name="address" class="form-control">
                                    <?= form_error('address') ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('profile_address2'); ?></label>
                                    <input type="text" name="address2" value="<?php echo set_value("address1", $profile->address2); ?>" class="form-control">
                                    <?= form_error('address2') ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('profile_city'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="city" id="city" value="<?php echo set_value("city", $profile->city); ?>">
                                    <?= form_error('city') ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label><?php echo lang('profile_post_code'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="zip" id="zip" value="<?php echo set_value("zip", $profile->post_code); ?>">
                                    <?= form_error('zip') ?>
                                </div>
                            </div>
            
            				<div class="row">
                            <div class="col-md-6 mb-3"  id="sel_state">
                              <label><?php echo lang('state'); ?> <span class="text-danger">*</span></label>
                              <select name="state" class="country form-control">
                                <option value="" selected="selected"><?php echo lang('select_a_state'); ?></option>
                                            <?php
                                            foreach ($this->general->get_indian_states() as $val) {
                                                ?>
                                                <option value="<?php echo $val->city_state; ?>"  <?php if ($profile->state == $val->city_state) echo 'selected=selected'; ?>  ><?php echo $val->city_state; ?></option>
                                            <?php } ?>
                              </select>
                              <?= form_error('state') ?>
                            </div>
                            <div class="col-md-6 mb-3">
                              <label><?php echo lang('country'); ?> <span class="text-danger">*</span></label>
                              
                                    <?php
                                    $country = $this->input->post('country');
                                    $db_country = !empty($profile->country) ? $profile->country : '';
                                    $sel_country = !empty($country) ? $country : $db_country;
                                    ?>
                                    <select name="country" class="country form-control" id="sel_country">
                                        <option value="" selected="selected"><?php echo lang('select_a_country'); ?></option>
                                        <?php
                                        foreach ($this->general->get_country() as $val) {
                                            ?>
                                            <option value="<?php echo $val->id; ?>" <?php if ($sel_country == $val->id) echo 'selected=selected'; ?>><?php echo $val->country; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?= form_error('country') ?>
                            </div>
                            
                          </div>
              
                            <div class="row">
                            <div class="col-md-12 mt-4 mb-3 text-center">
                            <button type="submit" class="custom-button"><?php echo lang('register_bttn_submit');?></button>
                            </div>
                        </div>
                            
                            </div>
                            </div>
                        </div>
                        
                        
                        
                        
                    </div>
</form>

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

<script>
    $(document).ready(function () {
        $('.mobile_verify').on('click', function () {
            //alert();
            var mobile_number = $('#mobile').val();
            mobile_number = mobile_number.replace(/\s+/g, "");
            //alert(mobile_number);
            //var characterReg = /^[9|8][0-9]{13}$/;
            //if(mobile_number.length == 13 && characterReg.test(mobile_number))
            if (mobile_number.length >= 10 && mobile_number.length <= 14)
            {

                $.ajax({
                    type: 'POST',
                    url: "<?php echo $this->general->lang_uri('/' . MY_ACCOUNT . '/user/mobile_verification'); ?>",
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

        $("#sel_country").change(function () {
            // alert('asdf');
            sel = $(this).val();
            if (sel != '') {
                $("#mobile").prop("disabled", false);
                c_code = $('#sel_country').find(':selected').data('country_code');
                $("#mobile").val(c_code);

                if (sel == '5') {
                    $("#sel_state").css('visibility', 'visible');

                } else {
                    $("#sel_state").css('visibility', 'hidden');
                    $('[name=state]').get(0).selectedIndex = 0;

                }
            }
        });

    });
</script>