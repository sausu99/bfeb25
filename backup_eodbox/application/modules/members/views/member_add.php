
<div class="content">
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  <a href="<?=site_url(ADMIN_DASHBOARD_PATH).'/members/index'?>">Member  Management </a></span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Edit Member </h2>
	<div align="center"><?php //$this->load->view('menu');?></div>
    <div class="mid_frm">
<div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
    echo "<div class='message'>".$this->session->flashdata('message')."</div>";
    }
  ?></div>
    
<form name="member" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8" id="uprofile">

<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Personal Detail</strong></div></td>
  </tr>
  
<tr>
  <td class="hmenu_font">Profile Image</td>
  <td><img id="profile_img" src="<?php //echo $profile_image;?>" height="100"></td>
<td> <?php //if(isset($rem))echo $rem;?></td>

</tr>
<tr>
<td class="hmenu_font">Gender </td>
<td>
	<?php $gender_post = set_value('gender','M');?>
    
  <input type="radio" name="gender" value="M" <?php if($gender_post=='M')echo 'checked';?>> Male
  <input type='radio' name="gender" value="F" <?php if($gender_post=='F')echo 'checked';?>>Female
  <?=form_error('gender')?>
</td>
</tr>


<tr>
  <td class="hmenu_font">First Name </td>
  <td><input name="first_name" type="text" class="inputtext" id="first_name" value="<?php echo set_value('first_name');?>" size="15" />
    <?=form_error('first_name')?></td>
</tr>
<tr>
  <td class="hmenu_font">Last Name </td>
  <td><input name="last_name" type="text" class="inputtext" id="last_name" value="<?php echo set_value('last_name');?>" size="15" />
    <?=form_error('last_name')?></td>
</tr>
<tr>
  <td class="hmenu_font">Email</td>
  <td><input name="email" type="text" class="inputtext" id="email" value="<?php echo set_value('email');?>" size="30" />
    <?=form_error('email')?></td>
</tr>

<tr>
  <td class="hmenu_font">Mobile Number</td>
  <td><input name="mobile" type="text" class="inputtext" id="mobile" value="<?php echo set_value('mobile');?>" size="15" />
    <?=form_error('mobile')?></td>
</tr>

<tr>

  <td class="hmenu_font">Date of Birth</td>
  <td>

  <?php  
    $year = $this->input->post('year');
    $month = $this->input->post('month');
    $day = $this->input->post('day');
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
    
    <select name="month" id="month"  tabindex="1">
    <option value="">Month</option>
          <?php 
            foreach($this->general->get_month('en') as $key=>$val) {      
          ?>
          <option value="<?php echo $key;?>" <?php if($key == $month)echo 'selected="selected"'; ?>><?php echo $val;?></option>
          <?php   
            } ?>
  </select>
  
    <select  name="year" id="year"  tabindex="1">
       <option value="">Year</option>
        <?php   
            for ($j = date("Y"); $j > 1900; $j--) {             
            ?>
              <option value="<?php echo $j;?>" <?php if($j == $year)echo 'selected="selected"'; ?>><?php echo $j;?></option>
            <?php   
             }  ?>          
  </select>
   <?=form_error('day')?><?=form_error('month')?><?=form_error('year')?>

  </td>

</tr>

<tr>
  <td class="hmenu_font">User Name </td>
  <td height="20">
  <input name="user_name" type="text" class="inputtext" id="user_name" value="<?php echo set_value('user_name');?>" size="15" />
    <?=form_error('user_name')?>
  </td>
</tr>

<tr>
  <td class="hmenu_font">Password</td>
  <td height="20">
  <input name="password" type="text" class="inputtext" id="password" value="<?php echo set_value('password');?>" size="15" />
    <?=form_error('password')?>
  </td>
</tr>

<tr>
  <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Billing Address </strong></div></td>
  </tr>
<tr>
  <td class="hmenu_font">Address1</td>
  <td><input name="address" type="text" class="inputtext" id="address" value="<?php echo set_value('address');?>" size="25" />
    <?=form_error('address')?></td>
</tr>
<tr>
  <td class="hmenu_font">Address2</td>
  <td><input name="address2" type="text" class="inputtext" id="address2" value="<?php echo set_value('address2');?>" size="25" />
    <?=form_error('address2')?></td>
</tr>
<tr>
  <td class="hmenu_font">Country</td>
  <td>
  <?php 
  $sel_country = set_value('country');
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
  $sel_state = set_value('state');
   ?>
  <select name="state" class="country form-control">
                    <option value="" selected="selected">Select State</option>
                    <?php
                                        foreach ($this->general->get_indian_states() as $val) {
                                            ?>
                    <option value="<?php echo $val->city_state; ?>"  <?php if($sel_state==$val->city_id)echo 'selected="selected"'; ?>><?php echo $val->city_state; ?></option>
                    <?php } ?>
                  </select>
      <?= form_error('state') ?>
  </td>
</tr>
<tr>
  <td class="hmenu_font">City</td>
  <td><input name="city" type="text" class="inputtext" id="city" value="<?php echo set_value('city');?>" size="25" />
    <?=form_error('city')?>
    </td>
</tr>

<tr>
  <td class="hmenu_font">Postcode</td>
  <td><input name="post_code" type="text" class="inputtext" id="post_code" value="<?php echo set_value('post_code');?>" size="25" />
    <?=form_error('post_code')?></td>
</tr>
<tr>
  <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
  <td colspan="2">&nbsp;</td>
</tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Submit" />  </td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>
	