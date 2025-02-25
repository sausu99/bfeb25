<script type="text/javascript">
function doconfirm()
{
	job=confirm("Are you sure to delete permanently?");
	if(job!=true)
	{
		return false;
	}
}
</script>
<div style="padding:6px 20px; float:left; width:610px; background:#202020; color:#ABABAB;"><span class="breed"><a href="<?=site_url(ADMIN_DASHBOARD_PATH)?>">ADMIN</a> &raquo;  <a href="<?=site_url(ADMIN_DASHBOARD_PATH).'/members/index'?>">Member  Management </a></span></div>
    <div style="padding:3px 20px; float:right; width:80px; background:#202020; color:#ABABAB; line-height:18px;">
    <a href="javascript:history.go(-1)" style="text-decoration:none;">
    <img src="<?php print(ADMIN_IMG_DIR_FULL_PATH);?>/back.gif" width="18" height="18" alt="back" style="padding:0; margin:0; width:18px; height:18px;" align="right" />
    </a><span class="breed"><a href="javascript:history.go(-1)"> go Back</a></span></div>
	<h2>View Member Bidding Details </h2>
    <div class="mid_frm">
    
     <div align="center"><?php $this->load->view('menu');?></div>
     <div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>

<table width="98%"  border="5"  align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" >
                             
                             
                              <tr>
                                <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
      
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="green_border">
      <tr>
        <td width="100%" >
		<!--the next table code goes here style="padding-top:8px" -->
		<table width='100%'  height='90%' border='0' cellpadding='0' cellspacing='0'>
          <tr><td height="30"><span class="product_info">Standard Auction Bid Summary</span>
            <P>
	    <span class="normal">&nbsp;An overview of all live bids in standard auctions by <?php echo $profile->first_name.' '.$profile->last_name;?></strong>. </span><br />
	    <br />
          </td>
          </tr>
		  
		  <tr> 
            <td> 
			
				
			<table width="99%" class="contentbl"  border="0" align="left" cellpadding="2" cellspacing="2">
            <tr>
			<th>Auction Details</th>
			<th>Winning Amount</th>
			</tr>
			
	<?php 			
			if($result_data)
			{
				foreach($result_data as $data)
				{
			?>
			<tr>						
				  <td align="center">
<?php
$atts = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
            );
				 				  				
echo anchor_popup(ADMIN_DASHBOARD_PATH.'/members/popup_detail_bid/'.$data->product_id.'/'.$profile->id.'', $data->name . '- Auction No:' . $data->product_id , $atts);
?>				  
				 <?php /*?><strong><?=$data->name;?> - Auction No: <?=$data->id;?></strong><?php */?>
				 </td>
			      <td align="center"><?php echo $user_currency_sign." ";?><?=$data->current_winner_amount;?></td>
			</tr>
			  
	   <?php }}
	   else { ?>
	   <tr>
	   <td colspan="2">
				   <?php echo $profile->user_name;?> has not won any standard auctions.
				  <?php }?>
				</td>  
			</tr>	  
			  
            </table>			
			
			
			</td>
          </tr>
		  
	
       <td >&nbsp;</td> 
       </tr>
		</table>		
		</td>
      </tr>
     
    </table>
	</td>
     <td>&nbsp;</td>
         </tr>  
     </table></td>
      </tr>
</table>


</div>