<div style="background:#FFFFFF; border:1px dashed #333333; padding:10px 5px; margin-bottom:20px;">
    <ul id="vList">
        <li>
            [ <?php
            if ($this->uri->segment(3) != 'paypal') {
                echo anchor(ADMIN_DASHBOARD_PATH . '/payment/paypal', 'PayPal Gateway', 'title="PayPal"');
            } else {
                echo "PayPal Gateway";
            }
            ?> ]
        </li>

       <!--  <li>
           // [ <?php
           // if ($this->uri->segment(3) != 'instamojo') {
                //echo anchor(ADMIN_DASHBOARD_PATH . '/payment/instamojo', 'Instamojo Gateway', 'title="Instamojo"');
           // } else {
             //   echo "Instamojo Gateway";
           // }
            ?> ]
        </li> -->
        <li>
            [ <?php
            if ($this->uri->segment(3) != 'ccavenue') {
                echo anchor(ADMIN_DASHBOARD_PATH . '/payment/ccavenue', 'ccavenue Gateway', 'title="ccavenue"');
            } else {
                echo "ccavenue Gateway";
            }
            ?> ]
        </li>
         <li>
            [ <?php
            if ($this->uri->segment(3) != 'paytm') {
                echo anchor(ADMIN_DASHBOARD_PATH . '/payment/paytm', 'payTm Gateway', 'title="paytm"');
            } else {
                echo "PayTm Gateway";
            }
            ?> ]
        </li>


    </ul>
    <div style="clear:both"></div>
</div>