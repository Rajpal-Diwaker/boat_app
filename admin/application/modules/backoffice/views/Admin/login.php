
    <!-- Login Page Start Here -->
    <div class="login-page-wrap">
        <div class="login-page-content">
            <div class="login-box">
                <div class="item-logo">
                    <img src="<?php echo(ADMIN.'/img/logo.png'); ?>" alt="logo">
                </div>
                <form method="POST" action="<?php echo(ADMINURL.'Admin/login'); ?>" class="login-form">
                    <div class="form-group">
                        <label>Email</label>
                        <input id="email" type="email" placeholder="Email" autocomplete="off" name="email" required data-msg="Please enter your email" class="form-control">
                        <i class="far fa-envelope"></i>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" id="password" type="password" name="password" placeholder="Password" required data-msg="Please enter your password">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <div class="form-check">
                           
                        </div>
                        <a data-toggle="modal" href="#myModal" class="forgot-btn">Forgot Password?</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="login-btn">Login</button>
                        <?php if(!empty($msg)){   ?>
                          <div id="login-error" class="is-invalid invalid-feedback" style="display: block;"><?php echo $msg; ?></div>
                        <?php } ?>
                    </div>
                </form>
            </div>
          
        </div>
    </div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
         <h4 class="modal-title">Forgot Password ?</h4>
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> 
        </div>
        <form id="forgotpassword-form" role="form" action="" method="POST">
          <div class="modal-body">
            <p>Enter your e-mail address below to reset your password.</p>
            <div class="form-group">
            <input type="text" id="forgotemail" name="forgotemail" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix" onkeyup="validatePassword('forgotemail')">
            <span class="small" id="forgotemail-error" style="color: red;"></span>
            </div>
          </div>
          <div class="modal-footer">
          <p class="red" id="error-msg" style="color: red;text-align:center;"></p>
          <p class="green" id="success-msg" style="color: green;text-align:center;"></p>
            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
            <button id="forgot-password" class="btn btn-primary" type="button">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>  


    <!-- Login Page End Here -->
    <!-- jquery-->
    <script src="<?php echo(ADMIN.'/js/jquery-3.3.1.min.js'); ?>"></script>
    <!-- Plugins js -->
    <script src="<?php echo(ADMIN.'/js/plugins.js'); ?>"></script>
    <!-- Popper js -->
    <script src="<?php echo(ADMIN.'/js/popper.min.js'); ?>"></script>
    <!-- Bootstrap js -->
    <script src="<?php echo(ADMIN.'/js/bootstrap.min.js'); ?>"></script>
    <!-- Scroll Up Js -->
    <script src="<?php echo(ADMIN.'/js/jquery.scrollUp.min.js'); ?>"></script>
    <!-- Custom Js -->
    <script src="<?php echo(ADMIN.'/js/main.js'); ?>"></script>

    <script type="text/javascript">

      $("#forgot-password").on('click', function(event) {
        var formStatus = true;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if ($( "#forgotemail" ).val().trim() === "") {
          formStatus = false;
          $( "#forgotemail-error" ).text( "Please enter email." ).show();
        }
        if (!emailReg.test($( "#forgotemail" ).val())) {
          formStatus = false;
          $( "#forgotemail-error" ).text( "Please enter a valid email." ).show();
        }
        if (!formStatus) {
          event.preventDefault();
        } else {  
          var data = {};
          data.email = $( "#forgotemail" ).val();
          $('#forgotpassword-form')[0].reset();
          $.ajax({
            type: "POST",
            url: "<?php echo(ADMINURL.'Admin/forgotpassword'); ?>",
            data: data,
            cache: false,
            dataType: 'text',
            success: function (res) {  
            //  console.log(res);
              if (res == 1) {
                $("#success-msg").text('Please check your email.').show();
              }
              else{
                $("#error-msg").text('Email id does not exist.').show();
              }
            }
          });
        }
      });

      function validatePassword(id){
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
          if(id === 'forgotemail'){
            if ($( "#forgotemail" ).val() === "") {
                $( "#forgotemail-error" ).text( "Please enter email." ).show();
            }else if (!emailReg.test($( "#forgotemail" ).val())) {
                $( "#forgotemail-error" ).text( "Please enter valid email." ).show();
            }else{
                $( "#forgotemail-error" ).text("");
            }
          }
      }
      
    </script>

</body>

</html>