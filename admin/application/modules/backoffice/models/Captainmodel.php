<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captainmodel extends MX_Controller {

	function listing($limit, $start, $search = "", $orderField, $orderDirection){
	    $orwhere = "(full_name LIKE '%".$search."%' OR email LIKE '".$search."' OR mobile LIKE '".$search."')";
	    $this->db->select('*');
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->limit($limit, $start);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by('captain_id','DESC');
	    $query=$this->db->get('b_captain');
	    $profileArr = $query->result_array();
	    return $profileArr;
	}

	function count_listing($limit, $start, $search = "", $orderField, $orderDirection){
	    $orwhere = "(full_name LIKE '%".$search."%' OR email LIKE '".$search."' OR mobile LIKE '".$search."')";
	    $this->db;
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $query=$this->db->get('b_captain');
	    return $query->num_rows();
	}

	function viewUser($pid){
	  $query=$this->db->select('u.*')
	  		->from('b_captain as u')
	  		//->join('q_user_info as ui','u.u_id=ui.u_id')
          	->where('u.captain_id',$pid)
          	->get('');
	  $result['userdetail'] = $query->result_array();
	  if(file_exists( FILEPATH.'captain/'. $result['userdetail'][0]['profile_pic']) && $result['userdetail'][0]['profile_pic'] != ""){
		$result['profile_pic']=FILEURL.'captain/'.$result['userdetail'][0]['profile_pic'];           
	  }else{
		$result['profile_pic'] =ADMIN.'/img/noimage.png';
	  } 
	  if(file_exists( FILEPATH.'captainid/'. $result['userdetail'][0]['idproof1']) && $result['userdetail'][0]['idproof1'] != ""){
		$result['idproof1']=FILEURL.'captainid/'.$result['userdetail'][0]['idproof1'];           
	  }else{
		$result['idproof1'] =ADMIN.'/img/noimage.png';
	  } 
	  if(file_exists( FILEPATH.'captainid/'. $result['userdetail'][0]['idproof2']) && $result['userdetail'][0]['idproof2'] != ""){
		$result['idproof2']=FILEURL.'captainid/'.$result['userdetail'][0]['idproof2'];           
	  }else{
		$result['idproof2'] =ADMIN.'/img/noimage.png';
	  } 
	  $boatImage = $this->db->select('image')
	  					->where('captain_id',$result['userdetail'][0]['captain_id'])
	  					->get('b_boat_images');
	  $boatArr = $boatImage->result_array();
	  foreach ($boatArr as $value) {
	  	  if(file_exists( FILEPATH.'boat/'. $value['image']) && $value['image'] != ""){
			$result['image'][]=FILEURL.'boat/'.$value['image'];           
		  }else{
			$result['image'][] =ADMIN.'/img/noimage.png';
		  } 
	  }
	 // echo "<pre>";print_r($result); die;
	  return $result;
	}

//Change User status 
function changeStatus(){
    if($_POST['type']==1){
      $data = ['verified' => 'true'];
    }else{
      $data = ['verified' => 'false'];
    }
    $this->db->where('captain_id',$_POST['post_id'])
          ->update('b_captain',$data);
    if ($this->db->affected_rows() > 0) {
      echo 1; die;
    }else{
      echo 0; die;
    }
}


	
}
   