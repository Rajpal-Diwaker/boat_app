<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminmodel extends MX_Controller {

	function login($email,$password){
		$sql = "SELECT admin_id,fullname,email,password_hash,admin_type FROM b_admin WHERE status='active' and email = ?";
		$query = $this->db->query($sql, array($email));
		$result = $query->result_array();
		if(verifyHashedPassword($password, $result[0]['password_hash'])){
			$user_id = $result[0]['admin_id'];
			$accesskey = md5($user_id.rand (1000000, 9999999));
			$accessdata = ['auth_token' => $accesskey,'created_on' => date('Y-m-d H:i:s')];
			$this->db->where('admin_id',$user_id);
			$this->db->update('b_admin_auth',$accessdata);
			return [
				'admin_id' => $result[0]['admin_id'],
				'fullname' => $result[0]['fullname'],
				'email' => $result[0]['email'],
				'admin_type' => $result[0]['admin_type'],
				'token' => $accesskey
				];
		}
		else{
			return false;
		}
	}

	function dashboard(){
		$userQuery = $this->db->query("SELECT COUNT(user_id) as user FROM `b_user`");
		$userArr = $userQuery->result_array();

		$captainQuery = $this->db->query("SELECT COUNT(captain_id) as captain FROM `b_captain`");
		$captainArr = $captainQuery->result_array();

		return ['user' => $userArr[0]['user'],'captain' => $captainArr[0]['captain'] ]; 
	}


	function adminprofile(){
		$adminid = $this->session->userdata('admin_id');
		$query = $this->db->select('admin_id,fullname,email,profile_pic,status')
					->where('admin_id',$adminid)
					->get('b_admin');
		$result = $query->result_array();
		return $result[0];
	}

	function admin_management($limit, $start, $search = "", $orderField, $orderDirection){
		$adminid = $this->session->userdata('admin_id');
	    $orwhere = "(fullname LIKE '%".$search."%' OR email LIKE '".$search."')";
	    $this->db->select('*');
	    $this->db->where('admin_id!=',$adminid);
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->limit($limit, $start);
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $query=$this->db->get('b_admin');
	    $profileArr = $query->result_array();
	    foreach ($profileArr as $value) {
			if(file_exists( FILEPATH. $value['profile_pic']) && $value['profile_pic'] != ""){
	            $profile_pic=FILEURL.$value['profile_pic'];      
	        }else{
	            $profile_pic =ADMIN.'/img/person.jpeg';
	        } 
	        $addedon = date('j M Y',strtotime($value['created_date']));
	        $responseArr[] = [
	        	'admin_id' => $value['admin_id'],
	        	'fullname' => $value['fullname'],
	        	'email' => $value['email'],
	        	'profile_pic' => $profile_pic,
	        	'admin_type' => $value['admin_type'],
	        	'created_date' => $addedon,
	        	'status' => $value['status']
	        	];
		}
	     return $responseArr;
	}
	//Blog  Listing count
	function count_admin_management($limit, $start, $search = "", $orderField, $orderDirection){
		$adminid = $this->session->userdata('admin_id');
	    $orwhere = "(fullname LIKE '%".$search."%' OR email LIKE '".$search."')";
	    $this->db;
	    $this->db->where('admin_id!=',$adminid);
	    if (!empty($search)) {
	      $this->db->where($orwhere);
	    }  
	    $this->db->order_by($orderField, $orderDirection);
	    $this->db->order_by($orderField, $orderDirection);
	    $query=$this->db->get('b_admin');
	    return $query->num_rows();
	}

	function update_admindata($array){
		//print_r($_POST); die;
		$adminid = $this->session->userdata('admin_id');
		if(!empty($array['profile_pic'])){
			$data = ['fullname' => $array['fullname'],'email' =>  $array['email'],'profile_pic' => 'admin/'.$array['profile_pic']];
		}else{
			$data = ['fullname' => $array['fullname'],'email' =>  $array['email']];
		}
		$this->db->where('admin_id',$adminid)
				->update('b_admin',$data);
		return true;
	}

	function addAdminprocess($array){
		if(!empty($array['profile_pic'])){
			$profile_pic = 'admin/'.$array['profile_pic'];
		}else{
			$profile_pic = '';
		}
		$data = ['fullname' => $array['fullname'],'email' => $array['email'],'profile_pic' => $profile_pic,'password_hash' => getHashedPassword($array['password']),'created_date' => date('Y-m-d H:i:s')];
		$this->db->insert('b_admin',$data);
		$userid = $this->db->insert_id();
		$accesskey = md5($userid.rand (1000000, 9999999));
		$accessdata = ['admin_id' => $userid,'auth_token' => $accesskey,'created_on' => date('Y-m-d H:i:s')];
		$this->db->insert('b_admin_auth',$accessdata);
		if ($this->db->affected_rows() > 0) {
		  header('location: '.ADMINURL.'Admin/admin_management');
		}else{
		  header('location: '.ADMINURL.'Admin/admin_management');
		}
	}

	function change_password($oldpassword,$newpassword){
		$adminid = $this->session->userdata('admin_id');
		$sql = "SELECT admin_id,password_hash FROM b_admin WHERE admin_id = ?";
		$query = $this->db->query($sql, array($adminid));
		$result = $query->result_array();
		if(verifyHashedPassword($oldpassword, $result[0]['password_hash'])){
			$data = ['password_hash'=>getHashedPassword($newpassword)];
			$this->db->where('admin_id',$adminid)
					->update('b_admin',$data);
			if ($this->db->affected_rows() > 0) {
				echo 1; die;
			}else{
				echo 2; die;
			}
		}else{
			echo 2; die;
		}
	}

	function updateNewPassword($strpassword,$email){
		$data = ['password_hash'=>getHashedPassword($strpassword)];
		$this->db->where('email',$email)
				->update('b_admin',$data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}


	function changeuserStatus(){
	    if($_POST['type']==1){
	      $data = ['status' => 'active','modified_date' => date('Y-m-d H:m:s')];
	    }else{
	      $data = ['status' => 'inactive','modified_date' => date('Y-m-d H:m:s')];
	    }
	    $this->db->where('admin_id',$_POST['user_id'])
	          ->update('b_admin',$data);
	    if ($this->db->affected_rows() > 0) {
	      echo 1; die;
	    }else{
	      echo 0; die;
	    }
	}

}
   