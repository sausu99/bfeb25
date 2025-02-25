<?php error_reporting(0);?>
<script type="text/javascript">
$(document).ready(function() {

$("#chang_pass").click(function() {
	$("#change_password").html('<input name="password" type="text" class="inputtext" id="password" size="30" /> <input class="bttn" type="button" name="Submit" value="Changed" id="changed"  onclick="changedpassword(this.value)" />');
	return false;
});
});

function changedpassword(value) {
	$.post('<?=site_url(ADMIN_DASHBOARD_PATH).'/members/change_user_password'?>', 
		   $("#uprofile").serialize(), 
		   function(data)
						{
							$("#change_password").html('<span class="error">'+data+'</span>');
						});
}

</script>
<div class="content">
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  <a href="<?=site_url(ADMIN_DASHBOARD_PATH).'/members/index'?>">Member  Management </a></span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Edit Member </h2>
	<div align="center"><?php $this->load->view('menu');?></div>
    <div class="mid_frm">
<div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
    echo "<div class='message'>".$this->session->flashdata('message')."</div>";
    }
  ?></div>
    
<form name="member" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8" id="uprofile">
<input name="user_id" type="hidden" class="inputtext" id="user_id" value="<?php echo $profile->id;?>" size="15" />
<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Personal Detail</strong></div></td>
  </tr>
<?php /*?><tr>
  <td width="229" class="hmenu_font">Title</td>
  <td width="429"><select name="title" class="reg-sel">
  <option <?php if('Mr' == $profile->title)echo 'selected="selected"'; ?>>Mr</option>
  <option <?php if('Mrs' == $profile->title)echo 'selected="selected"'; ?>>Mrs</option>
  <option <?php if('Miss' == $profile->title)echo 'selected="selected"'; ?>>Miss</option>
</select></td>
  </tr><?php */?>
  <?php
		$user_profile_imag = $this->general->get_user_profile_img($profile->id);
 
		if($user_profile_imag->image){
		 $profile_image = base_url(USER_PROFILE_PATH.$user_profile_imag->image);
     $rem='<a  style="margin-left:5px;" href="'.site_url(ADMIN_DASHBOARD_PATH).'/members/delete_image_only/'.$profile->id.'"><button type="button">Delete Image</button></a>';
    }
		else{
      if($profile->gender=='M')
			$profile_image = base_url('assets/images/MALE.jpg');
    else
      $profile_image = base_url('assets/images/FEMALE.jpg');
    }
?>
<tr>
  <td class="hmenu_font">Profile Image</td>
  <td><img id="profile_img" src="<?php echo $profile_image;?>" height="100"></td>
<td> <?php if(isset($rem))echo $rem;?></td>

</tr>
<tr>
<td class="hmenu_font">Gender </td>
<td>
  <input type="radio" name="gender" value="M" <?php if($profile->gender=='M')echo 'checked';?>> Male
  <input type='radio' name="gender" value="F" <?php if($profile->gender=='F')echo 'checked';?>>Female
</td>
</tr>
<tr>
  <td class="hmenu_font">Block this user</td>
  <td height="20">
  
      <input name="obsence_flag" type="radio" value="no" <?php if($profile->obsence_flag == 'no'){ echo 'checked="checked"';}?> />
      Safe
    <input name="obsence_flag" type="radio" value="yes" <?php if($profile->obsence_flag == 'yes'){ echo 'checked="checked"';}?> />
   Obsence user 
   </td>
   <td>(Note:checking on <strong>obsence user</strong> will suspend the user)</td>
   
</tr>

<tr>
  <td class="hmenu_font">First Name </td>
  <td><input name="first_name" type="text" class="inputtext" id="first_name" value="<?php echo set_value('first_name',$profile->first_name);?>" size="15" />
    <?=form_error('first_name')?></td>
</tr>
<tr>
  <td class="hmenu_font">Last Name </td>
  <td><input name="last_name" type="text" class="inputtext" id="last_name" value="<?php echo set_value('last_name',$profile->last_name);?>" size="15" />
    <?=form_error('last_name')?></td>
</tr>
<tr>
  <td class="hmenu_font">Email</td>
  <td><input name="email" type="text" class="inputtext" id="email" value="<?php echo set_value('email',$profile->email);?>" size="30" />
    <?=form_error('email')?></td>
</tr>
<tr>
  <td class="hmenu_font">User Name </td>
  <td height="20"><?php echo $profile->user_name;?></td>
</tr>

<tr>
  <td class="hmenu_font">Mobile Number</td>
  <td><input name="mobile" type="text" class="inputtext" id="mobile" value="<?php echo set_value('mobile',$profile->mobile);?>" size="15" />
    <?=form_error('mobile')?></td>
