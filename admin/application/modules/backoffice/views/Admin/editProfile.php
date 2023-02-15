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
                            <h3>Update Profile</h3>
                        </div>
                    </div>
                    <form action="<?php echo(ADMINURL.'Admin/update_profiledata'); ?>" class="new-added-form"  method="post" enctype="multipart/form-data" onSubmit="return validateinput();" >
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Full Name *</label>
                                <input id="fullname" type="text" class="form-control" onkeyup="checkValidation('fullname')" value="<?php echo $userArr['fullname'] ?>" name="fullname"  autocomplete="off">
                                <span class="small" id="fullname-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Email *</label>
                                <input id="email" type="email" onkeyup="checkValidation('email')" class="form-control" value="<?php echo $userArr['email'] ?>" name="email"  autocomplete="off">
                                <span class="small" id="email-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Profile Pic *</label>
                                <input id="profile_pic" onchange="readname(this);" class="inputfile " name="profile_pic" type="file">
                            </div>
                            <div class="col-12 form-group mg-t-8">
                                <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Update</button>
                                <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow" id="openModal">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6-xxxl col-xl-7">
            <div class="card account-settings-box">
                <div class="card-body">
                    <div class="heading-layout1 mg-b-20">
                        <div class="item-title">
                            <h3>User Details</h3>
                        </div>
                    </div>
                    <?php
                    if(file_exists( FILEPATH. $userArr['profile_pic']) && $userArr['profile_pic'] != ""){
                        $profile_pic=FILEURL.$userArr['profile_pic'];      
                    }else{
                        $profile_pic =WEB.'/images/person.png';
                    } 
                    ?>
                    <div class="user-details-box">
                        <div class="item-img">
                            <img style="max-width: 250px;" src="<?php echo $profile_pic; ?>" alt="user">
                        </div>
                        <div class="item-content">
                            <div class="info-table table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td>Full Name:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $userArr['fullname'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $userArr['email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Status:</td>
                                            <td class="font-medium text-dark-medium"><?php echo $userArr['status'] ?></td>
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


 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Password</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form role="form" class="contact-form" action="" method="POST">
                <div class="modal-body padding-1">
                    <div class="msg">
                        <p id="error-msg" style="color:red;text-align:center;"></p>
                        <p id="success-msg" style="color:green;text-align:center;"></p>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-12 form-group">
                        <input type="password" id="oldpassword" onkeyup="checkValidation('oldpassword')" name="oldpassword" class="form-control" placeholder="Old password">
                        <span class="small" id="oldpassword-error" style="color: red;"></span>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-12 form-group">
                        <input type="password" id="newpassword" onkeyup="checkValidation('newpassword')" name="newpassword" class="form-control" placeholder="New password">
                        <span class="small" id="newpassword-error" style="color: red;"></span>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-12 form-group">
                        <input type="password" id="confirmpassword" onkeyup="checkValidation('confirmpassword')" name="confirmpassword" class="form-control" placeholder="Confirm New password">
                        <span class="small" id="confirmpassword-error" style="color: red;"></span>
                    </div>
                </div>
                <div class="modal-footer padding-1">
                    <button id="password-change" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" type="button"> Submit</button>
                </div>
            </form>
        </div>
      </div>
      
    </div>
  </div>

<script>

$('#openModal').on('click',function(){
    $( "#oldpassword-error" ).text("");
    $( "#newpassword-error" ).text("");
    $( "#confirmpassword-error" ).text("");
    $('#myModal').modal('show');
});

function validateinput(){
 //   alert('sdfdsf');
    var isValid = true;
    if ($( "#fullname" ).val().trim() === "") {
        isValid = false;
        $("#fullname-error").text('Please enter fullname').show();
    }
    var NAME = $( "#fullname" ).val();
    if(!/^[a-zA-Z\s]+$/.test(NAME)){
        isValid = false;
        $( "#fullname-error" ).text( "Please enter fullname." ).show();
    }   
    
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if ($( "#email" ).val().trim() === "") {
        isValid = false;
        $( "#email-error" ).text( "Please enter email." ).show();
    }
    if (!emailReg.test($( "#email" ).val())) {
        isValid = false;
        $( "#email-error" ).text( "Please enter a valid email." ).show();
    }
    if ($('.email span').text() !== "") {
        isValid = false;
    }
   
    return isValid;         
}


