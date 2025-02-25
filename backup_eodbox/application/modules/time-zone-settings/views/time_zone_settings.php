<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; Time Zone Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Time Zone Settings</h2>
    <div class="mid_frm">
    

    
    
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
    
<?php
//print_r($site_set);
?>
<form name="timezonesettings" method="post" action="">
<table width="100%" border="0" cellspacing=4 cellpadding=2 class="light">
    <tr>
      <td colspan="2" class="language"><p>* Select time zone and click on button to save it. </p>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td width="39%" align="right"><strong>Select Time Zone : </strong></td>
      <td width="61%">
	  <select name="gmt_time">
	  <? foreach($gmt_lists as $gmt){?>
	  <option value="<?=$gmt['id'];?>" <? if($gmt['status']=="on"){echo "selected";}?>><?php echo $gmt['utc_time_zone']; ?></option>
	  <? }?>	  
      </select>
	  <input type="submit" name="Submit" value="Save Time Zone" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="language"><br /><? echo $this->session->flashdata('message')?></td>
    </tr>
  </table>

</form>

 </div>
    <div class="clear"></div>
</div>