</tr>
<tr>

  <td class="hmenu_font">Date of Birth</td>
  <td>

  <?php  
    $year=$this->input->post('year')?$this->input->post('year'):$profile->dob_year;
    $month=$this->input->post('month')?$this->input->post('month'):$profile->dob_month;
    $day=$this->input->post('day')?$this->input->post('day'):$profile->dob_day;
  ?>

  

  <select name="day" id="day" tabindex="1">
   <option value="">Day</option>
          <?php             
           for ($j = 1; $j <= 31; $j++) {             
          ?>
          <option value="<?php echo date("d", mktime(0, 0, 0, 1, $j, 2000))?>" <?php if(date("d", mktime(0, 0, 0, 1, $j, 2000)) == $day)echo 'selected="selected"'; ?>><?php echo date("d", mktime(0, 0, 0, 1, $j, 2000))?></option>
            <?php   
             }  
             ?>       
    </select>
    <?=form_error('day')?>
    <select name="month" id="month"  tabindex="1">
    <option value="">Month</option>
          <?php 
            foreach($this->general->get_month('en') as $key=>$val) {      
          ?>
          <option value="<?php echo $key;?>" <?php if($key == $month)echo 'selected="selected"'; ?>><?php echo $val;?></option>
          <?php   
            } ?>
  </select>
  <?=form_error('month')?>
    <select  name="year" id="year"  tabindex="1">
       <option value="">Year</option>
        <?php   
            for ($j = date("Y"); $j > 1900; $j--) {             
            ?>
              <option value="<?php echo $j;?>" <?php if($j == $year)echo 'selected="selected"'; ?>><?php echo $j;?></option>
            <?php   
             }  ?>          
  </select>
    <?=form_error('year')?>

  </td>

</tr>


<tr>
  <td class="hmenu_font">Password</td>
  <td height="20" id="change_password">********** <a href="#" id="chang_pass">Change Password</a> </td>
</tr>
<tr>
  <td class="hmenu_font">Balance</td>
  <td height="20"><?php echo $profile->balance;?> Credits </td>
</tr>
<tr>
  <td class="hmenu_font">Bonus</td>
  <td height="20"><?php echo $profile->bonus_points;?> Points</td>
</tr>
<tr>
  <td class="hmenu_font">Registered Date </td>
  <td height="20"><?php echo $this->general->convert_local_time($profile->reg_date);?></td>
</tr>
<tr>
  <td class="hmenu_font">Last Login Date </td>
  <td height="20"><?php echo $this->general->convert_local_time($profile->last_login_date);?></td>
</tr>


<tr>
  <td class="hmenu_font">Registered IP </td>
  <td height="20"><?php echo $profile->reg_ip_address;?></td>
</tr>
<tr>
  <td class="hmenu_font">Last Login IP </td>
  <td height="20"><?php echo $profile->last_ip_address;?></td>
</tr>
<tr>
  <td class="hmenu_font">Country</td>
  <td height="20">
  <?php if($profile->country_flag): ?>
  <img src='<?php echo base_url().$profile->country_flag;?>' title="<?php print($profile->country_flag);?>">
  <?php endif; ?> 
  (<?php print($profile->country_name);?>)

  </td>

</tr>
<tr>
  <td class="hmenu_font">Refer By</td>
  <td>
  <?php
  			if($profile->referer_id)
  ?></td>
</tr>
<tr>
  <td class="hmenu_font">Status</td>
  <td height="20">
  <input name="status" type="radio" value="active" checked="checked" />
    Active
      <input name="status" type="radio" value="inactive" <?php if($profile->status == 'inactive'){ echo 'checked="checked"';}?> />
      Inactive
	  <input name="status" type="radio" value="suspended" <?php if($profile->status == 'suspended'){ echo 'checked="checked"';}?> />
	  Suspended
	  <input name="status" type="radio" value="close" <?php if($profile->status == 'close'){ echo 'checked="checked"';}?> />
	  Closed</td>
</tr>
<tr>
  <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Address </strong></div></td>
  </tr>
<tr>
  <td class="hmenu_font">Address1</td>
  <td><input name="address" type="text" class="inputtext" id="address" value="<?php echo set_value('address',$profile->address);?>" size="25" />
    <?=form_error('address')?></td>
</tr>
<tr>
  <td class="hmenu_font">Address2</td>
  <td><input name="address2" type="text" class="inputtext" id="address2" value="<?php echo set_value('address2',$profile->address2);?>" size="25" />
    <?=form_error('address2')?></td>
