<div class="dashboard-content-one">
    <!-- Breadcubs Area Start Here -->
    <div class="breadcrumbs-area">
        <h3>Account Setting</h3>
        <ul>
            <li>
                <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>">Home</a>
            </li>
            <li>Setting</li>
        </ul>
    </div>
    <!-- Breadcubs Area End Here -->
    <!-- Account Settings Area Start Here -->
    <div class="row">
        <div class="col-6-xxxl col-xl-5">
            <div class="card account-settings-box height-auto">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Update Settings</h3>
                        </div>
                    </div>
                    <form action="<?php echo(ADMINURL.'Admin/update_settings'); ?>" class="new-added-form"  method="post" enctype="multipart/form-data" onSubmit="return validateinput();" >
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Hourly Rate India *</label>
                                <input id="hourly_rate_in" type="text" class="form-control" onkeyup="checkValidation('hourly_rate_in')" value="<?php echo $resultArr[0]['hourly_rate_in'] ?>" name="hourly_rate_in"  autocomplete="off">
                                <span class="small" id="hourly_rate_in-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Hourly Rate US & Canada *</label>
                                <input id="hourly_rate_us" type="text" class="form-control" onkeyup="checkValidation('hourly_rate_us')" value="<?php echo $resultArr[0]['hourly_rate_us'] ?>" name="hourly_rate_us"  autocomplete="off">
                                <span class="small" id="hourly_rate_us-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Hourly Rate UAE *</label>
                                <input id="hourly_rate_uae" type="text" class="form-control" onkeyup="checkValidation('hourly_rate_uae')" value="<?php echo $resultArr[0]['hourly_rate_uae'] ?>" name="hourly_rate_uae"  autocomplete="off">
                                <span class="small" id="hourly_rate_uae-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Hourly Rate Europe *</label>
                                <input id="hourly_rate_eur" type="text" class="form-control" onkeyup="checkValidation('hourly_rate_eur')" value="<?php echo $resultArr[0]['hourly_rate_eur'] ?>" name="hourly_rate_eur"  autocomplete="off">
                                <span class="small" id="hourly_rate_eur-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Payment Gateway Hour *</label>
                                <input id="payment_gateway" type="text" class="form-control" onkeyup="checkValidation('payment_gateway')" value="<?php echo $resultArr[0]['payment_gateway'] ?>" name="payment_gateway"  autocomplete="off">
                                <span class="small" id="payment_gateway-error" style="color:red;"></span>
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>


function validateinput(){
 //   alert('sdfdsf');
    var isValid = true;
    if ($( "#hourly_rate_in" ).val().trim() === "") {
        isValid = false;
        $("#hourly_rate_in-error").text('Please enter value').show();
    }
    if ($( "#hourly_rate_us" ).val().trim() === "") {
        isValid = false;
        $("#hourly_rate_us-error").text('Please enter value').show();
    }
    if ($( "#hourly_rate_uae" ).val().trim() === "") {
        isValid = false;
        $("#hourly_rate_uae-error").text('Please enter value').show();
    }
    if ($( "#hourly_rate_eur" ).val().trim() === "") {
        isValid = false;
        $("#hourly_rate_eur-error").text('Please enter value').show();
    }
   	if ($( "#payment_gateway" ).val().trim() === "") {
        isValid = false;
        $("#payment_gateway-error").text('Please enter hour').show();
    }
    var regex = /^[0-9]*[1-9][0-9]*$/;
    if(!regex.test($( "#payment_gateway" ).val())){
        isValid = false;
        $( "#payment_gateway-error" ).text( "Please enter integer value." ).show();
    } 
    return isValid;         
}

function checkValidation(id){
  if(id == 'hourly_rate_in'){
    if ($( "#hourly_rate_in" ).val() === "") {
        $( "#hourly_rate_in-error" ).text( "Please enter value." ).show();
    }else{
        $( "#hourly_rate_in-error" ).text("");
    }
  }
  if(id == 'hourly_rate_us'){
    if ($( "#hourly_rate_us" ).val() === "") {
        $( "#hourly_rate_us-error" ).text( "Please enter value." ).show();
    }else{
        $( "#hourly_rate_us-error" ).text("");
    }
  }
  if(id == 'hourly_rate_uae'){
    if ($( "#hourly_rate_uae" ).val() === "") {
        $( "#hourly_rate_uae-error" ).text( "Please enter value." ).show();
    }else{
        $( "#hourly_rate_uae-error" ).text("");
    }
  }
  if(id == 'hourly_rate_eur'){
    if ($( "#hourly_rate_eur" ).val() === "") {
        $( "#hourly_rate_eur-error" ).text( "Please enter value." ).show();
    }else{
        $( "#hourly_rate_eur-error" ).text("");
    }
  }
  if(id == 'payment_gateway'){
    if ($( "#payment_gateway" ).val() === "") {
        $( "#payment_gateway-error" ).text( "Please enter hour." ).show();
    }else{
        $( "#payment_gateway-error" ).text("");
    }
  }
  
}
</script>
