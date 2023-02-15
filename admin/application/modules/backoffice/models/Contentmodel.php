<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contentmodel extends MX_Controller {

//Post Listing
function postlisting($limit, $start, $search = "", $orderField, $orderDirection){
    $orwhere = "(post_name LIKE '%".$search."%')";
    $this->db->select('post_id,post_name,post_description,post_category,post_status,created_on');    
    if (!empty($search)) {
      $this->db->where($orwhere);
    }
    $this->db->limit($limit, $start);
    $this->db->order_by($orderField, $orderDirection);
    $this->db->order_by('post_id', 'DESC');
    $query=$this->db->get('q_post');
    return $query->result();
}
//Website Post Listing count
function count_postlisting($limit, $start, $search = "", $orderField, $orderDirection){
    $orwhere = "(post_name LIKE '%".$search."%')";
    $this->db;
    if (!empty($search)) {
      $this->db->where($orwhere);
    }
    $query=$this->db->get('q_post');
    return $query->num_rows();
}
//Change post status 
function changeStatus(){
    if($_POST['type']==1){
      $data = ['post_status' => 'active','updated_on' => date('Y-m-d H:m:s')];
    }else{
      $data = ['post_status' => 'inactive','updated_on' => date('Y-m-d H:m:s')];
    }
    $this->db->where('post_id',$_POST['post_id'])
          ->update('q_post',$data);
    if ($this->db->affected_rows() > 0) {
      echo 1; die;
    }else{
      echo 0; die;
    }
}
//Post Detail
function postDetail($postid){
  $query=$this->db->select('post_id,post_name,post_description,post_category,post_status,created_on')
          ->where('post_id',$postid)
          ->get('q_post');
  $result = $query->result_array();
  foreach ($result as $value) {
        $data = [
            'post_id' => $value['post_id'],
            'post_name' => $value['post_name'],
            'post_description' => $value['post_description'],
            'post_status' => $value['post_status']
        ];
    }
    return $data;
}

function editPostprocess($array){
    $data = ['post_name' => $array['post_title'],'post_description' => $array['post_content'],'updated_on' => date('Y-m-d H:i:s')];
  $this->db->where('post_id',$array['post_id'])
      ->update('q_post',$data);
  if ($this->db->affected_rows() > 0) {
      header('location: '.ADMINURL.'Content/listing');
  }else{
      header('location: '.ADMINURL.'Content/listing');
  }
}


function addPostprocess($array){
  $adminid = $this->session->userdata('admin_id');
  if($array['post_category']=='faq'){
      $data = ['post_name' => $array['post_title'],'post_description' => $array['post_content'],'post_category' => $array['post_category'],'post_identifier' => $array['post_category'],'added_by' => $adminid,'created_on' => date('Y-m-d H:i:s')];
  }else{
      $title = $array['post_title'];
      $urlTitles = str_replace(array( '\'', '"', ',' , ';', '<', '>','?','+' ), '', $title); 
      $urlTitles = preg_replace('/[^A-Za-z0-9\. -]/', '-', $urlTitles); 
      $urlTitle =  preg_replace('/\s+/', '-', $urlTitles);
      $url = strtolower($urlTitle);
      $checkquery = $this->db->select('post_id')
              ->where('post_identifier',$url)
              ->get('q_post');
      if($checkquery->num_rows()>0){
        $coursecount = $this->total_pcount();
        $newurl = $url.$coursecount;
      }else{
        $newurl = $url;
      }

      $data = ['post_name' => $array['post_title'],'post_description' => $array['post_content'],'post_category' => $array['post_category'],'post_identifier' => $newurl,'added_by' => $adminid,'created_on' => date('Y-m-d H:i:s')];
  }
  $this->db->insert('q_post',$data);
  if ($this->db->affected_rows() > 0) {
      header('location: '.ADMINURL.'Content/listing');
  }else{
      header('location: '.ADMINURL.'Content/listing');
  }
}

function total_pcount() {
  $query = $this->db->query("SELECT COUNT(post_id) as post from q_post");
  $blogArrs = $query->result_array();
  $totalcount = $blogArrs[0]['post'];
  return $totalcount;
}


}
   