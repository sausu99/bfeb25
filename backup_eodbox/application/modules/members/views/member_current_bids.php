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
		<table width='100%'  align=left height='90%' border='0' cellpadding='0' cellspacing='0'>
          <tr><td height="30"><span class="product_info">Standard Auction Bid Summary</span>
            <P>
	    <span class="normal">&nbsp;An overview of all live bids in standard auctions by <?php echo $profile->first_name.' '.$profile->last_name;?></strong>. </span><br />
	    <br />
          </td>
          </tr>
		  
		  <tr> 
            <td valign='top' class='body-text' align=left> 
			
				
			<table width="99%" border="0" align="left" cellpadding="2" cellspacing="2">

	<?php 			
	
			if($result_data)
			{
				foreach($result_data as $data)
				{
			?>
				<tr>
				  <td class="menutop1"><strong>
<?php /*?>				<?php echo anchor('auction/admin/auctions/'.$data->product_id , $data->name . '- Auction No:' . $data->product_id); ?>				  <?php */?>
				  <?=$data->name;?> - Auction No: <?=$data->product_id;?>
		</strong>	</td>
			      </tr>
			  
				<tr>
				  <td>

				  <table width="56%" border="0" cellspacing="0" cellpadding="4" class="contentbl">
                    <tr>
                      <th   width="41%" height="20" align="left">&nbsp;Date &amp; Time</th>
                      <th   width="18%" height="20" align="left">Bids</th>
                      <th   width="41%" height="20" align="left">Level of Uniqueness </th>
                    </tr>
					
					<?php
					$result=$this->admin_member->select_user_bid_detail($profile->id,$data->product_id);
					for($x=0;$x<sizeof($result);$x++)
					{
					?>
				    <tr>
                      <td height="20" align="left" valign="middle" class="normal11"><?php echo $this->general->convert_local_time($result[$x]->bid_date);?></td>
                      <td align="left" ><?php echo $result[$x]->userbid_bid_amt; 
                      //echo "XXXX";
                      ?> </td>					  
                      <td align="left" valign="middle" class="normal11">
					  					<?php
					$this->bidstatus=$this->general->getBidStatus1($result[$x]->userbid_bid_amt,$result[$x]->auc_id);
					if($this->bidstatus=="not_unique")
					{
					echo "<strong>"."<font color='red'>Not Unique</font>"."</strong>";
					}
					else if($this->bidstatus=="Lowest_Unique_Bid")
					{
					echo "<strong>"."<font color='green'>Lowest Unique Bid</font>"."</strong>";
					}
					else if($this->bidstatus=="Unique_But_Not_Lowest")
					{
					echo "<strong>"."<font color='blue'>Unique But Not Lowest</font>"."</strong>";
					}
					?>
					  </td>
                    </tr>
					<?php } ?>
               
				  </table>				  </td>
				  <td>				  </td>
			  </tr>
			  
							  
			  <tr>
				  <td><img src="images/spacer.gif" width="1" height="1" /></td>
			  </tr>
				<?php }}else{ ?>
				   <br />
					<?php echo $profile->user_name;?> does not have any live bids in standard auctions.
				  <?php }	?>
            </table>			
			
			
			</td>
          </tr>
		  
	
       <td >&nbsp;</td> 
       </tr>
	 
		</table>		</td>
      </tr>
     
    </table></td>
                                    <td>&nbsp;</td>



                                  </tr>
  
                                </table></td>
                              </tr>
</table>
</div>
    <div class="clear"></div>
</div>
