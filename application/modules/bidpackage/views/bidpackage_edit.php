<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Bid Package Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Bid Package</h2>
    <div class="mid_frm">

    
<?php
//print_r($site_set);
?>
<form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">

<table width=90% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td class="hmenu_font">Bid Package Name </td>
  <td><input name="name" type="text" class="inputtext" id="name" value="<?php echo set_value('name',$data->name);?>" size="40" />
    <?=form_error('name')?></td>
</tr>
<tr>
  <td class="hmenu_font">TAG </td>
  <td>
      <input type="radio" name="best_tag" value="0" <?php if($data->best_tag=='0')echo 'checked';?>>None
      <input type="radio" name="best_tag" value="1" <?php if($data->best_tag=='1')echo 'checked';?>>Best Selling
      <input type="radio" name="best_tag" value="2" <?php if($data->best_tag=='2')echo 'checked';?>>Best Value
  </td>
</tr>
<tr>
  <td class="hmenu_font">Amount</td>
  <td><input name="amount" type="text" class="inputtext" id="amount" value="<?php echo set_value('amount',$data->amount);?>" size="10" />
    <?=form_error('amount')?></td>
</tr>
<tr>
  <td width="229" class="hmenu_font">Bids</td>
  <td width="429"><input name="credits" type="text" class="inputtext" id="credits" value="<?php echo set_value('credits',$data->credits);?>" size="10" />
      <?=form_error('credits')?></td>
  </tr>
<tr>
  <td width="229" class="hmenu_font">Bonus Points</td>
  <td width="429"><input name="bonus_points" type="text" class="inputtext" id="bonus_points" value="<?php echo set_value('bonus_points',$data->bonus_points);?>" size="10" />
      <?=form_error('bonus_points')?></td>
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


