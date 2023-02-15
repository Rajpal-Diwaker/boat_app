<?php
class Admin extends MX_Controller {

 	public $client;
 	private $data = array();
    function __construct() {
        parent::__construct();
        $this->load->model('backoffice/Adminmodel');
		$this->load->helper('text');
        $this->load->helper('url');
    }

	
    function index($msg=NULL) {
    	$login=$this->session->userdata('adminlogin');
        if($login=="true" && $this->checkaccess()===true){ 
        	$this->dashboard();
        }else{
        	$data['page_title'] = 'Boat Admin | Login';
	        $data['msg'] = $msg;
	        $this->load->view('backoffice/Common/header',$data);
	        $this->load->view('backoffice/Admin/login');
        }        
    }

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
	
    //User Login Process
    public function login(){
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));
        if(!empty($email) && !empty($password)){
            $checkemail = $this->common->checkrecord('b_admin', 'email', $email);
            if(true === $checkemail){
                $rows=$this->Adminmodel->login($email,$password);
                if($rows === false){
                    $msg="Invalid Password";
                    $this->index($msg);
                }else{
                    $userdata = [
                        'admin_id' => $rows['admin_id'],
                        'fullname' => $rows['fullname'],
                        'email' => $rows['email'],
                        'admin_type' => $rows['admin_type'],
                        'adminlogin'=>'true',
                        'token' => $rows['token']
                        ];
                    $this->session->set_userdata($userdata);
                    header('location: '.BASEURL.'/backoffice/Admin/dashboard/');
                }
            }else{
                $msg="Invalid Email";
                $this->index($msg);
            }
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }	

    public function addAdmin(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
            $data['page_title'] = 'Add New Admin';    
            $this->load->view('backoffice/Common/header',$data);
            $this->load->view('backoffice/Common/sidebar');
            $this->load->view('backoffice/Admin/addAdmin',$data);
            $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    function addAdminprocess(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
        if ($_FILES['profile_pic']['error'] == '0' && $_FILES['profile_pic']['name'] != '') {
            $path = $this->common->do_upload("uploads/admin",'profile_pic','img');
            $this->data['profile_pic'] = $path['name'];                      
        }      
        $this->data['fullname'] = $_POST['fullname'];
        $this->data['email'] = $_POST['email'];
        $this->data['password'] = $_POST['password'];
        $this->data['role'] = $_POST['role'];
        $result = $this->Adminmodel->addAdminprocess($this->data);
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    //Edit Admin 
    public function editProfile($msg = NULL){
        $login=$this->session->userdata('adminlogin');
        if($login=="true" && $this->checkaccess()===true){ 
            $data['page_title'] = 'Edit Profile';    
            $data['userArr']=$this->Adminmodel->adminprofile();      
            $data['msg'] = $msg;
            $this->load->view('backoffice/Common/header',$data);
            $this->load->view('backoffice/Common/sidebar');
            $this->load->view('backoffice/Admin/editProfile',$data);
            $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    //Admin Profile Update 
    public function update_profiledata(){
        $login=$this->session->userdata('adminlogin');
        if($login=="true" && $this->checkaccess()===true){ 
            if ($_FILES['profile_pic']['error'] == '0' && $_FILES['profile_pic']['name'] != '') {
                $path = $this->common->do_upload("uploads/admin",'profile_pic','img');
                $this->data['profile_pic'] = $path['name'];                      
            } 
            $this->data['fullname'] = $_POST['fullname'];
            $this->data['email'] = $_POST['email'];
            $result=$this->Adminmodel->update_admindata($this->data);   
            if($result === true){
                $msg="Updated Successfully";
                $this->editProfile($msg);
            } 
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }     
    }

    function admin_management(){
        $login=$this->session->userdata('adminlogin');
        $admin_type = $this->session->userdata('admin_type');
        if($login=="true" && $this->checkaccess()===true && $admin_type=='admin'){ 
        $data['page_title'] = 'Admin Management';
        $config['base_url'] = base_url('backoffice/Admin/admin_management');
        
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
        $data['adminArr'] = $this->Adminmodel->admin_management($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $config['total_rows'] = $this->Adminmodel->count_admin_management($config["per_page"], $data['page'], $data['searchFor'], $data['orderField'], $data['orderDirection']);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('backoffice/Common/header',$data);
        $this->load->view('backoffice/Common/sidebar');
        $this->load->view('backoffice/Admin/admin_management',$data);
        $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    function changeuserStatus(){
        $result = $this->Adminmodel->changeuserStatus();  
    }
	
	//User Logout Process
    public function do_logout(){
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('adminlogin');
		header('location: '.BASEURL.'/backoffice/Admin');
        exit();
    }	
	
	public function dashboard(){
        $login=$this->session->userdata('adminlogin');
        if($login=="true" && $this->checkaccess()===true){   
        /*    $url = $_GET['year'];
            if(!empty($url)){
                $year = $url;
            }else{
                $year = date('Y');
            } 
            $data['filter'] = ['year' => $year]; */
            $data['resultArr']=$this->Adminmodel->dashboard();   
            $data['page_title'] = 'Boat Admin | Dashboard';
            $this->load->view('backoffice/Common/header',$data);
            $this->load->view('backoffice/Common/sidebar');
            $this->load->view('backoffice/Admin/dashboard',$data);
            $this->load->view('backoffice/Common/footer');
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }


    public function check_email_exists(){
        $login=$this->session->userdata('adminlogin');
        if($login=="true"){
        $user_id = $this->session->userdata('admin_id');
        $email = $this->security->xss_clean($this->input->post('email')); 
        $checkuser = $this->common->checkrecordid('b_admin', 'email', $email, 'admin_id', $user_id);
        if(true === $checkuser){ echo 1; die; }else{
            $checkemail = $this->common->checkrecord('b_admin', 'email', $email);
            if(false === $checkemail){
                echo 1; die;
            }else{
                echo 2; die;
            }
        }
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    public function check_email_exist(){
        $login=$this->session->userdata('adminlogin');
        if($login=="true"){
            $user_id = $this->session->userdata('admin_id');
            $email = $this->security->xss_clean($this->input->post('email')); 
            $checkemail = $this->common->checkrecord('b_admin', 'email', $email);
            if(false === $checkemail){
                echo 1; die;
            }else{
                echo 2; die;
            }
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }
    }

    public function changepassword(){
        $login=$this->session->userdata('adminlogin');
        if($login=="true"){
            $oldpassword = $this->security->xss_clean($this->input->post('oldpwd'));
            $newpassword = $this->security->xss_clean($this->input->post('newpwd'));
            $result=$this->Adminmodel->change_password($oldpassword,$newpassword);     
        }else{
            header('location: '.BASEURL.'/backoffice/Admin');
            exit();
        }      
    }

    public function forgotpassword(){
        $email = $this->security->xss_clean($this->input->post('email'));
        $checkEmail = $this->common->checkrecord('b_admin', 'email', $email);
        if(true === $checkEmail){
            $strpassword = $this->common->generateRandomString();
            $subject = "Password recovery email | One Quesh";
            $image_URL = ADMIN.'/img/logo.png';
            $bodytext = '<p>Greetings,<br>Your New Password: <strong>'.$strpassword.'</strong><br>Please login with given password and update your profile<br>Thanks<br> One Quesh Team';
            $body = '<!doctype html>
                    <html>
                    <head>
                    <meta charset="utf-8">
                    <title>Worqleus</title>
                    </head>
                    <body>
                    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="'.$image_URL.'" width="260" height="77" alt="" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="font-size:15px; font-family:Arial, Helvetica, sans-serif; line-height:24px;">'.$bodytext.'</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td bgcolor="#000" style="font-size:15px; font-family:Arial, Helvetica, sans-serif; padding:15px; text-align:center; color:#fff;">© Follow the notes©All Rights Reserved 2020</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>

                    </body>
                    </html>
                ';
            $result = $this->Adminmodel->updateNewPassword($strpassword,$email);
            if(false === $result ){
                echo 3; die;
            }
            else{ 
            $this->load->library('email');
            $mailresult = $this->email
                    ->from('info@One Quesh.com')
                    ->to($email)
                    ->subject($subject)
                    ->message($body)
                    ->send();
            echo 1; die;
           }            
        }else{echo 2; die;}
    }


    public function testemail(){
        $email = 'alok@techugo.com';
            $strpassword = $this->common->generateRandomString();
            $subject = "Password recovery email | Follow the Notes";
            $image_URL = ADMIN.'/img/logo.png';
            $bodytext = '<p>Greetings,<br>Your New Password: <strong>'.$strpassword.'</strong><br>Please login with given password and update your profile<br>Thanks<br> Follow the Notes Team';
            $body = '<!doctype html>
                    <html>
                    <head>
                    <meta charset="utf-8">
                    <title>Worqleus</title>
                    </head>
                    <body>
                    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center"><img src="'.$image_URL.'" width="260" height="77" alt="" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="font-size:15px; font-family:Arial, Helvetica, sans-serif; line-height:24px;">'.$bodytext.'</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td bgcolor="#000" style="font-size:15px; font-family:Arial, Helvetica, sans-serif; padding:15px; text-align:center; color:#fff;">© Follow the notes©All Rights Reserved 2020</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table>

                    </body>
                    </html>
                ';
            $this->load->library('email');
            $mailresult = $this->email
                    ->from('info@followthenotes.com')
                    ->to($email)
                    ->subject($subject)
                    ->message($body)
                    ->send();
            echo 1; die;           
    }


}