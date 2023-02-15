<div class="dashboard-content-one">
    <!-- Breadcubs Area Start Here -->
    <div class="breadcrumbs-area">
        <h3><?php echo $page_title; ?></h3>
        <ul>
            <li>
                <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>">Home</a>
            </li>
            <li><?php echo $page_title; ?></li>
        </ul>
    </div>
    <!-- Breadcubs Area End Here -->
    <!-- Account Settings Area Start Here -->
    <div class="row">
        <div class="col-1-xxxl col-xl-1">
            &nbsp;
        </div>
        <div class="col-10-xxxl col-xl-10">
            <div class="card">
                <div class="card-body">
                    <div class="heading-layout1 mg-b-20">
                        <div class="item-title">
                            <h3>Post Detail</h3>
                        </div>
                    </div>
                    <div class="user-details-box">
                        
                        <div class="item-content">

                            <div class="info-table table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Title:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['post_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Content:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['post_description'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Status:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['post_status'] ?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1-xxxl col-xl-1">
            &nbsp;
        </div>
    </div>