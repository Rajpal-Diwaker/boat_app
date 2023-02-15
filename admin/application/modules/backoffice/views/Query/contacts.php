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
                                      <div id="myTable_filter" class="dataTables_filter form-group"><input type="text" nme="searchFor" class="form-control" placeholder="Search By Username" id="searchKey" onchange="sendRequest();"></div>
                                  </div>
                              </div>
                              <div class="row be-datatable-body">
                                <div class="table-responsive">
                                   
                                    <table class="table table-striped table-hover table-fw-widget dataTable" id="table1" role="grid" aria-describedby="table1_info">
                                        <thead>
                                          <tr>
                                            <th><span>S/N</span></th>
                                            <th data-action="sort" data-title="u_username" data-direction="ASC"><span>Username</span> <i class="glyphicon glyphicon-triangle-bottom"></i></th>

                                            <th><span>Profilepic</span> </th>

                                            <th><span>Description</span> </th>

                                            <th  style="min-width: 100px;">Created On</th>

                                            <th>View</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                              foreach ($resultArr as $key => $value) {
                                                ?>
                                                <tr>
                                                    <td><?php echo ($page+$key+1); ?></td>
                                                    <td><?php echo $value['username']; ?></td>
                                                    <td><img style="max-width: 50px;" src="<?php echo $value['profilepic']; ?>"></td>
                                                    <td><?php echo $value['description']; ?></td>
                                                    <td><?php echo $value['created_date']; ?></td>
                                                    <td class="allbutton">
                                                      <a class="btn-fill-md text-dodger-blue border-dodger-blue" href="<?php echo(ADMINURL.'Query/viewDetail/').urlencode(base64_encode($value['contact_id'])); ?>"> View</a>
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
      
<script type="text/javascript">
  var sendRequest = function(){
      var searchKey = $('#searchKey').val();
      var limitRows = $('#limitRows').val();
      window.location.href = '<?=base_url('backoffice/Query/contacts')?>?query='+searchKey+'&limitRows='+limitRows+'&orderField='+curOrderField+'&orderDirection='+curOrderDirection;
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

</script>         
