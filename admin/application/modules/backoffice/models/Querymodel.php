<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Querymodel extends MX_Controller {

	function contacts($limit, $start, $search = "", $orderField, $orderDirection){
	    $orwhere = "(u.u_username LIKE '%".$search."%')";
	    $this->db->select('u.u_id,u.u_username,u.u_profilepic,c.contact_id,c.description,c.contacted_on');
	    $this->db->from('q_contactus as c');
	    $this->db->join('q_user as u','c.u_id=u.u_id');
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->limit($limit, $start);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by('c.contact_id','DESC');
	    $query=$this->db->get();
	    $profileArr = $query->result_array();
	    foreach ($profileArr as $value) {
	    	if(file_exists( FILEPATH.'userprofile/'. $value['u_profilepic']) && $value['u_profilepic'] != ""){
	            $profilepic=FILEURL.'userprofile/'.$value['u_profilepic'];           
	        }elseif(!empty($value['u_profilepic'])){
	            $profilepic =$value['u_profilepic'];
	        }else{
	            $profilepic =ADMIN.'/img/noimage.png';
	        }
	        $addedon = date('j M Y',strtotime($value['contacted_on']));
	        $description = character_limiter($value['description'],120);
	        $responseArr[] = [
	        	'contact_id' => $value['contact_id'],
	        	'description' => $description,
	        	'username' => $value['u_username'],
	        	'user_id' => $value['u_id'],
	        	'created_date' => $addedon,
	        	'profilepic' => $profilepic
	        	];
		}
	     return $responseArr;
	}

	function count_contacts($limit, $start, $search = "", $orderField, $orderDirection){
	    $orwhere = "(u.u_username LIKE '%".$search."%')";
	    $this->db;
	    $this->db->from('q_contactus as c');
	    $this->db->join('q_user as u','c.u_id=u.u_id');
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $query=$this->db->get();
	    return $query->num_rows();
	}

	function viewDetail($postid){
	  $query=$this->db->select('u.u_id,u.u_username,u.u_profilepic,c.contact_id,c.description,c.contacted_on')
		->from('q_contactus as c')
		->join('q_user as u','c.u_id=u.u_id')
		->where('c.contact_id',$postid)
		->get();
	  $result = $query->result_array();
	  foreach ($result as $value) {
	        if(file_exists( FILEPATH.'userprofile/'. $value['u_profilepic']) && $value['u_profilepic'] != ""){
	            $profilepic=FILEURL.'userprofile/'.$value['u_profilepic'];           
	        }elseif(!empty($value['u_profilepic'])){
	            $profilepic =$value['u_profilepic'];
	        }else{
	            $profilepic =ADMIN.'/img/noimage.png';
	        }
	        $addedon = date('j M Y',strtotime($value['contacted_on']));
	        $screenshotQuery = $this->db->select('name')
	        					->where('contact_id',$value['contact_id'])
	        					->get('q_contact_screenshot');
	        $screenArr = $screenshotQuery->result_array();
	        foreach ($screenArr as $photo) {
	        	if(file_exists( FILEPATH.'contact/'. $photo['name']) && $photo['name'] != ""){
		            $photo=FILEURL.'contact/'.$photo['name'];           
		        }else{
		            $photo =ADMIN.'/img/noimage.png';
		        }
	        	$screenshot[] = $photo;
	        }
	        $responseArr = [
        	'contact_id' => $value['contact_id'],
        	'description' => $value['description'],
        	'username' => $value['u_username'],
        	'user_id' => $value['u_id'],
        	'created_date' => $addedon,
        	'profilepic' => $profilepic,
        	'screenshot' => $screenshot
        	];
	    }
	    return $responseArr;
	}



	
}
   