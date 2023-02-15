<?php
//User controller to manage website queries
class User extends MX_Controller {

 	public $client;
 	private $data = array();
    function __construct() {
        parent::__construct();
        $this->load->model('backoffice/Usermodel');
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
        $authsql = "SELECT admin_id FROM b_admin_auth WHERE auth_token = ? and admin_id = ?";
        $authquerycheck = $this->db->query($authsql,[$auth,$user_id]);
        $authArr = $authquerycheck->result_array();
        if(empty($authArr)){
            $this->session->sess_destroy();
           header('location: '.BASEURL.'/backoffice/Admin');
           exit();
        }else{
           return true;
        }
    }
    //Contacts Listing
	function listing(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
        $data['page_title'] = 'User Listing';
        $config['base_url'] = base_url('backoffice/User/listing');
        
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
        $data['resultArr'] = $this->Usermodel->listing($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $config['total_rows'] = $this->Usermodel->count_listing($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('backoffice/Common/header',$data);
        $this->load->view('backoffice/Common/sidebar');
        $this->load->view('backoffice/User/listing',$data);
        $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    function export(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
        $data['page_title'] = 'User Listing';
        $config['base_url'] = base_url('backoffice/User/listing');
        
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
        $data['resultArr'] = $this->Usermodel->listing($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $config['total_rows'] = $this->Usermodel->count_listing($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $this->pagination->initialize($config);
        $this->load->library("excel");
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);
            $table_columns = array("Full Name", "Email", "Country Code", "Mobile", "Act Type", "Status", "Joined On", "Verified", "Profile Completed");
            $column = 0;
            foreach($table_columns as $field)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $excel_row = 2;

            foreach($data['resultArr'] as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['full_name']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['email']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['country_code']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['mobile']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['act_type']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['status']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['created_on']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['verified']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row['profile_completed']);
                $excel_row++;
            }
            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="User Data.xls"');
            $object_writer->save('php://output');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    function viewUser(){
      $id = $this->uri->segment(4);
      $data['page_title'] = 'User Detail';
      $pid = base64_decode(urldecode($id));
      $data['resultArr']=$this->Usermodel->viewUser($pid); 
      $this->load->view('backoffice/Common/header',$data);
        $this->load->view('backoffice/Common/sidebar');
        $this->load->view('backoffice/User/viewUser',$data);
        $this->load->view('backoffice/Common/footer');
    }

    //Change Quesh status 
    function changeStatus(){
        $result = $this->Usermodel->changeStatus();    
    }


  
}