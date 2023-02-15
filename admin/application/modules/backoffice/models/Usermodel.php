<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usermodel extends MX_Controller {

	function listing($limit, $start, $search = "", $orderField, $orderDirection){
	    $orwhere = "(full_name LIKE '%".$search."%' OR email LIKE '".$search."' OR mobile LIKE '".$search."')";
	    $this->db->select('*');
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->limit($limit, $start);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by('user_id','DESC');
	    $query=$this->db->get('b_user');
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
	    $query=$this->db->get('b_user');
	    return $query->num_rows();
	}

	function viewUser($pid){
	  $query=$this->db->select('u.*')
	  		->from('b_user as u')
	  		//->join('q_user_info as ui','u.u_id=ui.u_id')
          	->where('u.user_id',$pid)
          	->get('');
	  $result['userdetail'] = $query->result_array();
	  return $result;
	}

//Change User status 
function changeStatus(){
    if($_POST['type']==1){
      $data = ['status' => 'active'];
    }else{
      $data = ['status' => 'inactive'];
    }
    $this->db->where('user_id',$_POST['post_id'])
          ->update('b_user',$data);
    if ($this->db->affected_rows() > 0) {
      echo 1; die;
    }else{
      echo 0; die;
    }
}


	
}
   