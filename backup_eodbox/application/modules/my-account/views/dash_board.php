<div class="dashboard-widget mb-40">
                        <div class="dashboard-title mb-30">
                            <h5 class="title">My Activity</h5>
                        </div>
                        <div class="row justify-content-center mb-30-none">
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/01.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo $total_live_auc;?></span></h2>
                                        <h6 class="info">Active Bids</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/02.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo $total_won_auc;?></span></h2>
                                        <h6 class="info">Items Won</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="dashboard-item">
                                    <div class="thumb">
                                        <img src="<?php echo site_url(MAIN_IMG_DIR_FULL_PATH);?>dashboard/03.png" alt="dashboard">
                                    </div>
                                    <div class="content">
                                        <h2 class="title"><span class="counter"><?php echo ($user_watchlist==false)?'0':count($user_watchlist);?></span></h2>
                                        <h6 class="info">Favorites</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php /*?><div class="dashboard-widget">
                        <h5 class="title mb-10">Purchasing</h5>
                        <div class="dashboard-purchasing-tabs">
                            <ul class="nav-tabs nav">
                                <li>
                                    <a href="#current" class="active" data-toggle="tab">Current</a>
                                </li>
                                <li>
                                    <a href="#pending" data-toggle="tab">Pending</a>
                                </li>
                                <li>
                                    <a href="#history" data-toggle="tab">History</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active fade" id="current">
                                    <table class="purchasing-table">
                                        <thead>
                                            <th>Item</th>
                                            <th>Bid Price</th>
                                            <th>Highest Bid</th>
                                            <th>Lowest Bid</th>
                                            <th>Expires</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane show fade" id="pending">
                                    <table class="purchasing-table">
                                        <thead>
                                            <th>Item</th>
                                            <th>Bid Price</th>
                                            <th>Highest Bid</th>
                                            <th>Lowest Bid</th>
                                            <th>Expires</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane show fade" id="history">
                                    <table class="purchasing-table">
                                        <thead>
                                            <th>Item</th>
                                            <th>Bid Price</th>
                                            <th>Highest Bid</th>
                                            <th>Lowest Bid</th>
                                            <th>Expires</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                            <tr>
                                                <td data-purchase="item">2018 Hyundai Sonata</td>
                                                <td data-purchase="bid price">$1,775.00</td>
                                                <td data-purchase="highest bid">$1,775.00</td>
                                                <td data-purchase="lowest bid">$1,400.00</td>
                                                <td data-purchase="expires">7/2/2021</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><?php */?>