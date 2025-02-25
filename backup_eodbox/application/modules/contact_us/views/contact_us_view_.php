<script>
var capta = '<?php echo $this->session->userdata('word');?>';

function call_back(new_capta)
       {
     capta=new_capta;
     }
</script>
            <div class="col-md-9 col-sm-8 content_sec">                
            <div class="product_container login-page contact-page">
            
            
            <?php $add=$this->general->get_cms_pid('42');
              if($add){
            ?>
             <div class="office_address">
            <h3><?php echo lang('office_address');?></h3>
           <?php  
              echo $add->content;
            ?>
              </div>

            <?php } ?>
          
            <h3><?php echo lang('contact_us'); ?></h3>
            <?php //echo validation_errors(); ?>

              <?php if($this->session->flashdata('message')){?>
        <div role="alert" class="alert alert-success alert_msg">
         <?php echo $this->session->flashdata('message');?></div>
        <?php }?>
            <form method="post" id="contact-form" action="" enctype="multipart/form-data">
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('first_username'); ?></p>  <input type="text" name="fname" value="<?php echo set_value('fname');?>" class="form-control" tabindex="1" autofocus>
            
          <?=form_error('fname')?> </div>
            <div class="col-xs-6"><p><?php echo lang('email'); ?></p> <input type="email" name="email" value="<?php echo set_value('email');?>" class="form-control" tabindex="2">
          <?=form_error('email')?></div>
            </div>
            
            <div class="row form-group">
            <div class="col-xs-6"><p><?php echo lang('mobile_or_phone'); ?></p>  <input type="tel" name="mobile" value="<?php echo set_value('mobile');?>" class="form-control" tabindex="3"> 
            <?=form_error('mobile')?>
            </div>
            <div class="col-xs-6"><div class="row"><span class="col-xs-4  col-sm-12 col-md-4">
            <p><?php echo lang('cap_code'); ?></p> <figure>  <?php echo $image; ?></figure></figure>
            </span>
            <span class="col-xs-8 col-sm-12 col-md-8"><p><?php echo lang('enter_cap_code'); ?></p>  <input type="text" name="security_code" id="security_code" class="form-control" tabindex="4">
            <?=form_error('security_code')?>
            </span>
            </div></div>
            </div>
            <div class="form-group"><p><?php echo lang('message'); ?></p>
            <textarea rows="5" name="message" class="form-control" tabindex="5"></textarea>
          <?=form_error('message')?>
            </div>
            <button   type="submit" name="button" class="btn btn-danger main_btn" id="btnContact"><?php echo lang('send'); ?></button>
            
      </form>
            </div>

            
            
            <div class="clearfix"></div>
            
            </div>
          
     