$("#password-change").on('click', function(event) {
        var formStatus = true;
        if ($( "#oldpassword" ).val().trim() == "") {
            formStatus = false;
            $( "#oldpassword-error" ).text( "Please enter the old password." ).show();
        }
        if ($( "#newpassword" ).val().trim() == "") {
            formStatus = false;
            $( "#newpassword-error" ).text( "Please enter new password." ).show();
        }
        if ($( "#confirmpassword" ).val().trim() == "") {
            formStatus = false;
            $( "#confirmpassword-error" ).text( "Please enter your password." ).show();
            return false;
        }
        if ($( "#newpassword" ).val().trim() != $( "#confirmpassword" ).val().trim()) {
            formStatus = false;
            $( "#confirmpassword-error" ).text( "New password does not match." ).show();
        }
        if (!formStatus) {
            event.preventDefault();
        } else {  
            var data = {};
            data.oldpwd = $( "#oldpassword" ).val();
            data.newpwd = $( "#newpassword" ).val();
            data.confirmpwd = $( "#confirmpassword" ).val();
        //  console.log(data);
            $.ajax({
                type: "POST",
                url: "<?php echo(ADMINURL.'Admin/changepassword'); ?>",
                data: data,
                cache: false,
                dataType: 'text',
                success: function (res) {  
                    if (res == 1) {
                        $("#success-msg").text('Updated Successfully.').show();
                        setInterval(function(){ 
                            location.reload();
                        }, 1000);
                    }
                    else{
                        $("#error-msg").text('Incorrect old password.').show();
                    }
                    $('input[type=password]').val('');
                }
            });
        }
    });

function checkValidation(id){
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if(id == 'fullname'){
    if ($( "#fullname" ).val() === "") {
        $( "#fullname-error" ).text( "Please enter full name." ).show();
    }else{
        $( "#fullname-error" ).text("");
    }
  }
  if(id == 'email'){
    if ($( "#email" ).val() === "") {
        $( "#email-error" ).text( "Please enter email." ).show();
    }else{
        $( "#email-error" ).text("");
    }
  }
  if(id == 'email'){
    if ($( "#email" ).val() === "") {
        $( "#email-error" ).text( "Please enter email." ).show();
    }else if (!emailReg.test($( "#email" ).val())) {
        $( "#email-error" ).text( "Please enter valid email." ).show();
    }else{
        var email = $("#email").val();
          $.ajax({
              type:"post",
              url: "<?php echo(ADMINURL.'Admin/check_email_exists'); ?>",
              data:{ email:email},
              success:function(response)
              {
                  if (response == 1) 
                  {
                    $("#email-error").text('').show();
                  }
                  else 
                  {
                    $("#email-error").text('Email already in Use').show();
                  }  
              }
          }); 
      }
  }
  if(id == 'password'){
    if ($( "#password" ).val() === "") {
        $( "#password-error" ).text( "Please enter password." ).show();
    }else{
        $( "#password-error" ).text("");
    }
  }
  if(id == 'oldpassword'){
    if ($( "#oldpassword" ).val() === "") {
        $( "#oldpassword-error" ).text( "Please enter the old password." ).show();
    }else{
        $( "#oldpassword-error" ).text("");
    }
  }
  if(id == 'newpassword'){
    if ($( "#newpassword" ).val() === "") {
        $( "#newpassword-error" ).text( "Please enter new password." ).show();
    }else{
        $( "#newpassword-error" ).text("");
    }
  }
  if(id == 'confirmpassword'){
    if ($( "#confirmpassword" ).val() === "") {
        $( "#confirmpassword-error" ).text( "Please enter your password." ).show();
    }else if ($( "#newpassword" ).val().trim() != $( "#confirmpassword" ).val().trim()) {
        $( "#confirmpassword-error" ).text( "New password does not match." ).show();
    }else{
        $( "#confirmpassword-error" ).text("");
    }
  }
  
}
</script>
