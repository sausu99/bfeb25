<table width="100%" cellpadding="1" class="contentbl" align="center">
<tr>
<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='edit_member') echo 'Edit Member'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/edit_member/active/'.$this->uri->segment(5),'Edit Member');?> ]
</td>
<!-- <td align="center" valign="middle" class="clrgrey">[ <?php //if($this->uri->segment(3)=='view_current_bids') echo 'Auction History'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/view_current_bids/active/'.$this->uri->segment(5),'Auction History');?> ]
</td>

<td align="center" valign="middle" class="clrgrey">[ <?php //if($this->uri->segment(3)=='view_bid_history') echo 'Bid History'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/view_bid_history/active/'.$this->uri->segment(5),'Bid History');?> ]
</td> -->
<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='view_closed_bids') echo 'Closed History'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/view_closed_bids/active/'.$this->uri->segment(5),'Closed History');?> ]
</td>


<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='view_auctions_won') echo 'View Auctions Won'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/view_auctions_won/active/'.$this->uri->segment(5),'View Auctions Won');?> ]
</td>
</tr>
<tr>

<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='transaction') echo 'Transaction History'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/transaction/active/'.$this->uri->segment(5),'Transaction History');?> ]
</td>



<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='add_balance') echo 'Add Balance'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/add_balance/active/'.$this->uri->segment(5),'Add Balance');?> ]
</td>

<td align="center" valign="middle" class="clrgrey">[ <?php if($this->uri->segment(3)=='ip_address') echo 'IP Address'; else echo  anchor(ADMIN_DASHBOARD_PATH.'/members/ip_address/active/'.$this->uri->segment(5),'IP Address');?> ]
</td>
</tr>
</table>