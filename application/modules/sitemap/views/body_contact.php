<div class="col-md-9 col-sm-8 content_sec">                
            <div class="product_container login-page contact-page">
            <h3><?php echo lang('contact_us'); ?></h3>
            <p class="alert alert-success"><?php echo lang('thank_you_contact'); ?></p>
            <form> 
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('fullname_username'); ?></p> <input type="text" name="fname" class="form-control"> </div>
            <div class="col-xs-6"><p><?php echo lang('email'); ?></p> <input type="text" name="email" class="form-control"> </div>
            </div>
            
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('mobile_or_phone'); ?></p> <input type="text" name="mobile" class="form-control"> </div>
            <div class="col-xs-6"><div class="row"><span class="col-xs-4  col-sm-12 col-md-4">
            <p><?php echo lang('captcha_code'); ?></p> <figure><img src="images/capcha.jpg" width="130" height="33" alt="captcha code"></figure>
            </span>
            <span class="col-xs-8 col-sm-12 col-md-8"><p><?php echo lang('enter_cap_code'); ?></p> <input type="text" name="security_code" class="form-control"></span>
            </div></div>
            </div>
            <div class="form-group"><p><?php echo lang('message'); ?></p>
            <textarea class="form-control" name="message" rows="5"></textarea>
            </div>
            <button class="btn btn-danger main_btn"><?php echo lang('send'); ?></button>
			</form>
            </div>
            
            
            <div class="clearfix"></div>
            
            </div>
          
        