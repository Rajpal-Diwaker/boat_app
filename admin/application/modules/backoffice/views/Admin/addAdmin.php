<style>
    .modal{max-height: 100%;background:none;box-shadow:none;}
</style>

      <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title"><?php echo $page_title; ?></h2>
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb page-head-nav">
              <li class="breadcrumb-item"><a href="<?php echo(ADMINURL.'Admin/dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="#"><?php echo $page_title; ?></a></li>
            </ol>
          </nav>
        </div>
        <div class="main-content container-fluid">
        <?php if(!empty($msg)){ ?>
            <div class="card-header">
                <h4><?php echo $msg; ?></h4>
            </div>  
        <?php } ?>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-border-color card-border-color-primary">
                <div class="card-body">
                  <form action="<?php echo(ADMINURL.'Admin/addAdminprocess'); ?>"  method="post" enctype="multipart/form-data" onSubmit="return validateinput();" >
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="fullname">Full name</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input id="fullname" type="text" class="form-control" value="" name="fullname" onkeyup="checkValidation('fullname')" autocomplete="off">
                        <span class="small" id="fullname-error" style="color:red;"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="email">Email</label>
                      <div class="col-12 col-sm-8 col-lg-6 email">
                        <input id="email" type="text" class="form-control" value="" onkeyup="checkValidation('email')" name="email"  autocomplete="off">
                        <span class="small" id="email-error" style="color:red;"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="password">Password</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input id="password" type="password" class="form-control" value="" name="password" onkeyup="checkValidation('password')" autocomplete="off">
                        <span class="small" id="password-error" style="color:red;"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="profile_pic">Profile Pic</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                            <input id="profile_pic" onchange="readname(this);" class="inputfile " name="profile_pic" type="file">
                            <label class="btn btn-sm btn-primary" for="profile_pic">
                            <i class="fa fa-upload" aria-hidden="true"></i> &nbsp;<span>Browse files</span></label>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-12 col-sm-3 col-form-label text-sm-right">&nbsp;</div>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <button class="btn btn-space btn-primary" type="submit">Add</button>
                      </div>
                    </div>
      
                  </form>
                </div>
              </div>
            </div>
          </div>
      
        </div>
      </div>            


<script>

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
    if ($( "#password" ).val().trim() === "") {
        isValid = false;
        $("#password-error").text('Please enter password').show();
    }
    /*
    if ($( "#role" ).val().trim() === "") {
        isValid = false;
        $( "#role-error" ).text( "Please select admin role." ).show();
    } */
   
    return isValid;         
}

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
              url: "<?php echo(ADMINURL.'Admin/check_email_exist'); ?>",
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
  
}

function readname(input) {
  var input = event.srcElement;
  var fileName = input.files[0].name;
  input.parentElement.getElementsByTagName("span")[0].innerHTML = fileName;
}

</script>



