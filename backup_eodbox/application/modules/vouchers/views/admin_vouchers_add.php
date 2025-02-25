<script src="<?php print(ADMIN_JS_DIR_FULL_PATH);?>tabcontent/tabcontent.js" type="text/javascript"></script>
<link href="<?php print(ADMIN_JS_DIR_FULL_PATH);?>tabcontent/tabcontent.css" rel="stylesheet" type="text/css" />
<script>var FilesFolderPath = "<?php echo ASSETS_CALENDER;?>";</script>
<script src="<?php print(ASSETS_CALENDER);?>Scripts/DateTimePicker.js" type="text/javascript"></script>
<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Vouchers Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Vouchers</h2>
    <div class="mid_frm">

    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td><strong>Voucher Code</strong></td>
  <td><input name="code" type="text" class="inputtext" id="code" value="<?php echo set_value('code');?>" size="25" />(characters: A-Z, a-z, 0-9 and -)
    <?=form_error('code')?></td>
</tr>
<tr>
  <td><strong>Limit</strong></td>
  <td><input name="limit_number" type="text" class="inputtext" id="limit_number" value="<?php echo set_value('limit_number');?>" size="10" />
    <?=form_error('limit_number')?></td>
</tr>
<tr>
  <td width="229"><strong>Limit Per User</strong></td>
  <td width="429"><input name="limit_per_user" type="text" class="inputtext" id="limit_per_user" value="<?php echo set_value('limit_per_user');?>" size="10" />
      <?=form_error('limit_per_user')?></td>
  </tr>

<?php /*?><tr height="30">
  <td><strong>Free Bids</strong></td>
  <td colspan="2"><input name="free_bids" type="text" class="inputtext" id="free_bids" value="<?php echo set_value('free_bids',0);?>" size="10" />(Registration Voucher)
    <?=form_error('free_bids')?></td>
</tr><?php */?>
<tr height="30">
  <td><strong>Extra Bids</strong>(%)</td>
  <td colspan="2"><input name="extra_bids" type="text" class="inputtext" id="extra_bids" value="<?php echo set_value('extra_bids',0);?>" size="10" />
    <?=form_error('extra_bids')?></td>
</tr>
<tr height="30">
  <td><strong>Start Date</strong></td>
  <td colspan="2"><input name="start_date" type="text" class="inputtext" id="start_date" value="<?php echo set_value('start_date');?>" size="10" readonly="readonly" />
  <img src="<?php print(ASSETS_CALENDER);?>Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('start_date','yyyyMMdd','arrow',false,'24')" />
    <?=form_error('start_date')?></td>
</tr>
<tr height="30">
  <td><strong>End Date</strong></td>
  <td colspan="2"> <input name="end_date" type="text" class="inputtext" id="end_date" value="<?php echo $this->input->post('end_date');?>" size="10" />
  <img src="<?php print(ASSETS_CALENDER);?>Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('end_date','yyyyMMdd','arrow',false,'24')" />
    <?=form_error('end_date')?></td>
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


