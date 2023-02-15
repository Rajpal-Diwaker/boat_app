<?php //echo "<pre>"; print_r($resultArr); die; ?>
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
    <div class="row">
        <div class="col-12-xxxl col-xl-12">
            <div class="card account-settings-box height-auto">
                <div class="card-body">
                    <div class="heading-layout1">
                        <div class="item-title">
                            <h3>Listing</h3>
                        </div>
                        <div class="dropdown">
                            <a class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark mybtn" href="<?php echo(ADMINURL.'Content/addPost/'); ?>">Add New Post</a>
                        </div>
                    </div>
                    <?php if(!empty($resultArr)){ ?>
                        <div id="table1_wrapper" class="dataTables_wrapper">
                              <div class="row be-datatable-header">
                                  <div class="col-sm-6">
                                      <div class="dataTables_length" id="myTable_length table1_length">
                                          <label>Show 
                                          <select name="table1_length" aria-controls="myTable" class="" id="limitRows" onchange="sendRequest();">
                                            <option value="10">10</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                          </select> entries</label>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div id="myTable_filter" class="dataTables_filter form-group"><input type="text" nme="searchFor" class="form-control" placeholder="Search" id="searchKey" onchange="sendRequest();"></div>
                                  </div>
                              </div>
                              <div class="row be-datatable-body">
                                <div class="table-responsive">
                                   
                                    <table class="table table-striped table-hover table-fw-widget dataTable" id="table1" role="grid" aria-describedby="table1_info">
                                        <thead>
                                          <tr>
                                            <th><span>S/N</span></th>
                                            <th data-action="sort" data-title="blog_title" data-direction="ASC"><span>Post Title</span> <i class="glyphicon glyphicon-triangle-bottom"></i></th>
                                            <th>Type</th>
                                            <th style="text-align: center;">Status</th>
                                            <th>Edit</th>
                                            <th>View</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                        foreach ($resultArr as $key => $value) {
                                          ?>
                                          <tr>
                                              <td><?php echo ($page+$key+1); ?></td>
                                              <td><?php echo $value->post_name; ?></td>
                                              <td><?php echo $value->post_category; ?></td>
                                              <td  style="text-align:center;" class="allbutton"><?php if ($value->post_status==='inactive') { ?>
                                                <a class="btn-fill-md text-red border-red" onclick="changeStatus(<?php echo $value->post_id.','.'1';?>)">Inactive</a>
                                                <?php } else { ?>
                                                <a class="btn-fill-md text-dark-pastel-green border-dark-pastel-green" onclick="changeStatus(<?php echo $value->post_id.','.'0';?>)">Active</a>
                                                <?php } ?>
                                              </td>
                                              <td class="allbutton">
                                                <a class="" href="<?php echo(ADMINURL.'Content/editPost/').urlencode(base64_encode($value->post_id)); ?>"><i class="fas fa-cogs text-dark-pastel-green"></i> Edit</a>
                                              </td>
                                              <td class="allbutton">
                                                <a class="btn-fill-md text-dodger-blue border-dodger-blue" href="<?php echo(ADMINURL.'Content/viewPost/').urlencode(base64_encode($value->post_id)); ?>"> View</a>
                                              </td>
                                          </tr>
                                      <?php }
                                      ?>
                                      </tbody>
                                      
                                    </table>
                                    <?php echo $pagination;  ?>


                                </div>
                                </div>
                          </div>

                  <?php  }else{ echo  'Nothing to show.'; } ?>
                    
                </div>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.2.0/bootbox.min.js" type="text/javascript"></script>  
<script type="text/javascript">
    var sendRequest = function(){
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('backoffice/Content/listing')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
    }


    var getNamedParameter = function (key) {
        if (key == undefined) return false;

        var url = window.location.href;
        //console.log(url);
        var path_arr = url.split('?');
        if (path_arr.length === 1) {
            return null;
        }
        path_arr = path_arr[1].split('&');
        path_arr = remove_value(path_arr, "");
        var value = undefined;
        for (var i = 0; i < path_arr.length; i++) {
            var keyValue = path_arr[i].split('=');
            if (keyValue[0] == key) {
                value = keyValue[1];
                break;
            }
        }

        return value;
    };


    var remove_value = function (value, remove) {
        if (value.indexOf(remove) > -1) {
            value.splice(value.indexOf(remove), 1);
            remove_value(value, remove);
        }
        return value;
    };


    var curOrderField, curOrderDirection;
    $('[data-action="sort"]').on('click', function(e){
      curOrderField = $(this).data('title');
      curOrderDirection = $(this).data('direction');
      sendRequest();
    });


    $('#searchKey').val(decodeURIComponent(getNamedParameter('query')||""));
    $('#limitRows option[value="'+getNamedParameter('limitRows')+'"]').attr('selected', true);

    var curOrderField = getNamedParameter('orderField')||"";
    var curOrderDirection = getNamedParameter('orderDirection')||"";
    var currentSort = $('[data-action="sort"][data-title="'+getNamedParameter('orderField')+'"]');
    if(curOrderDirection=="ASC"){
      currentSort.attr('data-direction', "DESC").find('i.glyphicon').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top'); 
    }else{
      currentSort.attr('data-direction', "ASC").find('i.glyphicon').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');  
    }

function changeStatus(post_id,type){

    var post_id=post_id;
    var type=type;
    if(type == 1){
      var showtext = 'Do you really want to Activate this post?';
    }else{
      var showtext = 'Do you really want to De-activate this post?';
    }
   var box =  bootbox.confirm(showtext, function(result) {
       if(result){
         $(".loaderCntr").show();
          event.preventDefault();
         $.ajax({
           url: '<?php echo(ADMINURL.'Content/changeStatus'); ?>',
           type: 'POST',
           data: {post_id:post_id,type:type},
           success: function(response){
                $(".loaderCntr").hide(); 
                if(response == 1)
                {
                  var box2 = bootbox.confirm({
                    message: "Successfully Done",
                    buttons: {
                      confirm: {
                        label: 'OK',
                        className: 'btn btn-primary'
                      }
                    },
                      callback: function () {
                        window.location.href = location.href;
                    }
                  });
                   box2.css({
                    'top': '50%',
                    'margin-top': function () {
                      return -(box2.height() / 2);
                    }
                  });
                  $('#submit').attr('disabled',true);
                
                }else if(response==0){
                  bootbox.alert('<b>Something went wrong please try again!.</b>');
                  return false; 
                }         
           }
         });
       }
    });
box.css({
  'top': '50%',
  'margin-top': function () {
    return -(box.height() / 2);
  }
});
}

</script>