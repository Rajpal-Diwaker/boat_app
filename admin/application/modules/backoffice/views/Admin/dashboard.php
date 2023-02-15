
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Admin Dashboard</h3>
                    <ul>
                        <li>
                            <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>">Home</a>
                        </li>
                        <li>Admin</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Dashboard summery Start Here -->
                <div class="row gutters-20">
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-12">
                                    <div class="item-content">
                                        <div class="item-title">No of User Joined</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="item-icon bg-light-green ">
                                        <div class="item-number"><span class="counter" data-num="<?php echo $resultArr['user'] ?>"><?php echo $resultArr['user'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-12">
                                    <div class="item-content">
                                        <div class="item-title">No of Captain Joined</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="item-icon bg-light-blue">
                                        <div class="item-number"><span class="counter" data-num="<?php echo $resultArr['captain'] ?>"><?php echo $resultArr['captain'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-12">
                                    <div class="item-content">
                                        <div class="item-title">No of Boat Booked</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="item-icon bg-light-yellow">
                                        <div class="item-number"><span class="counter" data-num="<?php echo $resultArr['respo'] ?>"><?php echo $resultArr['respo'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="dashboard-summery-one mg-b-20">
                            <div class="row align-items-center">
                                <div class="col-6 col-sm-12">
                                    <div class="item-content">
                                        <div class="item-title">Revenue Generated</div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12">
                                    <div class="item-icon bg-light-red">
                                        <div class="item-number"><span class="counter" data-num="<?php echo $resultArr['advisor'] ?>"><?php echo $resultArr['advisor'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dashboard summery End Here -->
               
               
