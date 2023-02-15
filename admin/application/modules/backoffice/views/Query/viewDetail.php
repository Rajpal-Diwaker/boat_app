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
                            <h3>Contact Detail</h3>
                        </div>
                    </div>
                    <div class="user-details-box">
                        
                        <div class="item-content">

                            <div class="info-table table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Username:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Profilepic:</td>
                                            <td class="font-medium text-dark-medium"><img style="max-width: 200px;" src="<?php echo $postArr['profilepic'] ?>" alt="Admin"></td>
                                        </tr>
                                        <tr>
                                            <td>Description:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['description'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Contacted on:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $postArr['created_date'] ?></td>
                                        </tr>

                                        <tr>
                                            <td>Screenshot:</td>
                                            <td class="font-medium text-dark-medium">
                                            <?php foreach($postArr['screenshot'] as $screenshot){ ?>
                                            <img style="max-width: 600px;" src="<?php echo $screenshot; ?>" >&nbsp;&nbsp;&nbsp;
                                            <?php } ?>
                                            </td>
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