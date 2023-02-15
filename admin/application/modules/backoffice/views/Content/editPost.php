<script src="<?php echo  $this->config->item('base_url'); ?>public/admin/ckeditor/ckeditor.js"></script>
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
        <div class="col-2-xxxl col-xl-2">
            &nbsp;
        </div>
        <div class="col-8-xxxl col-xl-8">
            <div class="card account-settings-box height-auto">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3><?php echo $page_title; ?></h3>
                        </div>
                    </div>
                    <form action="<?php echo(ADMINURL.'Content/editPostprocess'); ?>" class="new-added-form"  method="post" enctype="multipart/form-data" onSubmit="return validateinput();" >
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Title *</label>
                                <input id="blog_title" type="text" class="form-control" onkeyup="checkValidation('blog_title')" value="<?php echo $postArr['post_name']; ?>" name="blog_title"  autocomplete="off">
                                <span class="small" id="blog_title-error" style="color:red;"></span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                <label>Content</label>
                                <textarea class="form-control" name="blog_content" id="editor1" autocomplete="off"><?php echo $postArr['post_description']; ?></textarea>
                                <span class="small" id="editor1-error" style="color:red;"></span>
                            </div>
                
                            <input type="hidden" name="blog_id" value="<?php echo $postArr['post_id']; ?>">
                            <div class="col-12 form-group mg-t-8">
                                <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-2-xxxl col-xl-2">
            &nbsp;
        </div>
    </div>

<script>
CKEDITOR.replace('editor1', {
    on: {
          change: function() {
              $( "#editor1-error" ).text( "" );
          }
    }
  });
CKEDITOR.add 

function validateinput(){
 //   alert('sdfdsf');
    var isValid = true;
    if ($( "#blog_title" ).val().trim() === "") {
        isValid = false;
        $("#blog_title-error").text('Please enter blog title').show();
    }
    /*
    var NAME = $( "#service_name" ).val();
    if(!/^[a-zA-Z\s]+$/.test(NAME)){
        isValid = false;
        $( "#service_name-error" ).text( "Please enter name." ).show();
    } */
    if (CKEDITOR.instances.editor1.getData() === "") {
        isValid = false;
        $("#editor1-error").text('Please enter description.').show();
    } 
    return isValid;         
}

function checkValidation(id){
  if(id == 'blog_title'){
    if ($( "#blog_title" ).val() === "") {
        $( "#blog_title-error" ).text( "Please enter blog title." ).show();
    }else{
        $( "#blog_title-error" ).text("");
    }
  }
  
}
</script>
