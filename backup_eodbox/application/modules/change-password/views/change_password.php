<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Change Password</span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>Change Admin Password  </h2>
    <div class="mid_frm">
    

    
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action="" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
<td width=229 class="hmenu_font">Old Password </td>
<td width="429">
<input name="old_password" type=text id="old_password" value="<?php echo set_value('old_password');?>" size=30>
<?=form_error('old_password')?></td>
</tr>
<tr>
<td width=229 class="hmenu_font">New Password </td>
<td width="429"><input name="new_password" type=text id="new_password" value="<?php echo set_value('new_password');?>" size=30><?=form_error('new_password')?></td>
</tr>
<tr>
<td width=229 class="hmenu_font">Confirm Password </td>
<td width="429"><input name="re_password" type=text id="re_password" value="<?php echo set_value('re_password');?>" size=30><?=form_error('re_password')?></td>
</tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>


