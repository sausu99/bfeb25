<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <?php echo $template['title']; ?> </title>
<base href="<?php echo site_url();?>" />
<link href="<?php print(site_url(ADMIN_CSS_DIR_FULL_PATH));?>/style.css" rel="stylesheet" type="text/css" />
<?php /*?><link REL="SHORTCUT ICON" href="<?=base_url()?>favicon.ico" /><?php */?>
<script type="text/javascript" src="<?php print(site_url(ADMIN_JS_DIR_FULL_PATH));?>/jquery.js"></script>
<script type="text/javascript" src="<?php print(site_url(ADMIN_JS_DIR_FULL_PATH));?>/validation.error.messages.js"></script>
<script type="text/javascript" src="<?php print(site_url(ADMIN_JS_DIR_FULL_PATH));?>/jquery.validate.min.js"></script>


<link href="<?php print(site_url(ADMIN_CSS_DIR_FULL_PATH));?>/paging.css" rel="stylesheet" type="text/css" />
<script src="<?php print(site_url(ADMIN_JS_DIR_FULL_PATH));?>/DD_roundies_0.0.2a.js" type="text/javascript"></script>
   
<script type="text/javascript">
var siteurl='<?php echo base_url();?>';
var baseUrl='<?=base_url()?>';
DD_roundies.addRule('#box', '6px', true);
DD_roundies.addRule('#box h1', '6px 6px 0 0', true);
DD_roundies.addRule('.btn', '4px', true);
</script>

<script src="<?php print(site_url(ADMIN_JS_DIR_FULL_PATH));?>/timer.js" type="text/javascript"></script>  

<script type="text/javascript">
var myvar;
function menuinit() {
document.getElementById('m1').style.display = 'none';

document.getElementById('m3').style.display = 'none';
//document.getElementById('m4').style.display = 'none';
document.getElementById('pm1').src = '<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblt.gif';

document.getElementById('pm3').src = '<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblt.gif';
//document.getElementById('pm4').src = '<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>sidenavblt.gif';
}
function menuexpand (i) {
        menuinit();
        if (myvar == i) {
		document.getElementById('p' + i).src = '<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblt.gif';
		document.getElementById(i).style.display = 'none';
		myvar = '';
	}
        else {
		document.getElementById('p' + i).src = '<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblthvr.gif';
		document.getElementById(i).style.display = 'block';
		myvar = i;
	}
}
</script>

</head>

<body>
<?php
$this->general->updateOnlineMemberByTime();

$total_member=$this->general->get_all_total();
define('TOTAL_MEMBER',$total_member);

$join_today_member=$this->general->get_join_today_members();
define('JOIN_TODAY_MEM',$join_today_member);

$online_member=$this->general->get_online_members();
define('ONLINE_MEMBERS',$online_member);

$mem_active_info=$this->general->get_total_members('active');		
define('ACTIVE_MEM_INFOS',$mem_active_info);
$mem_inactive_info=$this->general->get_total_members('inactive');		
define('INACTIVE_MEM_INFOS',$mem_inactive_info);
$mem_close_info=$this->general->get_total_members('close');		
define('CLOSE_MEM_INFOS',$mem_close_info);
$mem_suspended_info=$this->general->get_total_members('suspended');		
define('SUSPENDED_MEM_INFOS',$mem_suspended_info);
			
?>

<div id="wrapper_ctrl">
	<div id="header">
    		<div class="logo">
			<a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>">
            <?php if(SITE_LOGO): ?>
          <div>
            <img src="<?php echo site_url(SITE_LOGO_PATH).SITE_LOGO; ?>">
          </div>
<?php endif; ?>
			</a>
             </div>
             
              <div style=" float:right; padding-right:100px; color:#000000; font: bolder;">
				 <div style="padding:5px; font-weight:bolder; ">
					 <?php echo $this->general->date_formate($this->general->get_local_time('none'));?>
			 </div>
	 <div id="clock" style="padding:5px; font-family:Verdana, Geneva, sans-serif; font-size:18px; font-weight: bolder; color:#6f8520;"></div>
	 </div>
             
    		<div class="clear"></div>
			<div class="navigation">
                                <ul class="bevelmenu">
                                    <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH);?>" class="homeimg">Home</a></li>
                                    <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/change-password/index')?>" class="pass">Change Password</a></li>
                                    <li><a href="<?php echo site_url('')?>" class="sitehm" target="_blank">Site Home</a></li>
                                    <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/logout');?>"  class="signout">Log out</a></li>
                                </ul>
            </div>
      </div>
    
    <div id="container">
    <div id="lftcol">
    <ul id="nav">
                <li>
                        <span onclick="menuexpand('m1');">Member Management<img alt="" src="<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblt.gif" id="pm1" class="bltbutton" /></span>
                        <ul id="m1" >
                        <li><?php  echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/all', 'Total Members: '.TOTAL_MEMBER, 'title="Total Members"') ?></li>
                        <li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/active', 'Active Members: '.ACTIVE_MEM_INFOS, 'title="Active Members"');?></li>
                         <li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/inactive', 'Inactive Members: '.INACTIVE_MEM_INFOS, 'title="Inactive Members"');?></li>
                        <li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/close', 'Closed Members: '.CLOSE_MEM_INFOS, 'title="Closed Members"');?></li>
        								<li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/suspended', 'Suspended Members: '.SUSPENDED_MEM_INFOS, 'title="Suspended Members"');?></li>
                       <li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/today_join', 'Join Today Members :'.JOIN_TODAY_MEM, 'title="Join Today Members"');?></li>
                        <li><? echo anchor(ADMIN_DASHBOARD_PATH.'/members/index/online', 'Online Members: '.ONLINE_MEMBERS , 'title="Online Members"');?></li>                

								
                                 
                        </ul>
                </li>
                
                <li>
                        <span onclick="menuexpand('m3');">Admin Main Menu<img alt="" src="<?php print(site_url(ADMIN_IMG_DIR_FULL_PATH));?>/sidenavblt.gif" id="pm3" class="bltbutton"/></span>
                        <ul id="m3" >
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/transaction-history/index/')?>">Transaction History</a></li>
                           
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/product-categories/index/')?>">Categories</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction/index')?>">Auction Management</a></li>
                		   
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction-winner/index')?>">Winner Management</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/aucorder/index')?>">Auction Order Management</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/vouchers/index')?>">Vouchers Management</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/bidpackage/index')?>">Bid Package Management</a></li>
                                      
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/site-settings/index')?>">Site Settings</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/seo/index')?>">SEO Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/country/index')?>">Country Management</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/language-settings/index')?>">Language Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/cms/index')?>">Content Management System</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/auction-faq/index')?>">Auction FAQ's Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/banner/index')?>">Banner Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/email-settings/index')?>">E-mail Settings</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/notification/index')?>">Push Notification Settings</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/block-ip/index')?>">Blocked IP Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/change-password/index')?>">Change Password</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/help-category/index')?>">Help Category Management</a></li>
						   <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/help/index')?>">Help Management</a></li>
                           <li><a href="<?php echo site_url(ADMIN_DASHBOARD_PATH.'/payment/paypal')?>">Payment Getway Management</a></li>
                        </ul>
                </li>
                
        </ul>

    </div>
    
      
  
	  <div id="rtcol1">
	  <?php echo $template['body']; ?> 
    </div>
	
	  
<div class="clear"></div>

   </div>
   
   </div>
 
   
<div id="wrapper_ctrl1">&nbsp;</div>
<p><br />Page rendered in {elapsed_time} seconds</p>
</body>
</html>