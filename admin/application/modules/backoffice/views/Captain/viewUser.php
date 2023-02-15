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
                               
                            ?>
                            </div>
                            <div class="info-table table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Profile Pic:</td>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 300px;" src="<?php echo $resultArr['profile_pic']; ?>" alt="user"></td>
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
                                            <td>Info:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $resultArr['userdetail'][0]['info'] ?></td>
                                        </tr>


                                        <tr>
                                            <td>Verified:</td>
                                            <?php 
                                            if($resultArr['userdetail'][0]['verified']==true){ 
                                                $verified = 'Yes';
                                            }else{
                                                $verified = 'No';
                                            } 
                                            ?>
                                            <td class="font-medium text-dark-medium"><?php echo $verified; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Id Proof Front:</td>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 300px;" src="<?php echo $resultArr['idproof1']; ?>" alt="user"></td>
                                        </tr>

                                        <tr>
                                            <td>Id Proof Back:</td>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 300px;" src="<?php echo $resultArr['idproof2']; ?>" alt="user"></td>
                                        </tr>
                                        <tr>
                                            <td>Boat Image:</td>
                                            <?php foreach ($resultArr['image'] as $value) {  ?>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 300px;" src="<?php echo $value; ?>" alt="user"></td>
                                            <?php } ?>
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