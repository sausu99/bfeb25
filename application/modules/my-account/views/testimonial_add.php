<h1><?php echo $this->lang->line('user_testimonial_title');?></h1>
<div class="acc-lt">
<div class="probg-area">
<aside class="reg-sec-lt">
<form action="" method="post" enctype="multipart/form-data" name="password" id="password" accept-charset="utf-8">
<p><?php echo $this->lang->line('user_testimonial_desc');?> </p>
<div class="clear reg-line"></div>
<dl style="padding:20px 0 0 50px;">
<dt>* <?php echo $this->lang->line('user_testimonial_img_upload');?>:</dt><dd><input type="file" name="img" /> <div><?=form_error('img')?><?=$this->session->userdata('error_img1');?></div></dd></dl>
<dl style="padding:0 0 0 50px;"><dt>* <?php echo $this->lang->line('user_testimonial_message');?> :</dt>
<dd>  <textarea name="description" cols="40" rows="5" maxlength="200"></textarea>
  <div><?=form_error('description')?></div></dd>
</dl>

<div class="regbtn-sec"><input name="testimonial" type="submit" value="<?php echo $this->lang->line('user_testimonial_bttn');?>" class="lbtn"></div>
</form>
</aside>
</div>
<div class="acc-shadow"></div>
</div>