</tr>
<tr>
  <td class="hmenu_font">Country</td>
  <td>
  <?php 
  $country=$this->input->post('country')?$this->input->post('country'):0;
  $db_country=!empty($profile->country)? $profile->country:0;
  // exit;
  $sel_country=$country?$country:$db_country;
   ?>
  <select name="country" class="reg-sel-country" id="country">

  <option value="">Select Country</option>
  <?php foreach($this->general->get_country() as $country){?>
  <option value="<?php echo $country->id;?>" <?php if($sel_country==$country->id)echo 'selected="selected"'; ?>  ><?php echo $country->country;?></option>
  <?php } ?>
</select>  
    <?=form_error('country')?>
</td>
</tr>
<tr>
  <td class="hmenu_font">State</td>
  <td>
  <?php 
  $sel_state = set_value('state',$profile->state);
   ?>
  <select name="state" class="country form-control">
                    <option value="" selected="selected">Select State</option>
                    <?php
                                        foreach ($this->general->get_indian_states() as $val) {
                                            ?>
                    <option value="<?php echo $val->city_state; ?>"  <?php if($sel_state==$val->city_state)echo 'selected="selected"'; ?>><?php echo $val->city_state; ?></option>
                    <?php } ?>
                  </select>
      <?= form_error('state') ?>
  </td>
</tr>
<tr>
  <td class="hmenu_font">City</td>
  <td><input name="city" type="text" class="inputtext" id="city" value="<?php echo set_value('city',$profile->city);?>" size="25" />
    <?=form_error('city')?>
    </td>
</tr>

<tr>
  <td class="hmenu_font">Postcode</td>
  <td><input name="post_code" type="text" class="inputtext" id="post_code" value="<?php echo set_value('post_code',$profile->post_code);?>" size="25" />
    <?=form_error('post_code')?></td>
</tr>
<!-- <tr>
  <td class="hmenu_font">Phone</td>
  <td><input name="phone" type="text" class="inputtext" id="phone" value="<?php echo set_value('phone',$profile->phone);?>" size="25" />
    <?=form_error('phone')?></td>
</tr> -->
<tr>
  <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<!-- <tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Shipping Address </strong></div></td>
  </tr>
<tr>
  <td class="hmenu_font">Name</td>
  <td>
  <input name="ship_id" type="hidden" class="inputtext" id="name" value="<?php echo $ship_addr->id;?>" size="25" />
  <input name="name" type="text" class="inputtext" id="name" value="<?php echo set_value('name',$ship_addr->name);?>" size="25" />
    <?=form_error('name')?></td>
</tr>
<tr>
  <td class="hmenu_font">Address1</td>
  <td><input name="ship_address" type="text" class="inputtext" id="ship_address" value="<?php echo set_value('ship_address',$ship_addr->address);?>" size="25" />
    <?=form_error('ship_address')?></td>
</tr>
<tr>
  <td class="hmenu_font">Address2</td>
  <td><input name="ship_address2" type="text" class="inputtext" id="ship_address2" value="<?php echo set_value('ship_address2',$ship_addr->address2);?>" size="25" />
    <?=form_error('ship_address2')?></td>
</tr>
<tr>
  <td class="hmenu_font">Country</td>
  <td>
  <select name="ship_country" class="reg-sel-country" id="country">

  <option value="">Select Country</option>
  <?php foreach($this->general->get_country() as $country){?>
  <option value="<?php echo $country->id;?>" <?php if($country->id == $ship_addr->country_id)echo 'selected="selected"'; ?>  ><?php echo $country->country;?></option>
  <?php } ?>
</select>  </td>
</tr>
<tr>
  <td class="hmenu_font">City</td>
  <td><input name="ship_city" type="text" class="inputtext" id="ship_city" value="<?php echo set_value('ship_city',$ship_addr->city);?>" size="25" />
    <?=form_error('ship_city')?></td>
</tr>

<tr>
  <td class="hmenu_font">Postcode</td>
  <td><input name="ship_post_code" type="text" class="inputtext" id="ship_post_code" value="<?php echo set_value('ship_post_code',$ship_addr->post_code);?>" size="25" />
    <?=form_error('ship_post_code')?></td>
</tr>
<tr>
  <td class="hmenu_font">Phone</td>
  <td><input name="ship_phone" type="text" class="inputtext" id="ship_phone" value="<?php echo set_value('ship_phone',$ship_addr->phone);?>" size="25" />
    <?=form_error('ship_phone')?></td>
</tr>
 -->
<tr height="30">
  <td colspan="2">&nbsp;</td>
</tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" />  </td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>
	