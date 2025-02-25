<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Block IP Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Block IP </h2>
    <div class="mid_frm">

    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td width="229" class="hmenu_font">IP Address</td>
  <td width="429"><input name="ip_address" type="text" class="inputtext" id="ip_address" value="<?php echo set_value('ip_address');?>" size="30" />
      <?=form_error('ip_address')?></td>
  </tr>
<tr>
  <td width="229" class="hmenu_font">Message</td>
  <td width="429"><textarea name="message" cols="50" class="inputtext" id="message"><?php echo set_value('message');?></textarea>
      <?=form_error('message')?></td>
  </tr>
<tr height="30">
  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Submit" /></td>
</tr>
</table>
</form>

 </div>
    <div class="clear"></div>
</div>


