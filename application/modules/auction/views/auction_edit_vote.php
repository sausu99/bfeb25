<script src="<?php print base_url(ADMIN_JS_DIR_FULL_PATH);?>/tabcontent/tabcontent.js" type="text/javascript"></script>
<link href="<?php print base_url(ADMIN_JS_DIR_FULL_PATH);?>/tabcontent/tabcontent.css" rel="stylesheet" type="text/css" />
<script>var FilesFolderPath = "<?php echo ASSETS_CALENDER;?>";</script>
<script src="<?php print(ASSETS_CALENDER);?>Scripts/DateTimePicker.js" type="text/javascript"></script>
<script type="text/javascript">
function doconfirm()
{
	job=confirm("Are you sure to delete it?");
	if(job!=true)
	{
		return false;
	}
}
</script>

<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Vote Auction  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Vote Auction </h2>
    <div class="mid_frm">

    
<?php
//print_r($error);
?>
<form name="sitesetting" id="auction_form" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8">
<input name="id" type="hidden" class="inputtext" id="id" value="<?php echo $data_auction->id;?>" size="15" />
<table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">


<tr>
  <td colspan="2" bgcolor="#FFFFFF"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Auction Common Value</strong></div></td>
  </tr>
 <tr>
  <td class="hmenu_font">Category Name</td>
  <td>
  <?php 
  $cat_id=$this->input->post('cat_id');
  $cat_id_db=$data_auction->cat_id; 
  $sel_cat_id=!empty($cat_id)?$cat_id:$cat_id_db;
  ?>
  <select name="cat_id">
        <option value="">Select Category</option>
               <?php  if($this->general->get_all_categories_display(DEFAULT_LANG_ID)){
         foreach($this->general->get_all_categories_display(DEFAULT_LANG_ID) as $pcat){?>
       <option value="<?php echo $pcat->parent_id;?>" <?php if($pcat->parent_id==$sel_cat_id) echo "selected=selected";;?>><?php echo $pcat->name;?></option>
 
  <?php }
         }?>
</select>
<?=form_error('cat_id')?>
  </td>
</tr>
<tr>

  <td class="hmenu_font">Auction Price (<?php echo DEFAULT_CURRENCY_SIGN;?>)</td>
  <td><input name="price" type="text" class="inputtext" id="price" value="<?php echo set_value('price',$data_auction->price);?>" size="15" />
      <?=form_error('price')?></td>
  </tr>
<tr>
  <td class="hmenu_font">Shipping Cost (<?php echo DEFAULT_CURRENCY_SIGN;?>)</td>
  <td><input name="shipping_cost" type="text" class="inputtext" id="shipping_cost" value="<?php echo set_value('shipping_cost',$data_auction->shipping_cost);?>" size="15" />
    <?=form_error('shipping_cost')?></td>
</tr>
<tr>
  <td class="hmenu_font">Bid Fee </td>
  <td><input name="bid_fee" type="text" class="inputtext" id="bid_fee" value="<?php echo set_value('bid_fee',$data_auction->bid_fee);?>" size="10" />
  Bid(s)
    <?=form_error('bid_fee')?></td>
</tr>
<tr>
  <td class="hmenu_font">SMS Code</td>
  <td>
  <input name="sms_code" type="text" class="inputtext" id="sms_code" value="<?php echo set_value('sms_code',$data_auction->sms_code);?>" size="10" />
    <?=form_error('sms_code')?></td>
</tr>

<tr>
    <td class="hmenu_font"> Auction (DD-HH-MM)</td>
    <td><input name="end_day" type="text" class="inputtext" placeholder="DD" id="end_day" value="<?php echo set_value('end_day',$data_auction->end_day);?>" size="10" />
    <?=form_error('end_day')?>
    <input name="end_hour" type="text" class="inputtext" placeholder="HH" id="end_hour" value="<?php echo set_value('end_hour',$data_auction->end_hour);?>" size="10" />
    <?=form_error('end_hour')?>
    <input name="end_minute" type="text" class="inputtext" placeholder="MM" id="end_minute" value="<?php echo set_value('end_minute',$data_auction->end_minute);?>" size="10" />
    <?=form_error('end_minute')?>
    </td>
</tr>
<?php if($data_auction->image1){?>
<tr>
  <td class="hmenu_font"></td>
  <td><img src='<?php print(site_url(AUCTION_IMG_PATH.'thumb_'.$data_auction->image1));?>'></td>
</tr>
<?php }?>
<tr>
<td width=229 class="hmenu_font">Auction Image1 </td>
<td width="429"><input name="img1" type="file" id="img1" />
(Recommended Size 1110x585)
  <div class="error"><?=$this->session->userdata('error_img1');?></div></td>
