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
<link href="<?php echo base_url(DROPZONE_PATH.'dropzone.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url(DROPZONE_PATH.'dropzone.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
  
  var UrlDeleteImage = "<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction/ajax_delete_product_image');?>";
  
  var UrlRemoveDropzoneTempImage = "<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction/ajax_delete_product_temp_images'); ?>";
</script>
<script type="text/javascript">
$(document).ready(function() {
  Dropzone.options.myAwesomeDropzone = {
    maxFiles:  '<?php echo $images_quota; ?>',
    // maxFiles: 2,
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.bmp",
    addRemoveLinks: true,
    dictRemoveFile: "Remove Image",
    // dictDefaultMessage : (parseInt('<?php //echo $images_quota; ?>')>0?"Drop files here to upload":"Remove images to upload new image"),    
    dictDefaultMessage : "Drop files or click here to upload",      
    //default message displayed before any files are dropped
    
    init: function() {
      myDropzone = this;
      
      myDropzone.on("maxfilesexceeded", function(file) {
        this.removeFile(file);
        console.log('exceeded');
        $('.dropzoneResponse').show();
        $('.dropzoneResponse').html('You can upload only <?php echo $images_quota; ?> images.');
      });
      
      myDropzone.on("sending", function(file, xhr, formData){
        formData.append('pcodeimg', '<?php echo $product_code; ?>');
        formData.append('Developer', 'openpradip@yahoo.com'); //testing append function
      });
    
      myDropzone.on("success", function(file, response){
        response =  jQuery.parseJSON(response);
        if(response.status=='success'){
          //add attribute name to the remove image element
          $(file.previewTemplate).find('.dz-remove').attr('data-name', response.name);
        }
      });
    
      myDropzone.on("removedfile",function(file) {
        var name = $(file.previewTemplate).find('.dz-remove').attr('data-name');
        //console.log("name : " + name);
        var id = $(file.previewTemplate).find('.dz-remove').attr('data-imgid');
       
        if(name != '' && name != undefined) { 
           $.ajax({
            type: 'POST',
            url: UrlRemoveDropzoneTempImage,
            data: {name:name},
            dataType: 'json',
            success: function(response) {
              console.log(response);
              $('#'+id).hide();
              //response =  jQuery.parseJSON(response);
              //peform tasks here if we needto perform any task after success
            }
          }); 
        }
      });
    },
  };
});
</script>
<div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Auction  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2><?php echo $jobs;?> Auction </h2>
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
<!-- <tr>
  <td class="hmenu_font">Minimun Bids Value</td>
  <td>
  <input name="min_bids_value" type="text" class="inputtext" id="min_bids_value" value="<?php echo set_value('min_bids_value',$data_auction->min_bids_value);?>" size="10" />
  Bid(s)
    <?=form_error('min_bids_value')?></td>
</tr> -->
<tr>
  <td class="hmenu_font">SMS Code</td>
  <td>
  <input name="sms_code" type="text" class="inputtext" id="sms_code" value="<?php echo set_value('sms_code',$data_auction->sms_code);?>" size="10" />
    <?=form_error('sms_code')?></td>
</tr>

<!-- <tr>
  <td class="hmenu_font">Auction Reset Time</td>
  <td><input name="reset_time" type=text class="inputtext" id="reset_time" value="<?php echo set_value('reset_time',$data_auction->reset_time);?>" size="10"> Sec
<?=form_error('reset_time')?></td>
</tr> -->
<tr>
  <td class="hmenu_font">Start Date </td>
    <?php $db_start_date=$this->general->convert_local_time($data_auction->start_date); ?>
  <td><input name="start_date" type="text" class="inputtext" id="start_date" value="<?php echo set_value('start_date',$db_start_date);?>" size="20"  readonly="readonly" />
  <img src="<?php print(ASSETS_CALENDER);?>Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('start_date','yyyyMMdd','arrow',true,'24')" />
    <?=form_error('start_date')?></td>
</tr>
<tr>
  <td class="hmenu_font">End Date </td>
  <td>
  <?php $db_end_date=$this->general->convert_local_time($data_auction->end_date); ?>

  <input name="end_date" type="text" class="inputtext" id="end_date" value="<?php echo set_value('end_date',$db_end_date);?>" size="20"  readonly="readonly" />
  <img src="<?php print(ASSETS_CALENDER);?>Image/cal.gif" style="cursor: pointer;" onclick="javascript:NewCssCal('end_date','yyyyMMdd','arrow',true,'24')" />
    <?=form_error('end_date')?>	</td>
