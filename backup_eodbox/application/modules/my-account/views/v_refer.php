<div class="dashboard-widget mb-30">
                        <h4 class="title mt-0 mb-20">Welcome to the EODBOX Referral program.</h4>
                        <p>Have friends sign up using your unique link and earn rewards.</p>
                    </div>
                    <div class="dashboard-widget mb-30">
                        <h5 class="title mt-0 mb-20">Share your unique referral link</h5>
                        <form action="#0" class="referral-form mb-30">
                            <input type="text" id="myInput" value="<?php echo $this->general->lang_uri('/users/register?ref='.$this->session->userdata(SESSION.'user_id'));?>" readonly>
                            <button type="button" onclick="myFunction()" class="custom-button">Copy Link</button>
                        </form>
                        <div class="share-area">
                            <div class="left">
                                Share :
                            </div>
                            <ul class="social-icons">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $this->general->lang_uri('/users/register?ref='.$this->session->userdata(SESSION.'user_id'));?>" target="_blank" class="active"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo $this->general->lang_uri('/users/register?ref='.$this->session->userdata(SESSION.'user_id'));?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/?url=<?php echo $this->general->lang_uri('/users/register?ref='.$this->session->userdata(SESSION.'user_id'));?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $this->general->lang_uri('/users/register?ref='.$this->session->userdata(SESSION.'user_id'));?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="dashboard-widget mb-30">
                        <h5 class="title mt-0 mb-30">Promotions & Rewards</h5>
                        <p>* <?php echo lang('you_earn'); ?> <?php echo REFER_BONUS;?> <?php echo lang('bonus_points_for'); ?></p>
          				<p>* <?php echo lang('you_may_refer_as'); ?></p>          				
                    </div>
                    <?php /*?><div class="dashboard-widget mb-30">
                        <h5 class="title mt-0 mb-20">Leaderboard</h5>
                        <table class="referral-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Sbidu Bidder</th>
                                    <th>Referrals</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="rank">#01</td>
                                    <td data-label="Sbidu bidder">jo*@m**********</td>
                                    <td data-label="referrals">01</td>
                                </tr>
                                <tr>
                                    <td data-label="rank">#02</td>
                                    <td data-label="Sbidu bidder">ma****************@y********</td>
                                    <td data-label="referrals">01</td>
                                </tr>
                                <tr>
                                    <td data-label="rank">#03</td>
                                    <td data-label="Sbidu bidder">ho********@y********</td>
                                    <td data-label="referrals">00</td>
                                </tr>
                                <tr>
                                    <td data-label="rank">#04</td>
                                    <td data-label="Sbidu bidder">ma****************@y********</td>
                                    <td data-label="referrals">00</td>
                                </tr>
                                <tr>
                                    <td data-label="rank">#05</td>
                                    <td data-label="Sbidu bidder">ru*@f**********</td>
                                    <td data-label="referrals">00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div><?php */?>
                    <?php /*?><div class="dashboard-widget">
                        <h5 class="title mt-0 mb-30">Your Stats</h5>
                        <h3 class="stats">08<span>Referrals Made</span></h3>
                    </div><?php */?>

    

<script>
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

}
</script>