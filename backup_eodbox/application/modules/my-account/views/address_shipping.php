<?php error_reporting(0);?>
<h1><?php echo $this->lang->line('account_my_address');?></h1>
<div class="acc-lt">
<div class="probg-area">
  <form id="profile" name="profile" method="post" action="" accept-charset="utf-8">
  <aside class="reg-sec-lt">
<h2><?php echo $this->lang->line('profile_ship_addr');?></h2>
<div class="clear reg-line"></div>
<div align="center" class="error"><?php echo $this->session->flashdata('message');?></div>
<dl>

<dt> <?php echo $this->lang->line('profile_name');?>:</dt>
<dd><input name="name" type="text" class="logintxtbox" id="name" value="<?php echo set_value('name',$profile->name);?>">
<div><?=form_error('name')?></div></dd>


<dt>* <?php echo $this->lang->line('profile_address');?> :</dt><dd><input name="address" type="text" class="logintxtbox" id="address" value="<?php echo set_value('address',$profile->address);?>">
<div><?=form_error('address')?></div>
</dd>
<dt> <?php echo $this->lang->line('profile_address2');?> :</dt><dd><input name="address2" type="text" class="logintxtbox" id="address2" value="<?php echo set_value('address2',$profile->address2);?>">

</dd>
<dt><?php echo $this->lang->line('profile_country');?> :</dt><dd>
<select name="country" class="reg-sel-country" id="country">

  <option value=""><?php echo $this->lang->line('register_sel_country');?></option>
  <?php foreach($countries as $country){?>
  <option value="<?php echo $country->id;?>" <?php if($country->id == $profile->country_id || $country->id == $this->input->post('country', TRUE))echo 'selected="selected"'; ?>  ><?php echo $country->country;?></option>
  <?php } ?>

</select>
<div><?=form_error('country')?></div>
</dd>
<dt>* <?php echo $this->lang->line('profile_city');?> :</dt><dd><input name="city" type="text" class="logintxtbox" id="city" value="<?php echo set_value('city',$profile->city);?>">
<div><?=form_error('city')?></div>
</dd>
<dt>* <?php echo $this->lang->line('profile_post_code');?> :</dt><dd><input name="post_code" type="text" class="logintxtbox" id="post_code" value="<?php echo set_value('post_code',$profile->post_code);?>">
<div><?=form_error('post_code')?></div>
</dd>
<dt>* <?php echo $this->lang->line('phone');?> :</dt><dd><input name="phone" type="text" class="logintxtbox" id="phone" value="<?php echo set_value('phone',$profile->phone);?>">
<div><?=form_error('phone')?></div>
</dd>
<dt class="gap">&nbsp;</dt>
<dd class="ddbtns"><input name="profile" type="submit" value="<?php echo $this->lang->line('profile_bttn_name');?>" class="lbtn"></dd>
</dl>


</aside>
<input name="id" type="hidden" value="<?php echo $profile->id;?>">
  </form>
</div>
<div class="acc-shadow"></div>
</div>
