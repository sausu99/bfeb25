<div style="color:#CC0000; width:610px; margin-left:0px; font-weight:bold;" align="center">
<?php if($this->session->flashdata('message')){
		echo "<div class='message'>".$this->session->flashdata('message')."</div>";
		}
	?></div>
 <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/site-settings/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting1">Site Settings </td>
    </tr>
  </table>
</div>
</a> 
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/country/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody><tr>
      <td valign="top" align="center" class="setting13">Country List </td>
    </tr>
  </tbody></table>
</div>
</a>

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/language-settings/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting6">Language Settings</td>
    </tr>
  </table>
</div>
</a> 

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/cms/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting13">Content Management System</td>
    </tr>
  </table>
</div>
</a>
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting7">Auction Management </td>
    </tr>
  </table>
</div>
</a>
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/payment/paypal')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting19">Payment Gateway System</td>
    </tr>
  </table>
</div>
</a>
  <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/banner/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting18"> Banner Management </td>
    </tr>
  </table>
</div>
</a> 


<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/email-settings/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting4">E-mail Settings </td>
    </tr>
  </table>
</div>
</a>
 <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/bidpackage/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting11">Bid Package Management </td>
    </tr>
  </table>
</div>
</a>

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/bonuspackage/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="bonus">Bonus Point Management </td>
    </tr>
  </table>
</div>
</a> 
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/members/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting10">Members Management </td>
    </tr>
  </table>
</div>
</a>
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/block-ip/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting8">Blocked IP </td>
    </tr>
  </table>
</div>
</a>
<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/change-password/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting17">Change Password </td>
    </tr>
  </table>
</div>
</a> 

 
   <a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction-winner/index')?>">
<div class="controlpnl">
	<?php if($total_notship_auc > 0){?><div class="badge"><?php echo $total_notship_auc;?></div><?php }?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting12"> Winner Management</td>
    </tr>
  </table>
</div>
</a>


<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/help-category/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting5">Help Category Management</td>
    </tr>
  </table>
</div>
</a>

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/help/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting16">Help Management</td>
    </tr>
  </table>
</div>
</a>

<?php /*?><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/testimonial/index')?>">
<div class="controlpnl">
<?php if($total_pending_testimonial > 0){?><div class="badge"><?php echo $total_pending_testimonial;?></div><?php }?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="testimonial">Testimonial Management</td>
    </tr>
  </table>
</div>
</a><?php */?>

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/how-it-works/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="how_it_works">How it Works Management</td>
    </tr>
  </table>
</div>
</a>

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/vouchers/index')?>">
<div class="controlpnl">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting3">Vouchers Management</td>
    </tr>
  </table>
</div>
</a> 

<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/aucorder/index')?>">
<div class="controlpnl">
<?php if($total_notship_ord_auc > 0){?><div class="badge"><?php echo $total_notship_ord_auc;?></div><?php }?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" align="center" class="setting6">Auction  Order Management</td>
    </tr>
  </table>
</div>
</a> 