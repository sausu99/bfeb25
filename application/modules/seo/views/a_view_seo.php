<h1></h1><div class="content">
    
    <div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo; SEO  Management </span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
  <h2>SEO </h2>
    <div class="mid_frm">
 <?php if(count($lang_details)!=1){?>
      <div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin:10px 0 10px 0;">
      <ul id="vList">      
           <li>[ <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/seo/add/<?php echo $lang_id;?>">Add SEO</a> ]</li>
           
       <?php
          foreach($lang_details as $lang)
        {
    ?>
      <li>[ <?php if($lang_id != $lang->id){?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/seo/index/<?php echo $lang->id;?>"><?php }?><?php echo $lang->lang_name;?></a> ]</li>
     <?php
        }
    ?>
      </ul>
      <div style="clear:both"></div>
      </div>
          <?php }?>
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
    echo "<div class='message'>".$this->session->flashdata('message')."</div>";
    }
  ?></div>
    
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
       <tr>
                          <th width="8%" class="aligns">S.N</th>
                            <th width="18%" class="aligns">Page name</th>
                            <th width="15%" class="aligns">Meta Key</th>
                            <th width="15%" class="aligns">Meta Description</th>
                            <th width="18%" class="aligns">Created Date</th>
                            <th width="18%" class="aligns">Last Update</th>
                            <th width="8%" class="optn"> Operations </th>
                        </tr>
  <?php 
    
                    $sn_count=0;
                    $i=1;
                    if($seo_data)
                    {
                        foreach($seo_data as $value)
                        { ?>
                          <tr>
                            <td class="aligns"><?php echo $i; ?>.</td>
                            <td class="aligns"><?php echo $this->general->string_limit($value->page_title,25); ?></td>
                            <td class="aligns"><?php echo $this->general->string_limit($value->meta_key,25); ?></td>
                            <td class="aligns"><?php echo $this->general->string_limit($value->meta_description,25); ?></td>
                            <td class="aligns">
                           <?php print($this->general->convert_local_time($value->created_date));?>
                            </td>
                            <td class="aligns"><?php if($value->last_update!='0000-00-00 00:00:00')
                            {
                              // echo $this->general->long_date_time_format($value->last_update);
                              ?>
                                 <?php print($this->general->convert_local_time($value->last_update));?>
                              <?php
                            } 
                            else {echo "Not Updated Yet";} ?></td>
                            <td>
                               <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>/seo/edit/<?php echo $value->parent_id;?>/<?php echo $value->lang_id;?>" style="margin-right:5px;">
      <img src='<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/edit.gif' title="Edit SEO" alt="Edit SEO" width="16" height="16"></a> 
                            </td>
                          </tr>
                        <?php 
                        $i++;
                        }
                    }
                   
      else
      {
  ?>
   <tr> 
    <td colspan="8" align="center" style="border-right:none;"> (0) Zero Record Found </td>
    </tr>
  <?php
        }
  ?>
</table>
</div>
    <div class="clear"></div>
</div>
  