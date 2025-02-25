<div class="dash-bid-item dashboard-widget mb-40-60">
                        <div class="header">
                            <h4 class="title"><?php echo lang('account_cancel_account'); ?></h4>
                         </div>   
                            <?php
		
    if($this->session->flashdata('error_message'))
      { ?>
          <div role="alert" class="alert alert-danger">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-warning">&nbsp;</i><?php echo $this->session->flashdata('error_message') ?></div>
          <?php
      }
    ?>
          <?php
      if($this->session->flashdata('success_message'))
      { ?>
          <div role="alert" class="alert alert-success">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-check">&nbsp;</i> <?php echo $this->session->flashdata('success_message') ?></div>
          <?php
      }
       ?>
     
	  <?php
		
    if(form_error('package'))
      { ?>
          <div role="alert" class="alert alert-danger">
            <span aria-label="Close" data-dismiss="alert" class="close">×</span>
            <i class="fa fa-warning">&nbsp;</i><?php echo form_error('package'); ?></div>
          <?php
      }
    ?>
    
        <div class="scroll">
        	 <p><?php echo $this->lang->line('acc_cancel_warning_txt');?></p>
<p><?php echo $this->lang->line('acc_cancel_once_nt_register_txt');?></p>
<p><?php echo $this->lang->line('acc_cancel_what_do_uthink');?></p>
<form name="cancel" class="login-form" method="post" action="">
<div class="form-group checkgroup mb-30">
    <input type="checkbox" name="terms" id="check">
    <label for="check">
	<?php echo $this->lang->line('register_terms_condition_info_txt_1st_part');?> <?php echo $this->general->get_cms_link_byid(4);?>
    </label>
</div>

<div class="setsec wdth"><a href="javascript:vide(0);" onclick="document.cancel.submit();" class="custom-button"><?php echo $this->lang->line('acc_cancel_id');?></a>
<a href="javascript:window.history.back();" class="custom-button yellow"><?php echo $this->lang->line('acc_cancel_go_back');?></a></div>

</form>
<br />
<hr />
<p><?php echo $this->lang->line('acc_cancel_notice');?></p>    
        </div>     
		
                    </div>
                    
