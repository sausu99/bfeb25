<form name="profile_update" action="" method="post" class="login-form" enctype="multipart/form-data">
 <div class="row">
                        <div class="col-12">
                            <div class="dash-pro-item mb-30 dashboard-widget">
                                <div class="header">
                                    <h4 class="title">Personal Mobile</h4>
                                    
                                </div>
                                
                            
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
                            
                            
                            
                            
                            
                            
            
            				
              
                            <div class="row">                              
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
                              
                              
                              
                              
                            </div>                                                       
                            
                            <div class="mt-30">
                                                                
                            <div class="row">
                            <div class="col-md-12 mt-4 mb-3 text-center">
                            <button type="submit" class="custom-button">SUBMIT</button> 
                            </div>
                            <div class="col-md-12 mb-3" align="center" <?php echo $verify_field;?> >
                                <em>You did not get OTP. Click on <a href="<?php echo $this->general->lang_uri('/'.MY_ACCOUNT.'/user/send_verification_code');?>">Re-send OTP</a> to get new OTP.</em>
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