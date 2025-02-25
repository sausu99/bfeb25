<link href="<?php print(USER_THEME_CSS);?>select2.min.css" rel="stylesheet" type="text/css" />
<script src="<?php print(USER_THEME_JS);?>select2.js" type="text/javascript"></script>
<script>
  $(document).ready(function(){ 

      $("#category").select2(); 
  });
</script>
<div class="content">
  <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  Categories  Management </span></div>
  <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;"> <a href="javascript:history.go(-1)" style="text-decoration:none;"> <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" /> </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
  <h2><?php echo $jobs;?> Auction </h2>
  <div class="mid_frm">
    <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
      <?php if($this->session->flashdata('message')){
        echo "<div class='message'>".$this->session->flashdata('message')."</div>";
      }
      ?>
    </div>
    <form name="sitesetting" method="post" action=""  enctype="multipart/form-data" accept-charset="utf-8" id="form-cat-add">
      <table width=100% align=center border=0 cellspacing=4 cellpadding=2 class="light">
        <tr>
          <td class="hmenu_font"><strong>Language Name</strong></td>
          <td> <select name="language" id="language">
              <option value="">Select Language</option>
            <?php if($languages): ?>
              <?php $lang_id = $this->input->post('language') ? $this->input->post('language'): $cat_data->lang_id; ?>
              <?php foreach ($languages as $lang) { ?>
                <option value="<?php echo $lang->id; ?>" <?php if($lang->id == $lang_id){ echo 'selected="selected"'; } ?> > <?php echo $lang->lang_name; ?></option>
                <?php } ?>
              <?php endif ?>
            </select> <?=form_error('language')?></td>
          </tr>
          <tr>
            <td class="hmenu_font"><strong>Category Name</strong></td>
            <td><select name="cat_id" id="category">
              <option value="">Choose Category</option>
              <?php if($category){
                foreach($category as $cat){?>
                  <option value="<?php echo $cat->id?>" <?php if($cat->id==$cat_data->parent_id) echo 'selected';?>><?php echo $cat->name;?></option>
                  <?php }
                }?>
              </select>
              <?=form_error('cat_id')?></td>
            </tr>
            <tr>
              <td class="hmenu_font"><strong>Sub Category Name</strong></td>
              <td><input size="50" class="inputtext" type="text" id="name" name="name" value="<?php echo set_value('name',$cat_data->name);?>">
                <?=form_error('name')?></td>
              </tr>
              <tr>
                <td class="hmenu_font">Is Display?</td>
                <td><input type="radio" name="is_display" value="1" checked="checked" />
                  Yes
                  <input name="is_display" type="radio" value="0" <?php if($cat_data->is_display =='0'){ echo 'checked="checked"';}?>/>
                  No <b>(To display the auction or not???)</b></td>
                </tr>
                  <tr>
              <td class="hmenu_font"><strong>Display Order</strong></td>
              <td><input size="20" class="inputtext" type="text" id="order_subcat" name="order_subcat" value="<?php echo set_value('order_subcat', $cat_data->order_by); ?>">
                <?=form_error('order_subcat')?></td>
              </tr>
                <tr height="30">
                  <td>&nbsp;</td>
                  <td colspan="2"><input class="bttn" type="submit" name="Submit" value="Submit" id="btnAuctionAdd" /></td>
                </tr>
              </table>
            </form>
          </div>
          <div class="clear"></div>
        </div>
        
          <?php $language = $this->input->post('language') ? $this->input->post('language'): $cat_data->lang_id; 
            $cat_id = $this->input->post('cat_id') ? $this->input->post('cat_id'): $cat_data->parent_id; 
          if($language > 0){ ?>
            <script>
              var lang_id = "<?php echo $language; ?>";
              var cat_id = "<?php echo $cat_id; ?>"
              if (lang_id != '') {
              $.ajax({
                type: 'POST',
                url: "<?php echo site_url(ADMIN_DASHBOARD_PATH.'/product-categories/get_categories'); ?>",
                dataType: 'html',
                data: {
                  lang_id: lang_id, cat_id: cat_id
                },
                success: function (data){
                  $('#category').html(data); 
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                  alert('error');
                }
              });
            }else{
              $('#category').empty();
              $('#category').append('<option value="">First Select Language</option>')    
            } 

            </script>
          <?php }   ?>

        <script type="text/javascript">
          $('#language').on('change', function(){   
            var lang_id = $('#language').val(); 
            if (lang_id != '') {
              $.ajax({
                type: 'POST',
                url: "<?php echo site_url(ADMIN_DASHBOARD_PATH.'/product-categories/get_categories'); ?>",
                dataType: 'html',
                data: {
                  lang_id: lang_id,
                },
                success: function (data){
                  $('#category').html(data); 
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                  alert('error');
                }
              });
            }else{
              $('#category').empty();
              $('#category').append('<option value="">First Select Language</option>')    
            }
          });

          
        </script>