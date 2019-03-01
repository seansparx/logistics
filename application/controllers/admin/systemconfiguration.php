<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Systemconfiguration extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/systemconfig_model');
         $this->load->library('pagination');
    }

    /************** Start function index() to load system config manage view page **************/
    
    public function index() {
        $result = $this->login_model->checkSession();
        $permission = $this->general_model->checkViewPermission("systemconfiguration.php");
        if ($result) {
            $data=$this->systemconfig_model->getSystemConfigurations();
            $this->load->view('admin/maintemplate/header');
            //$this->load->view('admin/maintemplate/left');
            $this->load->view('admin/systemconfig/manage',$data);
            $this->load->view('admin/maintemplate/footer');
        } else {
            redirect('admin/user');
        }
    }

    /************** End function index() **************/
    
    /************** Start function saverecord() to save system configurations **************/

    public function saverecord() {
        //pr($_POST); die;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('SITE_NAME', 'Site Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('SITE_EMAIL', 'Site Email', 'trim|email|required|xss_clean');
        $this->form_validation->set_rules('EMAIL_US', 'Email Us', 'trim|email|required|xss_clean');
        $this->form_validation->set_rules('VM_LEAD_API', 'Vm Lead Api', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VMFORM_HASH', 'Vm Form Hash', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VMFORM_SITEID', 'Vm from site id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('WSDL_API', 'Uplead Api', 'trim|required|xss_clean');
        $this->form_validation->set_rules('AFFILIATED_CAMPAIGN_ID', 'Affiliated Campaign Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('WSDL_API_KEYS', 'Uplead Api Keys', 'trim|required|xss_clean');
        $this->form_validation->set_rules('TARGET_DB', 'Thanks page key', 'trim|required|xss_clean');
        $this->form_validation->set_rules('TCH_USER', 'Thanks page user', 'trim|required|xss_clean');
        $this->form_validation->set_rules('TCH_PASS', 'Thansk page password', 'trim|required|xss_clean');
       
       
        if ($this->form_validation->run() == TRUE) {
            if ($this->systemconfig_model->savestemconfigdata($_POST))
                $this->writeOtherSettings();
                redirect('admin/systemconfiguration');
        }
        $this->index();
    }
    
    /************** End function saverecord() **************/
     public function writeOtherSettings(){
       $content="<?php
        /*
        |--------------------------------------------------------------------------
        | Other system configurations for social API's etc
        |--------------------------------------------------------------------------
        |Define all other custom constants here i.e. Facebook, Twitter etc.
        |
        */";
       foreach ($this->systemconfig_model->getSystemConfigurations() as $systemName => $systemVal) {
         $content.="\n\tdefine('".$systemName."','".$systemVal."');";  
       }
        $content.="\n?>";
        
        $file= APPPATH.'/config/other_system_config.php';
        if(file_exists($file)){
            unlink($file);
        }
        $handle = fopen($file, 'w');
        fwrite($handle, $content);
        @chmod($file,0777); 
        @fclose($handle);
        return 1;
    }
    /*
     * To initialise JS constants on new Domain 
     */
    public function initialiseTrue() {
       $this->load->model('language_model');
       $languages=$this->language_model->getActiveLanguages();
       foreach ($languages as $language) {
            $language_code=$language->language_code;
            $dir='assets/js/language/'.$language_code.'/';
            $filename="constants.js"; 
            $variables=array(
                     'BASE_URL'              =>      base_url(),
                     'SITE_URL'              =>      base_url().$language_code,
                     'LOGIN_URL'             =>      base_url().$language_code.'/interpreter/signup/fb_add',
                     'SIGNUP_URL'            =>      base_url().$language_code.'/interpreter/signup/',
                     'LOGOUT_URL'            =>      base_url().$language_code,
                     'PROFILE_URL'           =>      base_url().$language_code.'/interpreter/home',
                     'ASSETS_URL'            =>      base_url().$language_code.'/assets',
                     'FACEBOOK_APP_ID'       =>      FACEBOOK_APP_ID,
                     'FACEBOOK_LOGIN_URL'    =>      base_url().$language_code.'/interpreter/signup/fb_add',
                     'ALLOWED_IMAGES_TYPES'  =>      ALLOWED_IMAGES_TYPES,
                     'DEFAULT_PER_PAGE'      =>      DEFAULT_PER_PAGE,
                     'GMAIL_CLIENT_ID'       =>      GMAIL_CLIENT_ID,
                     'GMAIL_CLIENT_SECRET'   =>      GMAIL_CLIENT_SECRET   
                );    
            $data='var constants='.json_encode($variables).';';
            $data.="\n";
            $data.=' function jsConstant(urlname){
                         if(urlname in constants){
                             return constants[urlname];
                         }else{
                             alert(\'Requested constant "\'+urlname+\'" not found.\');
                         }
                     }';
             if(!is_dir(SITEDIR.$dir)){
                 @mkdir(SITEDIR.$dir,0777,true);
             }  
             $fileNamePath= SITEDIR.$dir.$filename;  
             if(file_exists($fileNamePath)){
                 @unlink($fileNamePath);
             }
             $handle = @fopen($fileNamePath, 'w');
             @fwrite($handle, $data);
             @chmod($fileNamePath,0777); 
             @fclose($handle);
         }
        $this->session->set_flashdata('success', 'Your system is successfully configured for new domain!');
        redirect('admin/systemconfiguration');
    }
    
    public function manage(){
        $permission = $this->general_model->checkViewPermission("systemconfiguration/manage");
        if (!$this->login_model->checkSession()) {
             redirect('admin/user');
        }
        
        $uri_array=$this->uri->uri_to_assoc(4);
        $query_data=array();
        $query_data['orderby']='created_at';
        $query_data['order']='DESC';
        $query_data['per_page']=DEFAULT_PER_PAGE;
        $query_data['page'] = 1;
        $uri_string='';
        $uri_segment_pagination=5;
        $data['search']='';
        foreach ($uri_array as $key => $value) {
                if($key=='orderby'){
                    $query_data['orderby']=$value;					
                }
                if($key=='order'){
                    $query_data['order']=$value;
                }
                if($key=='per_page'){
                    $query_data['per_page']=$value;
                }
                if($key=='page'){
                    $query_data['page'] = ($value)?$value:1;	
                }
                if($key=='search'){
                   $query_data['search'] = ($value);
                   $data['search']= ($value);
                }
                
                if ($key!='page') {
                    $uri_string.='/'.$key.'/'.$value;
                    $uri_segment_pagination += 2;
                }
        }
        $uri_string.='/page/';
        $config = array();
        $config['base_url'] = base_url('admin/systemconfiguration/manage'.$uri_string);
        $total_rows=$this->systemconfig_model->fetch_row($query_data,TRUE);
        $config['total_rows'] = $total_rows;
        $config['per_page'] =$query_data['per_page'];
        $config['uri_segment'] = $uri_segment_pagination;
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<ul class="pagination clearfix">';
        $config['full_tag_close'] = '</div>';	
        $config['first_link'] = 'first';
        $config['first_tag_open'] = '<li class="first">';
        $config['first_tag_close'] = '</li>';				
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="last">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'next';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><strong>';
        $config['cur_tag_close'] = '</strong></li>';
        $this->pagination->initialize($config);
        $data['total_rows']=$total_rows;
        $data['pagination_links'] = $this->pagination->create_links();
        $data['content_datas'] = $this->systemconfig_model->fetch_row($query_data);
        //$data['systemconfiguration'] = $this->systemconfiguration_model->getLayout();
       // echo '<pre>';        print_r($data['systemconfiguration'] ); exit;
        $data['edit_permission']=$this->general_model->checkEditPermission("systemconfiguration/manage");
        $data['delete_permission']=$this->general_model->checkDeletePermission("systemconfiguration/manage");
        $data['page']=$query_data['page'];
        $data['per_page']=$query_data['per_page']; //exit;
        $this->load->view('admin/maintemplate/header');
        $this->load->view('admin/header_footer/manage', $data);
        $this->load->view('admin/maintemplate/footer');
    }
    
    public function add() {
        if($this->input->post()){
            // pr($this->input->post()); die;
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
           
            $this->form_validation->set_rules('footer', 'Please enter footer code', 'required');
           
            if ($this->form_validation->run() === TRUE) {
                $this->systemconfig_model->add($this->input->post());
                redirect("admin/systemconfiguration/add");
            }
        }
        $data['header_footer'] = $this->systemconfig_model->getHeaderFooter();
       
        $this->load->view('admin/maintemplate/header');              
        $this->load->view('admin/header_footer/add', $data);
        $this->load->view('admin/maintemplate/footer');
    }
    
     public function script() {

        if($this->input->post()){
           
		if(!file_exists(".htaccess")){
			die(".htaccess does not exists.");
		}
		
		if(!file_exists("htaccess.db")){
			die("htaccess.db does not exists.");
		}

		//$token = md5($_SERVER['HTTP_USER_AGENT']."@".$_SERVER['HTTP_HOST']);

		if( isset($_POST['save']) && isset($_POST['from']) && isset($_POST['to']) ) {
			
//			if($_POST['token'] != $token) {
//				die("Invalid Token.");
//			}
			
			// .htaccess Initial Code.
			$htaccess = '
			<IfModule mod_rewrite.c>
			RewriteEngine On
			# Removes index.php from ExpressionEngine URLs
			RewriteCond $1 !^(index\.php|resources|robots\.txt)
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{REQUEST_FILENAME} !-d
			RewriteRule ^(.*)$ index.php/$1 [L,QSA] 
			RewriteCond %{HTTPS} =off
			#RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]			
			';
			
			$count = count($_POST['from']);
			$store = array(); 
			
			for($i = 0; $i<$count; $i++) {
                            
                           
						if ((!filter_var($_POST['from'][$i], FILTER_VALIDATE_URL) === false) && !filter_var($_POST['to'][$i], FILTER_VALIDATE_URL) === false) {
						//filter_va	
							// Databse Variables.
							$store['from'][] = $_POST['from'][$i];
							$store['to'][] 	 = $_POST['to'][$i];
							
							// Htaccess Variables.
							$from = explode("?", $_POST['from'][$i]);
							$to   = $_POST['to'][$i];
							
							// Remove domain name.
							$request_uri = str_replace("http://10.0.4.4", "", $from[0]);
							$request_uri = str_replace("http://10.0.4.4", "", $request_uri);
							
							// Query Strings.
							$query_str = $from[1];
							
							// .htaccess Code.
							$htaccess .= '
							RewriteCond   %{REQUEST_URI}    ^'.$request_uri.'$
							RewriteCond   %{QUERY_STRING}   ^'.$query_str.'$
							RewriteRule   ^(.*)$ '.$to.' [R=301,L]
							';
						}
			}
			
			// Append Initial file code.
			$htaccess .= '
			#RewriteRule ^([A-Za-z0-9-]+)/?$ brand.php?cname=$1 [NC,L]
			</IfModule>
			';

			file_put_contents('htaccess.db', serialize($store));
			file_put_contents(".htaccess", $htaccess);
		}
		
		
        }
       
        $this->load->view('admin/maintemplate/header');              
        $this->load->view('admin/systemconfig/script');
        $this->load->view('admin/maintemplate/footer');
    }
    
     public function trackingCode() {
        if($this->input->post()){
            // pr($this->input->post()); die;
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('body_code', 'Please enter body code', 'required');
            $this->form_validation->set_rules('head_code', 'Please enter head code', 'required');
            if ($this->form_validation->run() === TRUE) {
                $this->systemconfig_model->updateCode($this->input->post());
                redirect("admin/systemconfiguration/trackingCode");
            }
        }
        $data['tracking_code'] = $this->systemconfig_model->getTrackingCode();
       
        $this->load->view('admin/maintemplate/header');              
        $this->load->view('admin/header_footer/tracking_code', $data);
        $this->load->view('admin/maintemplate/footer');
    }
    
    public function logoText(){
        if($this->input->post()){
            // pr($this->input->post()); die;
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('logo_text1', 'Please enter logo text1', 'required');
            $this->form_validation->set_rules('logo_text2', 'Please enter logo text2', 'required');
            if ($this->form_validation->run() === TRUE) {
                $this->systemconfig_model->updateLogotext($this->input->post());
                redirect("admin/systemconfiguration/logoText");
            }
        }
        $data['logo_text'] = $this->systemconfig_model->getLogotext();
       
        $this->load->view('admin/maintemplate/header');              
        $this->load->view('admin/header_footer/logo_text', $data);
        $this->load->view('admin/maintemplate/footer');
    }
    
    
    public function disclaimer() {
        if($this->input->post()){
            // pr($this->input->post()); die;
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
           
            $this->form_validation->set_rules('disclaimer', 'Please enter disclaimer code', 'required');
           
            if ($this->form_validation->run() === TRUE) {
                $this->systemconfig_model->disclaimer($this->input->post());
                redirect("admin/systemconfiguration/disclaimer");
            }
        }
        $data['disclaimer'] = $this->systemconfig_model->getDisclaimer();
       
        $this->load->view('admin/maintemplate/header');              
        $this->load->view('admin/systemconfig/disclaimer', $data);
        $this->load->view('admin/maintemplate/footer');
    }
    
    
}