<tr>    
<td class="hmenu_font">Auction Images </td>
<td width="429">

            <div class="dropzoneResponse" style="display:none;color: red"></div>
            <div action='<?php echo site_url('/'.ADMIN_DASHBOARD_PATH.'/auction/multiple_image_ajax_uploader')?>' class='form-group dropzone' id='my-awesome-dropzone'>
            <?php
            // echo '<pre>';
            // print_r($product_images);
            // exit;
              if($product_images){  
                for($i = 1; $i<7; $i++){
                  $name = 'image'.$i;
                  if($product_images->$name !=''){
               ?> 

              <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete" id="<?php echo $random.$i;?>">
                    <div class="dz-image edit-image">

                      <img alt="<?php echo $product_images->$name; ?>" src="<?php echo site_url(AUCTION_IMG_PATH.'thumb_'.$product_images->$name); ?>" />

                    </div>
                    <div class="dz-details">
                      <div class="dz-filename"> <span><?php echo $product_images->$name; ?></span>
                      </div>
                    </div>
            <a href="javascript:void(0);" data-column="<?php echo $name; ?>" class="dz-remove remove_image" data-job="<?php echo $jobs; ?>" data-imgname="<?php echo $product_images->$name; ?>" data-imgid="<?php echo $random.$i; ?>" data-pid='<?php echo ($jobs=='Edit' || $jobs=='Copy')?$product_images->id:''; ?>'  data-pcode="<?php echo ($jobs=='relist')?$product_code:''; ?>">Remove file</a> </div>

                <?php } } }

              if($product_images_temp){
                foreach($product_images_temp as $image)
                {
                  ?>
                  <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete" id="<?php echo $random;?>">
                    <div class="dz-image edit-image">

                      <img alt="<?php echo $image->image; ?>" src="<?php echo site_url(AUCTION_TEMP_PATH.$image->image); ?>" width="240" height="240" />

                    </div>
                    <div class="dz-details">
                      <div class="dz-filename"> <span><?php echo $image->image; ?></span>
                      </div>
                    </div>
       <a href="javascript:void(0);" class="dz-remove remove_image" data-job="<?php echo 'add'; ?>" data-imgname="<?php echo $image->image; ?>" data-imgid="<?php echo $random; ?>" data-pid='<?php echo ($jobs=='edit')?$product->id:''; ?>'  data-pcode="<?php echo ($jobs=='relist')?$product_code:''; ?>">Remove file</a> </div>
                  <?php
                  }
                } ?>


              </div>
     <input type="hidden" name="pcodeimg" id="pcodeImg" value="<?php echo $product_code; ?>" />
     Recommended Size 1110x585</td>
</tr>
<tr>
  <td class="hmenu_font">Is featured?</td>
  <td>
  <input name="is_featured" type="radio" value="Yes" <?php if($data_auction->is_featured == 'Yes'){ echo 'checked="checked"';}?>/>
    Yes
      <input name="is_featured" type="radio" value="No" <?php if($data_auction->is_featured == 'No'){ echo 'checked="checked"';}?> />
      No  </td>
</tr

><tr>
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

<tr class="buy_now_qty" <?php if((isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == 'Yes') || $data_auction->is_buy_now == 'Yes'){ echo '';}else{echo 'style="display:none;"';}?>>
  <td class="hmenu_font">Buy Now Quantity</td>
  <td>
  <input name="no_qty" type="text" class="inputtext" id="no_qty" value="<?php echo set_value('no_qty',$data_auction->no_qty);?>" size="10" />
  </td>
</tr>

<tr class="buy_now_qty" <?php if((isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == 'Yes') || $data_auction->is_buy_now == 'Yes'){ echo '';}else{echo 'style="display:none;"';}?>>
  <td class="hmenu_font">Buy Now Price</td>
  <td>
  <input name="buy_now_price" type="text" class="inputtext" id="buy_now_price" value="<?php echo set_value('buy_now_price',$data_auction->buy_now_price);?>" size="10" />
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
  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Submit" />  </td>
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
	$(".buy_now_qty").show();
}

function hide_buy_now(){
	$(".buy_now_qty").hide();
}

 $(".remove_image").click(function(e) {
  // e.preventDefault();
  //e.stopPropagation();
  // alert('test');
  var imgid = $(this).data('imgid');
  var imgname = $(this).data("imgname");
  var pid = $(this).data("pid");
  var job = $(this).data('job');
  var column = $(this).data('column');
  // alert(pid);
  // return false;
  var pcode = $(this).data('pcode');
  // alert(pid);
  console.log('Job:' + job + ' # image id:' + imgid + ' # Image Name:'+ imgname + ' # Product ID:' + pid + ' #Pcode:' + pcode);
  
 // if((pid!='' && imgname!='') || (job=='relist' && imgname!='')){
    // alert('here');
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: (job=='Edit' || job=='Copy' )?UrlDeleteImage:UrlRemoveDropzoneTempImage,
      data: {
        name: imgname,
        pid: pid,
        pcode: pcode,
        column:column
      },
      success: function(data){
        //console.log(data);
        if (data.result == "success") {
          //remove image from dropzone area
          $('#' + imgid).hide(1000,function() { $(this).remove(); } );
          //console.log('#' + imgname);
          //alert('#' + imgname);
          
          //add maxFile limit in dropzone config file
          
          myDropzone.options.maxFiles = data.image_quota;
          //myDropzone.options.dictDefaultMessage = "Pradip";
          myDropzone.options.dictDefaultMessage = (data.image_quota>0?"Drop files here to upload":"Remove images to upload new image");
        } else {
          //do nothing                          
        }
      },
      error: function(errors) {
        console.log(errors);
      }
    });
  //}
});
</script> 	