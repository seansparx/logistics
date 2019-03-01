<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("session");
    }

    /*************** Start function getMenuDetail() to get menu details **************/

    function getMenuDetail() {
        $this->db->where("adminLevelId", $this->session->userdata(SITE_SESSION_NAME.'USERLEVELID'));
        $query = $this->db->get(TBL_ADMINPERMISSION);
        if ($query->num_rows() > 0) {
            $adminPermissionArr = array();
            foreach ($query->result() as $line) {
                array_push($adminPermissionArr, $line->menuid);
            }
            return $adminPermissionArr;
        } else {
            return 0;
        }
    }
    
    /*************** End function getMenuDetail() ****************/

    /*************** Start function displayMenu_new() to display menu **************/

    function displayMenu_new() {
        
        if (!$this->session->userdata(SITE_SESSION_NAME.'ADMINTBL_USERHASH')):
            $this->session->set_flashdata('flashdata', 'OOPS! your session has been expired!');
            return false;
        endif;
        $adminMenuID = $this->getMenuDetail();
        $cond = array(
            'parentId' => '0',
            'status' => '1',
        );
        $this->db->order_by("menuId");
        $this->db->where($cond);
        $this->db->where_in('menuId', $adminMenuID);
        $query = $this->db->get(TBL_MENU);

        $i = 1;
        $cururl = $this->uri->segment(1,'');
        $currentPage=$cururl.".php";
        $parentMenu=  $this->general_model->fetchValue(TBL_MENU,"parentId","menuUrl LIKE '%".$currentPage."%'");
        foreach ($query->result() as $rowMenu) {
            if ($i == 1) {
                $menuurl = explode('.', $rowMenu->menuUrl);
                $properety = array(
                    'title' => $rowMenu->menuName
                );
                if($rowMenu->menuId == $parentMenu){
                    $class="active";
                }else{
                    $class="";
                }
                $table.='<li class="hasSubmenu '.trim($class).'">
                                <a href="'.site_url('admin/'.$menuurl[0]).'"  class="'.$rowMenu->menuClass.'"><i></i><span>'.$rowMenu->menuName.'</span></a></li>';
            } else {

                if($rowMenu->menuId == $parentMenu){
                    $class="active";
                }else{
                    $class="";
                }
                
                $menuurl = explode('.', $rowMenu->menuUrl);
                $properety = array(
                    'class' => 'subexpandable',
                    'title' => $rowMenu->menuName
                );
                $table.='<li class="hasSubmenu '.trim($class).'">
                                <a href="#menu_components'.$rowMenu->menuId.'" data-toggle="collapse" class="'.$rowMenu->menuClass.'"><i></i><span>'.$rowMenu->menuName.'</span></a>';
                $cond1 = array(
                    'parentId' => $rowMenu->menuId,
                    'status' => '1',
                );
                $this->db->order_by("menuId");
                $this->db->where($cond1);
                $this->db->where_in('menuId', $adminMenuID);
                $query1 = $this->db->get(TBL_MENU);
                if ($query1->num_rows() > 0) {
                    $table .= '<ul class="collapse in" id="menu_components'.$rowMenu->menuId.'">';
                    foreach ($query1->result() as $rowSubMenu) {
                        if ($rowSubMenu->menuUrl == "clean.php" || $rowSubMenu->menuUrl == "restore.php") {
                            $submenuurl = explode('.', $rowSubMenu->menuUrl);
                            $table.='<li class="hasSubmenu"><a href="'.site_url('admin/'.$submenuurl[0]).'" />'.$rowSubMenu->menuName.'</a></li>';
                        } else {
                            $submenuurl = explode('.', $rowSubMenu->menuUrl);
                            $cururl = $this->curPageURL();
                            if (strpos($cururl, $submenuurl[0])) {
                                $class="active-submenu";                                
                            } else {
                                $class="";                                
                            }
                            $table.='<li class="hasSubmenu '.$class.'"><a href="'.site_url('admin/'.$submenuurl[0]).'" />'.$rowSubMenu->menuName.'</a></li>';
                        }
                    }
                    $table .= '</ul>';
                } // end of if
                $table .= '</li>';
            }
            $i++;
        }  // END OF WHILE
        $table .= '';
        return $table;
    }
    
    /**************** End function displayMenu_new() *****************/

    /*************** Start function curPageURL() to get current page url **************/

    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    
    /*************** End Function curPageURL() *****************/

    /*************** Start function allrecord() to get all menu **************/

    public function allrecord() {

        $page = $this->uri->segment(3, 0);
        $start = 0;
        $config['base_url'] = site_url("admin/managemenu/index");
        $config['per_page'] = $this->uri->segment(4, 0) ? $this->uri->segment(4, 0) : 10;
        $config['uri_segment'] = 3;

        $query = $this->db->get(TBL_MENU);
        $rows = $query->num_rows();
        $config['total_rows'] = $rows;
        if ($query->num_rows() > 0) {
            $i = 1;
            if ($page > 0 && $page < $config['total_rows'])
                $start = $page;
            if ($this->input->post('name')) {
                $this->db->like('menuName', $this->input->post('name'));
            }
            $this->db->limit($config['per_page'], $start);

            $orderby = $this->uri->segment(5, 0) ? $this->uri->segment(5, 0) : "menuId";
            $order = $this->uri->segment(6, 0) ? $this->uri->segment(6, 0) : "ASC";
            $this->db->order_by($orderby, $order);
            $query1 = $this->db->get(TBL_MENU);
            $numrows = $query1->num_rows();
            $table = "<input type='hidden' name='baseurlval' id='baseurlval' value='" . base_url() . "'>";
            foreach ($query1->result() as $line) {
                $highlight = $i % 2 == 0 ? "bglingtGray" : "";
                $div_id = "status" . $line->menuId;
                if ($line->status == 1) {
                    $status = '<img src="' . base_url() . 'images/eye.png" alt="Inactive" border="0" style="background-repeat:no-repeat; cursor:pointer;" title="Inactive">';
                } else {
                    $status = '<img src="' . base_url() . 'images/eye-disabled.png" alt="Active" border="0" style="background-repeat:no-repeat; cursor:pointer;" title="Active">';
                }
                $isDefault = "";
                $onclickstatus = ' onClick="javascript:changeStatus(\'' . $div_id . '\',\'' . $line->menuId . '\',\'menu\')"';
                $chkbox = '<input name="chk[]" value="' . $line->menuId . '" type="checkbox" class="checkbox">';
                $delete = "<a href='javascript:void(NULL);'  onClick=\"if(confirm('Are you sure to delete this Record  ?')){window.location.href= '" .site_url('admin/managemenu/delete') . $line->menuId . "'}else{}\" >";
                $delete .= '<img src="' . base_url() . 'images/drop.png" alt="" border="0" style="cursor:pointer;" title="Delete"></a>';
                if ($line->menuImage != "") {
                    $menuimage = '<img src="' . SHOWPATH . 'menu/thumb/' . $line->menuImage . '" alt="" border="0">';
                } else {
                    $menuimage = "--";
                }

                $edit = '<a title=" Edit " href="' . site_url('admin/managemenu/edit/' . $line->menuId ). '"><img border="0" title="Edit" alt="" src="' . base_url() . 'images/edit.png"></a>';

                if ($this->general_model->checkEditPermission("managemenu.php")) {
                    $edit = $edit;
                } else {
                    $edit = "--";
                }
                if ($this->general_model->checkDeletePermission("managemenu.php")) {
                    $delete = $delete;
                } else {
                    $delete = "--";
                }

                $table .= '<tr  class="' . $highlight . '">
			        <td align="right" valign="middle" style="padding-right:5px;">' . $chkbox . '</td>
					<td align="right" valign="middle" style="padding-right:5px;">' . $i . '</td>
					<td align="left" valign="middle" style="padding-left:10px;">' . $line->menuName . '</td>
					<td align="left" valign="middle" style="padding-left:10px;">' . $menuimage . '</td>					
				    <td align="center"><div id=' . $div_id . ' ' . $onclickstatus . '>' . $status . '</div></td>				
					<td align="left" valign="middle" style="padding-left:10px;"><a id="example2" title=" View " href="' . site_url('admin/managemenu/view/' . $line->menuId ). '"><img border="0" title="View" alt="" src="' . base_url() . 'images/view.png"></a></td>			
					<td align="center">' . $edit . '</td>				
					<td align="center">' . $delete . '</td>					
				   </tr>';
                $i++;
            }
            $this->pagination->initialize($config);
            $table .= '<tr>
	                   <td align="right" valign="middle" colspan="3" style="padding-right:5px; border-right:0px;">Total ' . $rows . ' records found.</td>	                   
	                   <td align="right" valign="middle" colspan="2" style="padding-right:5px; border-right:0px; border-left:0px;">
                       <input type="hidden" name="pagename" id="pagename" value=' . $config[base_url] . '>
	                      Display <select name="limit" id="limit" onchange="pagelimit(this.value);" style="width:50px;">
					<option value="10" ' . $sel1 . '>10</option>
					<option value="20" ' . $sel2 . '>20</option>
					<option value="30" ' . $sel3 . '>30</option> 
					<option value=' . $rows . ' ' . $sel4 . '>All</option>  
					  </select> Records Per Page
	                    
	                    </td>
	                  
	                   <td align="right"  colspan="3" style="text-align:right; font-size:14px; border-left:0px;">' . $this->pagination->create_links() . '</td>
	                   </tr>';
        } else {
            $table = '<tr class="bglingtGray">
					<td align="right" valign="middle" style="padding-right:5px; color:#CC0000" colspan="8">Sorry not record found!</td>
					</tr>';
        }
        return $table;
    }

    /*************** End function allrecord() ****************/
    
    /*************** Start function changStatus() to change status of menu **************/

    public function changStatus($get) {
        $this->db->where('menuId', $get['id']);
        $query = $this->db->get(TBL_MENU);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $value) {
                $status = $value->status;
            }
        }
        if ($status == '1') {
            $stat = '0';
            $status1 = '<img src="' . base_url() . 'images/eye-disabled.png" alt="Active" title="Active" style="cursor:pointer;">';
        } else {
            $stat = '1';
            $status1 = '<img src="' . base_url() . 'images/eye.png" alt="InActive" title="InActive" style="cursor:pointer;">';
        }
        $this->db->where('menuId', $get['id']);
        $postarray = array(
            'status' => $stat
        );
        $this->db->update(TBL_MENU, $postarray);
        return $status1;
    }

    /*************** End function changStatus() ************/
    
    /*************** Start function deleteRecord() to delete menu **************/

    public function deleteRecord($get) {
        $this->db->where('menuId', $get['id']);
        $this->db->delete(TBL_MENU);
        $this->session->set_flashdata('success', 'Your information has been deleted successfully.');
        redirect(site_url('admin/managemenu/index'));
        return true;
    }
    
    /*************** End Function deleteRecord() **************/

    /*************** Start function deleteAllRecord() to delete all menu **************/

    public function deleteAllRecord() {
        $this->db->delete(TBL_MENU);
        $this->session->set_flashdata('success', 'All data has been deleted successfully.');
        return true;
    }

    /**************** End function deleteAllRecord() **************/
    
    /*************** Start function getrecord() to get menu details from menu id **************/

    public function getrecord($id) {
        $result = array();
        $this->db->select('*');
        $this->db->from(TBL_MENU);
        $this->db->where('menuId', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $value) {
                $result = $value;
            }
        }
        return $result;
    }
    
    /*************** End function getrecord() *************/

    /*************** Start function addrecords() to add new menu **************/

    public function addrecords($post, $file, $menu) {
        $menu_name = $post['menu'];
        $menuurl = $post['menuurl'];

        if ($menu) {
            $imgSource = ABSOLUTEPATH . 'menu/original/' . $menu;
            $ThumbImage = ABSOLUTEPATH . 'menu/thumb/' . $menu;
            exec(IMAGEMAGICPATH . " $imgSource -thumbnail 25x25 $ThumbImage");
        }
        $data1 = array(
            'menuName' => addslashes(trim($menu_name)),
            'menuUrl' => addslashes(trim($menuurl)),
            'menuImage' => trim($menu),
            'status' => '1'
        );
        $this->db->insert(TBL_MENU, $data1);
        $inserted_id = $this->db->insert_id();
        if (!$inserted_id) {
            log_message('error', $this->db->_error_message());
            return false;
        } else {
            $this->session->set_flashdata('success', 'Menu has been added successfully.');
            redirect(site_url('admin/managemenu/add'));
            return true;
        }
    }

    /************** End function addrecords() ***************/
    
    /************** Start function editrecords() to edit existing menu **************/

    public function editrecords($post, $file, $menu) {
        $menu_name = $post['menu'];
        $menuurl = $post['menuurl'];
        if ($menu) {
            $imgSource = ABSOLUTEPATH . 'menu/original/' . $menu;
            $ThumbImage = ABSOLUTEPATH . 'menu/thumb/' . $menu;
            exec(IMAGEMAGICPATH . " $imgSource -thumbnail 25x25 $ThumbImage");
            $data = array(
                'menuName' => addslashes(trim($menu_name)),
                'menuUrl' => addslashes(trim($menuurl)),
                'menuImage' => trim($menu),
                'status' => '1'
            );
        } else {
            $data = array(
                'menuName' => addslashes(trim($menu_name)),
                'menuUrl' => addslashes(trim($menuurl)),
                'status' => '1'
            );
        }
        $this->db->where('menuId', $post['id']);
        $this->db->update(TBL_MENU, $data);
        $this->session->set_flashdata('success', 'Menu has been updated successfully.');
        redirect(site_url('admin/managemenu/edit/'.$post['id']));
        return true;
    }
    
    /***************** End function editrecords() ***************/
    
    //By Gaurav
    function main_menu(){        
        
        if (!$this->session->userdata(SITE_SESSION_NAME.'ADMINTBL_USERHASH')):
            $this->session->set_flashdata('flashdata', 'OOPS! your session has been expired!');
            return false;
        endif;
        $adminMenuID = $this->getMenuDetail();
        $cond = array(
            'parentId' => '0',
            'status' => '1',
        );
        $this->db->order_by("menuId");
        $this->db->where($cond);
        $this->db->where_in('menuId', $adminMenuID);
        $query = $this->db->get(TBL_MENU);
        $i = 1;
        $cururl = $this->uri->segment(1,'');
        $currentPage=$cururl.".php";
        $parentMenu=  $this->general_model->fetchValue(TBL_MENU,"parentId","menuUrl LIKE '%".$currentPage."%'");
        $output ='';
        foreach ($query->result() as $rowMenu) {
            if($rowMenu->menuId == $parentMenu){
                $class="active";
            }else{
                $class="";
            }
                
            if($rowMenu->menuId == 1){
                $url = explode('.',$rowMenu->menuUrl);
                $output .= '<li class="'.$class.'"><a  href="'.site_url('admin/'.$url[0]).'" class="'.$rowMenu->menuClass.'"><i></i>'.$rowMenu->menuName.'</a></li>';
            }else{
                $url = explode('.',$rowMenu->menuUrl);
                $output .= '<li class="dropdown dd-1 '.$class.'"><a href="'.site_url('admin/'.$url[0]).'" data-toggle="dropdown" class="'.$rowMenu->menuClass.'"><i></i>'.$rowMenu->menuName.'<span class="caret"></span></a>';
                 $cond = array(
            'status' => '1',
        );
       $this->db->where($cond);
               // $this->db->where_in('parentId', $rowMenu->menuId);
       
                 $this->db->where(array('parentId'=>$rowMenu->menuId));//change made by 430
                 $this->db->where_in('menuId', $adminMenuID);// 
                $this->db->order_by('sort_order','desc');
                $query1 = $this->db->get(TBL_MENU);
                if($this->db->count_all_results()){
                    $output .= '<ul class="dropdown-menu pull-left">';
                    foreach ($query1->result() as $rowMenu1) {
                        $url1 = explode('.',$rowMenu1->menuUrl);
                        $output .= '<li ><a  href="'.site_url('admin/'.$url1[0]).'">'.$rowMenu1->menuName.'</a></li>';
                    }
                    $output .= '</ul>';
                }else{
                    $output .= '</li>';
                }                
            }            
        }
        return $output;
    }
    function getTopmost(){}
}
?>