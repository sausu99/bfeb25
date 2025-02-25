

<section class="mid_part">
<div class="container">
<div class="row">
<div class="col-md-9 col-sm-12 reg_form">
<h3 class="title"> <?php echo lang('contact'); ?></h3>
<form method="post" id="contact-fgg" action="<?php echo base_url();?>contact_us/testform" enctype="multipart/form-data">
  <fieldset>
    <h4><?php echo lang('contact_us'); ?></h4>
    <div class="row">
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
          <label><?php echo lang(';first_username'); ?></label>
          <input type="text" name="fname" value="<?php echo set_value('fname');?>" class="form-control" tabindex="1" autofocus>
          <?=form_error('fname')?>
        </div>
        <div class="form-group">
          <label><?php echo lang('email'); ?> </label>
          <input type="email" name="email" value="<?php echo set_value('email');?>" class="form-control" tabindex="2">
          <?=form_error('email')?>
        </div>
        <div class="form-group">
          <label><?php echo lang('last'); ?> </label>
          <input type="text" name="lname" value="<?php echo set_value('lname');?>" class="form-control" tabindex="3">
        </div>
        
      </div>
      
    </div>
    <div class="row">
      <div class="col-md-2 col-sm-3 col-xs-12 form-group btn-contact">
        <button type="submit" name="button" class="btn_yellow" id="btnCggtact"><?php echo lang('send'); ?></button>
      </div>
      <div class="col-md-10 col-sm-9 col-xs-12">
        <?php if($this->session->flashdata('message')){?>
        <div role="alert" class="alert alert-success alert_msg">
         <?php echo $this->session->flashdata('message');?></div>
        <?php }?>
      </div>
    </div>
  </fieldset>
  <div class="clearfix"></div>
</form>
