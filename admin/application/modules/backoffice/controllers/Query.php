<?php
//Query controller to manage website queries
class Query extends MX_Controller {

 	public $client;
 	private $data = array();
    function __construct() {
        parent::__construct();
        $this->load->model('backoffice/Querymodel');
        $this->load->helper(array('form', 'text'));
        $this->load->library('pagination');
        $login=$this->session->userdata('adminlogin');
        $checkaccess = $this->checkaccess();
        if($login!="true" && $checkaccess!=true){
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }
    //Check User auth token
    function checkaccess(){ 
        $auth= $this->session->userdata('token');
        $user_id = $this->session->userdata('admin_id'); 
        $authsql = "SELECT admin_id FROM q_admin_auth WHERE auth_token = ? and admin_id = ?";
        $authquerycheck = $this->db->query($authsql,[$auth,$user_id]);
        $authArr = $authquerycheck->result_array();
        if(empty($authArr)){
            $this->session->sess_destroy();
           header('location: '.BASEURL.'/backoffice');
           exit();
        }else{
           return true;
        }
    }
    //Contacts Listing
	function contacts(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
        $data['page_title'] = 'Contact Request';
        $config['base_url'] = base_url('backoffice/Query/contacts');
        
        $config['per_page'] = ($this->input->get('limitRows')) ? $this->input->get('limitRows') : 10;
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
         // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
       
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="'.$config['base_url'].'?per_page=0">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data['page'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $data['searchFor'] = ($this->input->get('query')) ? $this->input->get('query') : NULL;
        $data['orderField'] = ($this->input->get('orderField')) ? $this->input->get('orderField') : '';
        $data['orderDirection'] = ($this->input->get('orderDirection')) ? $this->input->get('orderDirection') : '';
        $data['resultArr'] = $this->Querymodel->contacts($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $config['total_rows'] = $this->Querymodel->count_contacts($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('backoffice/Common/header',$data);
        $this->load->view('backoffice/Common/sidebar');
        $this->load->view('backoffice/Query/contacts',$data);
        $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice');
            exit();
        }
    }

  //View Contact Detail
    function viewDetail(){
      $id = $this->uri->segment(4);
      $data['page_title'] = 'Contact Request Detail';
      $postid = base64_decode(urldecode($id));
      $data['postArr']=$this->Querymodel->viewDetail($postid);  
      $this->load->view('backoffice/Common/header',$data);
        $this->load->view('backoffice/Common/sidebar');
        $this->load->view('backoffice/Query/viewDetail',$data);
        $this->load->view('backoffice/Common/footer');
    }

   
  
}