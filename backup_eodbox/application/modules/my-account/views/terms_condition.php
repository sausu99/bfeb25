<h1><?php echo $this->lang->line('label_terms_service');?></h1>
<div class="acc-lt">
<div class="bidhis-area">
<div class="dashboardsec">
<div align="center" class="error"><?php echo $this->session->flashdata('message');?></div>
  <div class="clear"></div>
  <div align="center"><img src="<?php echo ROOT_SITE_PATH.MAIN_IMG_DIR_FULL_PATH;?>cancel.png" ></div>


  <p><?php echo $this->lang->line('acc_toc_desc');?></p>
<form name="tc" method="post" action="">
<input name="t_c" type="checkbox" id="t_c" <?php echo set_checkbox('t_c', 'on'); ?> >
<?php echo $this->lang->line('register_terms_condition_info_txt_1st_part');?> <?php echo $this->general->get_cms_link_byid(4);?>
<?php echo form_error('t_c'); ?>
<div class="clear"></div>
<div class="setsec"><a href="javascript:vide(0);" onclick="document.tc.submit();"><?php echo $this->lang->line('acc_toc_agree');?></a></div>

<div class="clear"></div>
</form>

</div>

</div>

<div class="acc-shadow"></div>
</div>