</tr>
<?php if($data_auction->image2){?>
<tr>
  <td class="hmenu_font"></td>
  <td>
  <img src='<?php print(site_url(AUCTION_IMG_PATH.'thumb_'.$data_auction->image2));?>' >
  <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/delete_image/img2/<?php print($data_auction->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>
  </td>
</tr>
<?php }?>
<tr>
<td width=229 class="hmenu_font">Auction Image2</td>
<td width="429"><input name="img2" type="file" id="img2" />
(Recommended Size 1110x585)
  <div class="error"><?=$this->session->userdata('error_img2');?></div> </td>
</tr>

<?php if($data_auction->image3){?>
<tr>
  <td class="hmenu_font"></td>
  <td>
  <img src='<?php print(site_url(AUCTION_IMG_PATH.'thumb_'.$data_auction->image3));?>'>
  <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/delete_image/img3/<?php print($data_auction->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>
  </td>
</tr>
<?php }?>
<tr>
<td width=229 class="hmenu_font">Auction Image3</td>
<td width="429"><input name="img3" type="file" id="img3" />
(Recommended Size 1110x585)
  <div class="error"><?=$this->session->userdata('error_img3');?></div></td>
</tr>

<?php if($data_auction->image4){?>
<tr>
  <td class="hmenu_font"></td>
  <td><img src='<?php print(site_url(AUCTION_IMG_PATH.'thumb_'.$data_auction->image4));?>'>
  <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/auction/delete_image/img4/<?php print($data_auction->id);?>"> 
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/delete.gif' title="Delete" onClick="return doconfirm();" ></a>
  </td>
</tr>
<?php }?>
<tr>
<td width=229 class="hmenu_font">Auction Image4</td>
<td width="429"><input name="img4" type="file" id="img4" />
(Recommended Size 1110x585)
  <div class="error"><?=$this->session->userdata('error_img4');?></div></td>
</tr>

<tr>
  <td class="hmenu_font">Is Display?</td>
  <td>
  <input name="is_display" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_display" type="radio" value="No" <?php if($data_auction->is_display == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr>

<!-- <tr>
  <td class="hmenu_font">Is Bidbutler?</td>
  <td>
  <input name="is_bidbutler" type="radio" value="Yes" checked="checked" />
    Yes
      <input name="is_bidbutler" type="radio" value="No"<?php if($data_auction->is_bidbutler == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr>

<tr>
  <td class="hmenu_font">Is Bid Package?</td>
  <td>
  <input name="is_bid_package" type="radio" value="Yes"  <?php if((isset($_POST['is_bid_package']) && $_POST['is_bid_package'] == 'Yes') || $data_auction->is_bid_package == 'Yes'){ echo 'checked="checked"';}?> />
    Yes
      <input name="is_bid_package" type="radio" value="No" <?php if((isset($_POST['is_bid_package']) && $_POST['is_bid_package'] == 'No') || $data_auction->is_bid_package == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr> -->

<tr>
  <td class="hmenu_font">Is Buy Now?</td>
  <td>
  <input name="is_buy_now" type="radio" value="Yes" onclick="show_buy_now()" checked="checked" />
    Yes
      <input name="is_buy_now" type="radio" value="No" onclick="hide_buy_now()"  <?php if((empty($_POST['is_buy_now']) && $data_auction->is_buy_now == 'No') || (isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == 'No')){ echo 'checked="checked"';}?> />
      No  </td>
</tr>

<tr id="buy_now_qty" <?php if((isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == 'Yes') || $data_auction->is_buy_now == 'Yes'){ echo '';}else{echo 'style="display:none;"';}?>>
  <td class="hmenu_font">Buy Now Quantity</td>
  <td>
  <input name="no_qty" type="text" class="inputtext" id="no_qty" value="<?php echo set_value('no_qty',$data_auction->no_qty);?>" size="10" />
  </td>
</tr>


<tr height="30">
  <td colspan="2">&nbsp;</td>
</tr>
<tr height="30">
  <td colspan="2"><div style=" background-color:#FFFFFF; padding:5px 10px;"><strong>Auction Details <?php if(count($data_auction_details)!=1){?>Based on Language<?php }?> </strong></div></td>
</tr>

<tr>
  <td colspan="2">
  <?php //echo count($data_auction_details);?>
  <div class="" >
<ul id="mytabs" class="shadetabs" <?php if(count($data_auction_details)==1){ echo 'style="display:none"';}?>>
					  <?php for($i=0; $i<count($data_auction_details); $i++){?>
                      <li><a href="JavaScript:void(0);" rel="tab<?php echo $i;?>"><span><?php echo $data_auction_details[$i]->lang_name;?></span></a></li>
					  <?php }?>
                      <li><a href="JavaScript:void(0);" style="background:none;border-bottom: #CCC 1px solid;"><span style="background:none;width:69px;">&nbsp;</span></a></li>
                    </ul> 	
<div class="clearboth"></div>

<?php for($i=0; $i<count($data_auction_details); $i++){?>
<div id="tab<?php echo $i;?>" class="tabcontent">
<table align=left cellpadding=2 cellspacing=4 width=99% border="0" class="light">

<tr>
      <td width="660"><strong>Auction Name <?php if(count($data_auction_details)!=1){?>(<?php echo $data_auction_details[$i]->lang_name;?>)<?php }?> </strong></td>
</tr>

<tr>
<td>
<?php $auc_name = ''; $name = $this->input->post('name'); if(!empty($name[$data_auction_details[$i]->lang_id])){ $auc_name = $name[$data_auction_details[$i]->lang_id]; }else{$auc_name = $data_auction_details[$i]->name;}?>
<input size="50" class="inputtext" type="text" id="name[<?php echo $data_auction_details[$i]->lang_id;?>]" name="name[<?php echo $data_auction_details[$i]->lang_id;?>]" value="<?php echo $auc_name; ?>">
<?php echo '<div class="error">'.$this->session->userdata('name_'.$data_auction_details[$i]->lang_id)."</div>"; ?></td>
</tr>


<tr>
      <td><strong>Acution Description <?php if(count($data_auction_details)!=1){?>(<?php echo $data_auction_details[$i]->lang_name;?>)<?php }?></strong></td>
</tr>

<tr>
<td>
		<?php $description = ''; $description2 = $this->input->post('description'); if(!empty($description2[$data_auction_details[$i]->lang_id])){ $description = $description2[$data_auction_details[$i]->lang_id]; }else{$description = $data_auction_details[$i]->description;}?>
		<?php echo form_fckeditor('description['.$data_auction_details[$i]->lang_id.']', $description );	?>
		<?php echo '<div class="error">'.$this->session->userdata('description_'.$data_auction_details[$i]->lang_id)."</div>"; ?>		</td>
</tr>
<tr>
      <td width="660"><strong>Page Title <?php if(count($data_auction_details)!=1){?>(<?php echo $data_auction_details[$i]->lang_name;?>)<?php }?></strong></td>
</tr>

<tr>
<td>
<?php $page_title = ''; $page_title2 = $this->input->post('page_title'); if(!empty($page_title2[$data_auction_details[$i]->lang_id])){ $page_title = $page_title2[$data_auction_details[$i]->lang_id]; }else{$page_title = $data_auction_details[$i]->page_title;}?>
<textarea cols="60" rows="1" id="page_title[<?php echo $data_auction_details[$i]->lang_id;?>]" name="page_title[<?php echo $data_auction_details[$i]->lang_id;?>]"><?php echo $page_title;?></textarea></td>
</tr>
<tr>
      <td width="660"><strong>Meta Keys <?php if(count($data_auction_details)!=1){?>(<?php echo $data_auction_details[$i]->lang_name;?>)<?php }?></strong></td>
</tr>

<tr>
<td>
<?php $meta_key = ''; $meta_key2 = $this->input->post('page_title'); if(!empty($meta_key2[$data_auction_details[$i]->lang_id])){ $meta_key = $meta_key2[$data_auction_details[$i]->lang_id]; }else{$meta_key = $data_auction_details[$i]->meta_keys;}?>
<textarea cols="60" rows="2" id="meta_key[<?php echo $data_auction_details[$i]->lang_id;?>]" name="meta_key[<?php echo $data_auction_details[$i]->lang_id;?>]"><?php echo $meta_key;?></textarea></td>
</tr>
<tr>
      <td width="660"><strong>Meta Description <?php if(count($data_auction_details)!=1){?>(<?php echo $data_auction_details[$i]->lang_name;?>)<?php }?></strong></td>
</tr>
<tr>
<td>
<?php $meta_desc = ''; $meta_desc2 = $this->input->post('page_title'); if(!empty($meta_desc2[$data_auction_details[$i]->lang_id])){ $meta_desc = $meta_desc2[$data_auction_details[$i]->lang_id]; }else{$meta_desc = $data_auction_details[$i]->meta_desc;}?>
<textarea cols="60" rows="2" id="meta_desc[<?php echo $data_auction_details[$i]->lang_id;?>]" name="meta_desc[<?php echo $data_auction_details[$i]->lang_id;?>]"><?php echo $meta_desc;?></textarea></td>
</tr>






<tr height=25 valign="middle">
  <td>&nbsp;</td>
</tr>
</table>
<div class="clearboth"></div>
</div>
 <input size="50" class="inputtext" type="hidden" id="lang_id[]" name="lang_id[]" value="<?php echo $data_auction_details[$i]->lang_id;?>">
  <input size="50" class="inputtext" type="hidden" id="auc_details_id[]" name="auc_details_id[]" value="<?php echo $data_auction_details[$i]->id;?>">
 <?php }?>
</div> 

</td>
  </tr>
<tr height="30">

  <td>&nbsp;</td>
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Update" />  </td>
</tr>
</table>

<?php if($jobs == "Copy"){?>

<input type="hidden" name="copy_img1" value="<?php echo $data_auction->image1;?>" />
<input type="hidden" name="copy_img2" value="<?php echo $data_auction->image2;?>" />
<input type="hidden" name="copy_img3" value="<?php echo $data_auction->image3;?>" />
<input type="hidden" name="copy_img4" value="<?php echo $data_auction->image4;?>" />

<?php }?>
</form>

 </div>
    <div class="clear"></div>
</div>

<script>
var mytabs_obj=new ddtabcontent("mytabs")
mytabs_obj.setpersist(true)
mytabs_obj.setselectedClassTarget("link") //"link" or "linkparent"
mytabs_obj.init()


function show_buy_now(){
	$("#buy_now_qty").show();
}

function hide_buy_now(){
	$("#buy_now_qty").hide();
}
</script> 	