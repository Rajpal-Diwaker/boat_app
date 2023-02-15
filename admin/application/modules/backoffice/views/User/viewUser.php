<div class="dashboard-content-one">
    <!-- Breadcubs Area Start Here -->
    <div class="breadcrumbs-area">
        <div class="row">
        <div class="col-xl-10">
            <h3><?php echo $page_title; ?></h3>
            <ul>
                <li>
                    <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>">Home</a>
                </li>
                <li><?php echo $page_title; ?></li>
            </ul>
        </div>
        <div class="col-xl-2">
           
        </div>
        </div>
    </div>
    <!-- Breadcubs Area End Here -->
    <!-- Account Settings Area Start Here -->

    <div class="row">

        <div class="col-12-xxxl col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="heading-layout1 mg-b-20">
                        <div class="item-title">
                            <h3>Details</h3>
                        </div>
                        
                    </div>
                    <div class="user-details-box">
                        
                        <div class="item-content">
                            <div class="item-img">
                            <?php    
                                if(file_exists( FILEPATH.'userprofile/'. $resultArr['userdetail'][0]['profile_pic']) && $resultArr['userdetail'][0]['profile_pic'] != ""){
                                    $profilepic=FILEURL.'userprofile/'.$resultArr['userdetail'][0]['profile_pic'];           
                                }elseif(!empty($resultArr['userdetail'][0]['profile_pic'])){
                                    $profilepic =$resultArr['userdetail'][0]['profile_pic'];
                                }else{
                                    $profilepic =ADMIN.'/img/noimage.png';
                                } 
                            ?>
                            </div>
                            <div class="info-table table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Profile Pic:</td>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 300px;" src="<?php echo $profilepic; ?>" alt="user"></td>
                                        </tr>
                                        <tr>
                                            <td>Fullname:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['full_name'] ?></td>
                                        </tr>
                                      
                                        <tr>
                                            <td>Email:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['country_code'].' '.$resultArr['userdetail'][0]['mobile']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Account Type:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['act_type'] ?></td>
                                        </tr>


                                        <tr>
                                            <td>Status:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['status'] ?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>