<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General_model extends CI_Model {

    public function __construct() {
        
        parent::__construct();
    }

    public function getMonthArray() {
        return array(
            '1' => $this->lang->line('jan'),
            '2' => $this->lang->line('feb'),
            '3' => $this->lang->line('mar'),
            '4' => $this->lang->line('apr'),
            '5' => $this->lang->line('may'),
            '6' => $this->lang->line('jun'),
            '7' => $this->lang->line('jul'),
            '8' => $this->lang->line('aug'),
            '9' => $this->lang->line('sep'),
            '10' => $this->lang->line('oct'),
            '11' => $this->lang->line('nov'),
            '12' => $this->lang->line('dec')
        );
    }

    /**
     * To create post time string
     * @paran time in seconds
     * @return String
     */
    public function createPostTimeString($activity_time) {
        //return $activity_time;
        if ((time() - $activity_time) > 86400) {
            $month_array = $this->getMonthArray();
            return $month_array[date('n', $activity_time)] . date(' d, Y', $activity_time);
        } elseif ((time() - $activity_time) > 3600) {
            return intval((time() - $activity_time) / 3600) . ' ' . $this->lang->line('hr') . ((((time() - $activity_time) / 3600) > 1) ? 's' : '') . ' ' . $this->lang->line('ago');
        } elseif ((time() - $activity_time) > 60) {
            return intval((time() - $activity_time) / 60) . ' ' . $this->lang->line('minute') . ((((time() - $activity_time) / 60) > 1) ? 's' : '') . ' ' . $this->lang->line('ago');
        } else {
            return intval(time() - $activity_time) . ' ' . $this->lang->line('second') . ((((time() - $activity_time)) > 1) ? 's' : '') . ' ' . $this->lang->line('ago');
        }
    }

    /*
     * To create select options for per_page in listing
     * @param string $uri:-uri key
     * @param string $uri_value:-value to given uri
     */

    public function urlUriHistory($action, $uri, $uri_value) {
        $uri_array = $this->uri->uri_to_assoc(4);
        $uri_string = '';
        foreach ($uri_array as $key => $value) {
            if ($key != $uri) {
                $uri_string.='/' . $key . '/' . $value;
            }
        }
        $given_uri = ($uri_value !== NULL) ? ('/' . $uri . '/' . $uri_value) : '';
        return site_url($action) . $given_uri . $uri_string;
    }

    /*
     * To create select options for per_page in listing
     * @param string $action:-controller/method
     * @param array $arr_options:-otions as you want in selectbox
     */

    public function perPageURL($action, $arr_options, $totalrows=NULL) {
        $uri_array = $this->uri->uri_to_assoc(4);
        $uri_string = '';
        $url = array();
        foreach ($uri_array as $key => $value) {
            if (($key != 'page') && $key != 'per_page') {
                $uri_string.='/' . $key . '/' . $value;
            }
        }
        foreach ($arr_options as $option) {
            if ($option == 'select') {
                $url['select'] = 'select';
            }
           else if($option == 'All'){
                if (in_array($totalrows, $arr_options)) {
                    $url[site_url($action) . '/per_page/' . ($totalrows + 1) . $uri_string] = 'ALL';
                } else {
                    $url[site_url($action) . '/per_page/' . $totalrows . $uri_string] = 'ALL';
                } 
           }else {
                $url[site_url($action) . '/per_page/' . $option . $uri_string] = $option;
            }
        }
        return $url;
    }

    /*
     * To create sorting url for lists
     * @param string $action:-controller/method
     * @param string $title:-title as display
     * @param string $title:-as in DB colunm name 
     * @param array $attributes:-an optional param for attributes
     */

    public function sortUrl($action, $title, $orderby, $attributes = array()) {
        $sorting_url = $action;
        $uri_array = $this->uri->uri_to_assoc(4); //because we are using admin in sub-directory so 4
        unset($uri_array['page']);
        if (array_key_exists('orderby', $uri_array) && ($orderby == $uri_array['orderby'])) {
            $attributes['class'] = (array_key_exists('class', $attributes) ? trim($attributes['class']) : '') . ' sortinglink clearfix ';
            if (array_key_exists('order', $uri_array) && ($uri_array['order'] == 'desc')) {
                $attributes['class'] = $attributes['class'] . 'desc';
            } else {
                $attributes['class'] = $attributes['class'] . 'asc';
            }
        }
        $uri_array['order'] = array_key_exists('order', $uri_array) ? (($uri_array['order'] == 'asc') ? 'desc' : 'asc') : 'asc';
        $uri_array['orderby'] = $orderby;
        if (array_key_exists('search', $uri_array) && !trim($uri_array['search'])) {
            unset($uri_array['search']);
        }
        foreach ($uri_array as $uri_key => $uri_value) {
            if (trim($uri_value)) {
                $sorting_url.='/' . $uri_key . '/' . $uri_value;
            }
        }
        return anchor($sorting_url, '<span>' . $title . '</span><i></i>', $attributes);
    }
    
    public function get_uri_segment($str=NULL)
    {
        $curnt=current_url();
         $site_url=  base_url();
         $return=str_replace($site_url,"",$curnt);
         $explode=explode("/",$return);
        
        return array_search($str,$explode);
       
    }

    /*     * *************function to create multi lingual text box ************* */

    public function input_box($name = 0, $postdata) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        $tbl = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                //$tbl .='<tr><td width="30%">';
                $textboxname = $name . '_' . $line->id;
                $textboxvalue = $name . '_' . $line->id;
                $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 required',
                );
                $tbl.= '<div><span>' . $line->language_name . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_input($data) . '</div>';

                //$tbl.= '<img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '">';
                $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
            }
        }
        return $tbl;
    }

    /*     * *************function to create multi lingual edit text box ************* */

    public function input_editbox($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                //$tbl .='<tr><td width="30%">';
                $textboxname = $name . '_' . $line->id;
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 required',
                );

                $tbl.= '<div><span>' . $line->language_name . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_input($data) . '</div>';
                //  $tbl.= form_input($data);
                //$tbl.= '<img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '">';
                $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
                //$tbl .='</tr>';
            }
        }
        //$tbl .='</table>';
        return $tbl;
    }

    /*     * *************function to create multi lingual text area ************* */

    public function textarea_box($name = 0, $postdata, $rows = 5, $cols = 5) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                // $tbl .='<tr><td width="30%">';
                $textboxname = $name . '_' . $line->id;
                $textboxvalue = $name . '_' . $line->id;
                $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'rows' => $rows,
                    'cols' => $cols,
                    'class' => 'input-medium-textarea span12',
                );

                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_textarea($data) . '</div>';
                $tbl.='<div class="error">' . form_error($textboxname) . '</div>';
                // $tbl.= form_textarea($data);
                //$tbl.= '</td><td style="padding-top:12px;" align="left"><img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '"></td>';
                //$tbl.= '<td>' . form_error($textboxname) . '</td>';
                //$tbl .='</tr>';
            }
        }

        // $tbl .='</table>';
        return $tbl;
    }

    /*     * *************function to create multi lingual text area box ************* */

    public function input_edittextareabox($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName, $rows = 5, $cols = 5) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                // $tbl .='<tr><td width="30%">';
                $textboxname = $name . '_' . $line->id;
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'cols' => $rows,
                    'rows' => $cols,
                    'class' => 'input-medium-textarea span12',
                );
                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_textarea($data) . '</div>';
                $tbl.='<div class="error">' . form_error($textboxname) . '</div>';
            }
        }
        // $tbl .='</table>';
        return $tbl;
    }

    /*     * *************function to validate form ************* */

    public function form_validation($fieldname = 0, $msg = 0, $rule = 0) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $valname = $fieldname . '_' . $line->id;
                $message = $msg . " in " . stripslashes($line->language_name);
                $tbl.= $this->form_validation->set_rules($valname, $message, $rule);
            }
        }
        return $tbl;
    }

    /*     * *************function to return data ************* */

    public function return_data($fieldname = 0) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $valname = $fieldname . '_' . $line->id;
                $value = $this->input->post($valname);
                $data[$valname] = $value;
            }
        }
        return $data;
    }

    /*     * *************function to fetch single value from table ************* */

    function fetchValue($table, $field, $where) {
        $result = "";
        $this->db->select($field);
        $this->db->where($where);
        $query = $this->db->get($table);
        foreach ($query->result() as $value) {
            $result = stripslashes($value->$field);
        }
        return $result;
    }

    function getActiveLanguageArray() {
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $this->db->select(array('id','language_name'));
        $query = $this->db->get(TBL_LANGUAGE);
        $rset = $query->result();
        foreach ($rset as $row) {
            $arr[$row->id] = $row->language_name;
        }
        return $arr;
    }

    /*     * *************function to view data ************* */

    public function view_data($id = 0, $tablename = 0, $fieldname, $tableIdName) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        $tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $tbl .='<tr>';
                $value = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                //$tbl.= '<td style="padding-left:0px;" align="left"><img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '">&nbsp;&nbsp;' . stripslashes($value) . '</td>';
                $tbl.= '<td style="padding-left:0px;" align="left">&nbsp;&nbsp;' . stripslashes($value) . '</td>';
                $tbl.= '<td>' . form_error($textboxname) . '</td>';
                $tbl .='</tr>';
            }
        }
        $tbl .='</table>';
        return $tbl;
    }

    /*     * *************function to check view permission ************* */

//    function checkViewPermission($pageName = "") {
//        $result = $this->login_model->checkSession();
//        if ($result) {
//            $this->db->where("menuUrl", $pageName);
//            $query = $this->db->get(TBL_MENU);
//            if ($query->num_rows() > 0) {
//                foreach ($query->result() as $line) {
//                    $menuId = $line->menuId;
//                    $this->db->where("menuid", $menuId);
//                    $this->db->where("adminLevelId", $this->session->userdata(SITE_SESSION_NAME.'USERLEVELID'));
//                    $query1 = $this->db->get(TBL_ADMINPERMISSION);
//                    if ($query1->num_rows() > 0) {
//                        return true;
//                    } else {
//                        redirect('admin/adminarea');
//                        return false;
//                    }
//                }
//            } else {
//                redirect('admin/adminarea');
//                return false;
//            }
//        }
//    }

    /*     * *************function to check add permission ************* */

//    function checkAddPermission($pageName = "") {
//        $result = $this->login_model->checkSession();
//        if ($result) {
//            $this->db->where("menuUrl", $pageName);
//            $query = $this->db->get(TBL_MENU);
//            if ($query->num_rows() > 0) {
//                foreach ($query->result() as $line) {
//                    $menuId = $line->menuId;
//                    $this->db->where("menuid", $menuId);
//                    $this->db->where("adminLevelId", $this->session->userdata(SITE_SESSION_NAME.'USERLEVELID'));
//                    $query1 = $this->db->get(TBL_ADMINPERMISSION);
//                    if ($query1->num_rows() > 0) {
//                        foreach ($query1->result() as $line_per) {
//                            if ($line_per->add_record == 1) {
//                                return true;
//                            } else {
//                                return false;
//                            }
//                        }
//                    } else {
//                        return false;
//                    }
//                }
//            } else {
//                return false;
//            }
//        }
//    }

    /*     * *************function to check edit permission ************* */

//    function checkEditPermission($pageName = "") {
//        $result = $this->login_model->checkSession();
//        if ($result) {
//            $this->db->where("menuUrl", $pageName);
//            $query = $this->db->get(TBL_MENU);
//            if ($query->num_rows() > 0) {
//                foreach ($query->result() as $line) {
//                    $menuId = $line->menuId;
//                    $this->db->where("menuid", $menuId);
//                    $this->db->where("adminLevelId", $this->session->userdata(SITE_SESSION_NAME.'USERLEVELID'));
//                    $query1 = $this->db->get(TBL_ADMINPERMISSION);
//                    //echo $this->db->last_query();
//                    if ($query1->num_rows() > 0) {
//                        foreach ($query1->result() as $line_per) {
//                            if ($line_per->edit_record == 1) {
//                                return true;
//                            } else {
//                                return false;
//                            }
//                        }
//                    } else {
//                        return false;
//                    }
//                }
//            } else {
//                return false;
//            }
//        }
//    }

    ////////////////////// Function to get multilingual text area ///////////////////////////

    public function textarea_box_richtext($name = 0, $postdata, $width = 700, $height = 400) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $textboxname = $name . '_' . $line->id;
                $textboxvalue = $name . '_' . $line->id;
                $value = $postdata[$textboxvalue];
                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.='<textarea id="' . $textboxname . '" name="' . $textboxname . '" class="" rows="5">' . stripslashes($value) . '</textarea>';
                $tbl.=form_error($textboxname);
                $tbl.='<script type="text/javascript"> CKEDITOR.replace( "' . $textboxname . '"); </script>	';
            }
        }
        return $tbl;
    }

    ////////////////////// Function to get multilingual text area for edit ///////////////////////////

    public function input_edittextareabox_richtext($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName, $width = 700, $height = 400) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $textboxname = $name . '_' . $line->id;
                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                $tbl.='<textarea id="' . $textboxname . '" name="' . $textboxname . '" class="" rows="5">' . stripslashes($textboxvalue) . '</textarea>';
                $tbl.=form_error($textboxname);
                $tbl.='<script type="text/javascript"> CKEDITOR.replace( "' . $textboxname . '"); </script>	';
            }
        }
        return $tbl;
    }

    /*     * *************function to get delete permission ************* */

    function checkDeletePermission($pageName = "") {
        $result = $this->login_model->checkSession();
        if ($result) {
            $this->db->where("menuUrl", $pageName);
            $query = $this->db->get(TBL_MENU);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $line) {
                    $menuId = $line->menuId;
                    $this->db->where("menuid", $menuId);
                    $this->db->where("adminLevelId", $this->session->userdata(SITE_SESSION_NAME.'USERLEVELID'));
                    $query1 = $this->db->get(TBL_ADMINPERMISSION);
                    if ($query1->num_rows() > 0) {
                        foreach ($query1->result() as $line_per) {
                            if ($line_per->delete_record == 1) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }

    /*     * ************** Function to get image ratio ***************** */

    function getImageRatio($ratioW, $ratioH, $imgPath) 
    {
        $imageRatio = '';
        
        list($width, $height) = @(getimagesize($imgPath));
        
        if ($width > $ratioW && $height > $ratioH) {
            $imageRatio = $ratioW . 'x' . $ratioH;
        } 
        else if ($width < $ratioW && $height > $ratioH) {
            $imageRatio = 'x' . $ratioH;
        } 
        else if ($width > $ratioW && $height < $ratioH) {
            $imageRatio = $ratioW . 'x';
        } 
        else {
            $imageRatio = $width . 'x' . $height;
        }
        
        return $imageRatio;
    }

    /* Fucntion to get the getegory array to populate in drupdown or anywhere else
      @Arg2 :
     */

    function getCategoryOptions($catId = 0, &$catArr = array()) {
        // echo '<br>'.$catId.'<br>';
        $this->db->select('cat.id,cat_desc.cat_name,cat.parent_id,cat.parent_path');
        $this->db->from(TBL_BUSINESSCAEGORY . ' as cat');
        $this->db->join(TBL_BUSINESSCAEGORY_DESC . ' as cat_desc', 'cat.id=cat_desc.cat_id');
        $this->db->where(array('cat.deleted_at' => NULL, 'cat_desc.langId' => '1', 'cat.status' => '1', 'cat.parent_id' => $catId));
        $this->db->order_by('cat_desc.cat_name', 'asc');
        $query = $this->db->get();
        //if($catId==6){print_r($query);exit;}
        foreach ($query->result() as $row) {
            $dash = '';
            $arr = explode('-', $row->parent_path);
            if (count($arr) > 2) {
                for ($k = 2; $k < count($arr); $k++)
                    $dash.='--';
            }
            $catArr[$row->id] = $dash . $row->cat_name;
            //if($row->id=='6')exit;
            $this->getCategoryOptions($row->id, $catArr);

            //if($row->id=='6') {print_r($catArr);exit;}
//            $this->db->select('cat.id,cat_desc.cat_name,cat.parent_id');
//            $this->db->from(TBL_BUSINESSCAEGORY.' as cat');
//            $this->db->join(TBL_BUSINESSCAEGORY_DESC.' as cat_desc','cat.id=cat_desc.cat_id');
//            $this->db->where( array('cat.deleted_at' => NULL,'cat_desc.langId'=>'1','cat.status'=>'1','cat.parent_id'=>$row->id));
//            $this->db->order_by('cat_desc.cat_name','asc');
//            $innerQuery=$this->db->get();
//              if($innerQuery){
//                  foreach($innerQuery->result() as $innerrow){//print_r
//                      $catArr[$innerrow->id]=$innerrow->cat_name;
//                      $this->getCategoryOptions($innerrow->id, $catArr);
//                  }
//              }  
        }
        return $catArr;
    }

    function checkMailTemplatePermission($mailTempId, $userId = '') {
        $this->db->select('etemp.mail_cat_id,maticat.is_compulsory');
        $this->db->join(TBL_EMAIL_TEMPLATES . ' as etemp', 'etemp.mail_cat_id =maticat.id');
        $row = $this->db->get_where(TBL_MAILCATEGORY . ' as maticat', array('etemp.id' => $mailTempId))->row();
        if ($row) {
            if ($row->is_compulsory)
                return true;
            else {
                if (1 == 2) { // This is the condition where we will check the user accoutn setting
                    return true;
                } else
                    return true;
            }
        }
        return false;
    }

    function getSubCategoriesOptions($catId) {
        $this->db->select('cat.id,cat_desc.cat_name');
        $this->db->from(TBL_BUSINESSCAEGORY . ' as cat');
        $this->db->join(TBL_BUSINESSCAEGORY_DESC . ' as cat_desc', 'cat.id=cat_desc.cat_id');
        $this->db->where(array('cat.deleted_at' => NULL, 'cat_desc.langId' => '1', 'cat.status' => '1', 'cat.parent_id' => '0'));
        $this->db->order_by('cat_desc.cat_name', 'asc');
        $query = $this->db->get();
    }

    function checkFacebookAutopost() {
        $this->db->select('facebook_autopost_id');
        $this->db->from(TBL_USERS);
        $this->db->where(array('id' => $this->session->userdata('id')));
        $row = $this->db->get()->row();
        if ($row) {
            if (trim($row->facebook_autopost_id))
                return true;
        }
        return false;
    }

    function checkTwitterAutoPost() {
        $this->db->select('twitter_oauth_token');
        $this->db->from(TBL_USERS);
        $this->db->where(array('id' => $this->session->userdata('id')));
        $row = $this->db->get()->row();
        if ($row) {
            if (trim($row->twitter_oauth_token))
                return true;
        }
        return false;
    }

    function postOnFacebook($id, $type,$data=array()) {
        $this->db->select('facebook_autopost_id,facebook_token');
        $this->db->from(TBL_USERS);
        $this->db->where(array('id' => $this->session->userdata('id')));
        $row = $this->db->get()->row();
       // print_r($row);exit;
        if (trim($row->facebook_autopost_id)) {
            if($type==='business_review'){
                $data = $this->getReviewDataToPost($id);
            }
           // $data=Array ('message' => 'Test facebook auto post', 'description' => 'dummy','link' => 'http://norefresh.thesparxitsolutions.com/MELPEL1022/en/business/details/dummy1415257242','picture' => 'http://norefresh.thesparxitsolutions.com/MELPEL1022/files/business/images/thumb/led2.jpg' ) ;
           // print_r($data);exit;
            $url = 'https://graph.facebook.com/' . $row->facebook_autopost_id . '/feed';
            $fields = 'message=' . $data['message']
                    . '&description=' . $data['description']
                    . '&link=' . $data['link']
                    . '&picture=' . $data['picture']
                    . '&method=post&access_token=' . $row->facebook_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_POST, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);
            //echo '<pre>';            print_r($result); exit;
            if (isset($result->error)) {
                $this->session->set_flashdata('error', $this->lang->line('can_not_post_on_facebook') . ' "' . $result->error->message . '"');
                // redirect('account/externalapplication');
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('can_not_post_on_facebook'));
             //redirect('account/externalapplication');
        }
    }

    function postOnTwitter($id, $type,$data=array()) {
        $this->db->select('twitter_oauth_token,twitter_oauth_token_secret,twitter_user_id,twitter_screen_name');
        $this->db->from(TBL_USERS);
        $this->db->where(array('id' => $this->session->userdata('id')));
        $row = $this->db->get()->row();
        if (trim($row->twitter_oauth_token)) {
            if($type==='business_review'){
                $data = $this->getReviewDataToPost($id);
            }
            $access_token = array(
                'oauth_token' => $row->twitter_oauth_token,
                'oauth_token_secret' => $row->twitter_oauth_token_secret,
                'user_id' => $row->twitter_user_id,
                'screen_name' => $row->twitter_screen_name
            );
            $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $row->twitter_oauth_token, $row->twitter_oauth_token_secret);
            $res = $connection->post('statuses/update', array('status' => $data['message'] . ' ' . $data['link'])
            );
            if (isset($res->errors)) {
                $this->session->set_flashdata('error', $this->lang->line('can_not_post_on_twitter') . '"' . $res->errors['0']->message . '"');
               // redirect('account/externalapplication');
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('can_not_post_on_twitter'));
            //redirect('account/externalapplication');
        }
    }

    function getReviewDataToPost($id) {
        $this->db->select('breview.content,bs.title,bs.url');
        $this->db->from(TBL_BUSINESSES_REVIEWS . ' as breview');
        $this->db->join(TBL_BUSINESSES . ' as bs', 'breview.business_id=bs.id');
        $this->db->where(array('breview.id' => $id));
        $row = $this->db->get()->row();
        $data['message'] = $row->content;
        $data['description'] = $row->title;
        $data['link'] = site_url('business/details/' . $row->url);
        $data['picture'] = 'http://norefresh.thesparxitsolutions.com/MELPEL1022/assets/images/img-06.jpg';
        return $data;
    }
    function userInformation($userIds){
       // echo $userIds;exit;
        $query="select tu.sex,tu.avatar,tu.id,tu.f_name,tu.l_name,tu.nickname,tu.userUniqueId,tfcount.*,count(*) as reviewcount from (
select uid,sum(fcount) as friendcount from (
select tt.uid as uid,tt.friend_count as fcount from 
(select tf.receiver_id as uid,count(*) friend_count from ".TBL_USERS." tu join ".TBL_FRIEND_REQUEST." tf on tu.id=tf.sender_id where tf.receiver_id in($userIds) and tu.deleted_at is null and tu.status='1' and tu.email_validated='1' group by tf.receiver_id) as tt
union
select tt.uid as uid,tt.friend_count as fcount from (select tf.sender_id as uid,count(*) friend_count from ".TBL_USERS." tu join ".TBL_FRIEND_REQUEST." tf on tu.id=tf.receiver_id  where tf.sender_id in ($userIds) and  tu.deleted_at is null and tu.status='1' and tu.email_validated='1' group by tf.sender_id ) as tt 
) as temp group by uid) as tfcount 
right join true_users tu on tu.id=tfcount.uid left join  ".TBL_BUSINESSES_REVIEWS." as tbr on tu.id=tbr.user_id  where tu.status='1' and tu.id in ($userIds) group by tu.id
        ";
        $data=array();
        
        $rst=$this->db->query($query);
        foreach($rst->result() as $row){
            $data[$row->id]=$row;
        }
       //print_r($data);exit;
       return $data;
                
    }
    
    
    public function fetch_reviews($id,$querydata=array(),$is_count=false){
        
        //print_r($querydata['search']);
       $this->db->select('br.id,br.business_id,br.user_id,br.ratings,br.content,br.status,br.created_at,br.updated_at,br.status_updated_by,br.status_updated_by_id,sum(funny) as funny,sum(cool) as cool, sum(useful) as useful');
       $this->db->from(TBL_BUSINESSES_REVIEWS.' as br');
       $this->db->join(TBL_REVIEW_VOTES.' AS vote','br.id=vote.review_id','left');
       $this->db->where('br.business_id',$id);
       if (isset($querydata['search']) && trim($querydata['search'])) {
           $this->db->like('br.content',$querydata['search']); 
       }
       $this->db->group_by('br.id');
       $rst=$this->db ->get();
       //echo $this->db->last_query();
       if($is_count)
        return $rst->num_rows;
       $data=array();
       foreach($rst->result() as $row){
           if(isset($data['userIds']))
           $data['userIds']=$data['userIds'].','.$row->user_id;
           else
               $data['userIds']=$row->user_id;
           $data['data'][]=$row;
       }
       
         //$this->db->last_query();
      
       return $data;
    }
    public function getCountry($id){
            $langid = $this->lang->langId();
            $this->db->select(TBL_COUNTRY.'.id as id,'.TBL_COUNTRY_DESC.'.country_name AS country');
            $this->db->from(TBL_COUNTRY_DESC);
            $this->db->join(TBL_COUNTRY, TBL_COUNTRY.'.id = '.TBL_COUNTRY_DESC.'.country_id');
            $this->db->where(TBL_COUNTRY_DESC.".langId",$langid);
            $this->db->where(TBL_COUNTRY.".deleted_at",NULL);
            $this->db->where(TBL_COUNTRY.".status",'1');
            $query=$this->db->get();
            //echo $this->db->last_query();die;
            $tbl = '';
            if($query->num_rows()>0)
            {
                    $tbl .='<option value="">Please select country</option>';
                    foreach ($query->result() as $value) {
                            if($id==$value->id){
                                    $sel = "selected";
                            }else{
                                    $sel = "";
                            }
                            $tbl .='<option value="'.$value->id.'" '.$sel.'>'.$value->country.'</option>';

                    }
            }else{
                    $tbl .='<option value="">Not any country found!</option>';
            }

            return $tbl;
    }
    
    public function getselectedState($id)
    {       
            $langid = $this->lang->langId();
            $this->db->select(TBL_STATE.'.id as id,'.TBL_STATE_DESC.'.state_name AS state');
            $this->db->from(TBL_STATE_DESC);
            $this->db->join(TBL_STATE, TBL_STATE.'.id = '.TBL_STATE_DESC.'.state_id');
            $this->db->where(TBL_STATE_DESC.".langId",$langid);
            $this->db->where(TBL_STATE.".deleted_at",NULL);
            $this->db->where(TBL_STATE.".status",'1');
            $query=$this->db->get();
            //echo $this->db->last_query();die;
            if($query->num_rows()>0)
            {
                    $tbl ='<select id="state" name="state" onchange="getCity(this.value)"><option value="">Please select state</option>';
                    foreach ($query->result() as $value) {
                            if($id==$value->id){
                                    $sel = "selected";
                            }else{
                                    $sel = "";
                            }
                            $tbl .='<option value="'.$value->id.'" '.$sel.'>'.$value->state.'</option>';

                    }
            }else{
                    $tbl .='<option value="">Not any state found!</option>';
            }
            $tbl.='<select>';
            return $tbl;
    }
    public function getselectedCity($stateId,$id)
    {       
            $langid = $this->lang->langId();
            $this->db->select(TBL_CITY.'.id as id,'.TBL_CITY_DESC.'.city_name AS city');
            $this->db->from(TBL_CITY_DESC);
            $this->db->join(TBL_CITY, TBL_CITY.'.id = '.TBL_CITY_DESC.'.city_id');
            $this->db->where(TBL_CITY_DESC.".langId",$langid);
            $this->db->where(TBL_CITY.".deleted_at",NULL);
            $this->db->where(TBL_CITY.".status",'1');
            $this->db->where(TBL_CITY.".state_id",$stateId);
            $query=$this->db->get();
            //echo $this->db->last_query();die;
            if($query->num_rows()>0)
            {
                    $tbl ='<select id="city" name="city"><option value="">Please select state</option>';
                    foreach ($query->result() as $value) {
                            if($id==$value->id){
                                    $sel = "selected";
                            }else{
                                    $sel = "";
                            }
                            $tbl .='<option value="'.$value->id.'" '.$sel.'>'.$value->city.'</option>';

                    }
            }else{
                    $tbl .='<option value="">Not any city found!</option>';
            }
            $tbl.='<select>';
            return $tbl;
    }

        function getState($get)
	{
            //print_r($get);die;
                $langid = $this->lang->langId();
                $this->db->select(TBL_STATE.'.id AS id,'.TBL_STATE.'.*,'.TBL_STATE_DESC.'.state_name,'.TBL_COUNTRY_DESC.'.country_name');
		$this->db->from(TBL_STATE_DESC);
		$this->db->join(TBL_STATE, TBL_STATE.'.id = '.TBL_STATE_DESC.'.state_id');
                $this->db->join(TBL_COUNTRY_DESC, TBL_COUNTRY_DESC.'.country_id = '.TBL_STATE.'.country_id');
		$this->db->where(TBL_COUNTRY_DESC.".langId",$langid);
                $this->db->where(TBL_STATE_DESC.".langId",$langid);
                $this->db->where(TBL_STATE.'.country_id',$get['contryId']);
		$this->db->where(TBL_STATE.".deleted_at",NULL);
                $query=$this->db->get();
                //echo $this->db->last_query();die;
                $tbl = '';
		$tbl .='<select name="state" id="state" class="input-medium" onchange="getCity(this.value)">';
		if($query->num_rows()>0)
		{
			$tbl .='<option value="">Please select state</option>';
			foreach ($query->result() as $value) {
					
				$tbl .='<option value="'.$value->id.'">'.$value->state_name.'</option>';
			}
		}else{
			$tbl .='<option value="">Not any state found!</option>';
		}
		$tbl .='</select>';
                
		return $tbl;
	}
        function getCity($get)
	{
                //print_r($get);die;
                $langid = $this->lang->langId();
                $this->db->select(TBL_CITY.'.id AS id,'.TBL_CITY.'.*,'.TBL_CITY_DESC.'.city_name,'.TBL_STATE_DESC.'.state_name');
		$this->db->from(TBL_CITY_DESC);
		$this->db->join(TBL_CITY, TBL_CITY.'.id = '.TBL_CITY_DESC.'.city_id');
                $this->db->join(TBL_STATE_DESC, TBL_STATE_DESC.'.state_id = '.TBL_CITY.'.state_id');
		$this->db->where(TBL_STATE_DESC.".langId",$langid);
                $this->db->where(TBL_CITY_DESC.".langId",$langid);
                $this->db->where(TBL_CITY.'.state_id',$get['stateId']);
		$this->db->where(TBL_CITY.".deleted_at",NULL);
                $query=$this->db->get();
                //echo $this->db->last_query();die;
                $tbl = '';
		$tbl .='<select name="city" id="city" class="input-medium">';
		if($query->num_rows()>0)
		{
			$tbl .='<option value="">Please select city</option>';
			foreach ($query->result() as $value) {
					
				$tbl .='<option value="'.$value->id.'">'.$value->city_name.'</option>';
			}
		}else{
			$tbl .='<option value="">Not any state found!</option>';
		}
		$tbl .='</select>';
                
		return $tbl;
	}

 public function input_editradio($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName,$defaultValue) {
        $tbl = "";
       
       
         $radioname = $name;
                $radiovalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
                $checked=FALSE;
                if($defaultValue==$radiovalue)
                {
                     $checked=TRUE;
                }
                
                $data = array(
                    'name' => $radioname,
                    'id' => $radioname,
                    'value' => $defaultValue,
                    'checked'=>$checked);

                $tbl=  form_radio($data);
                return $tbl;
    }
    
    public function textarea_box_normal($name = 0, $postdata, $rows = 5, $cols = 5) {
        $tbl = "";
        $textboxname = $name ;
                $textboxvalue = $name;
                $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'rows' => $rows,
                    'cols' => $cols,
                    'class' => 'input-medium-textarea span12',
                );

                 $tbl.= '<div>';
                $tbl.=form_textarea($data) . '</div>';
               
                
        return $tbl;
        
    }
    
     public function input_edittextareabox_normal($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName, $rows = 5, $cols = 5) {
        $tbl = "";
        
       
                
                $textboxname = $name;
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'cols' => $rows,
                    'rows' => $cols,
                    'class' => 'input-medium-textarea span12',
                );
                $tbl.= '<div><span></span>';
                $tbl.=form_textarea($data) . '</div>';
                $tbl.='<div class="error">' . form_error($textboxname) . '</div>';
        
        return $tbl;
    }

     public function input_box_normal($name = 0, $postdata) {
        
        $tbl = '';
        $textboxname = $name ;
        $textboxvalue = $name ;
        $value = $postdata[$textboxvalue];
        $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 required',
                );
                $tbl.= '<div>';
               $tbl.=form_input($data) . '</div>';
        $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
         return $tbl;
    }
    
     public function textarea_box_richtext_normal($name = 0, $postdata, $width = 700, $height = 400) {
        $tbl = "";
        
        $value = "";
       
        $textboxname = $name . '_1';
                $textboxvalue = $name . '_1';
                $value = $postdata[$textboxvalue];
                $tbl.= '<div><span></span>';
                
                $tbl.='<textarea id="' . $textboxname . '" name="' . $textboxname . '" class="" rows="5">' . stripslashes($value) . '</textarea>';
                $tbl.=form_error($textboxname);
                $tbl.='<script type="text/javascript"> CKEDITOR.replace( "' . $textboxname . '"); </script>	';    
        
        
        return $tbl;
    }
    
     public function eidttextarea_box_richtext_normal($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName, $width = 700, $height = 400) {
        $tbl = "";
        
        $value = "";
       
        $textboxname = $name . '_1';
        $textboxvalue = $name . '_1';
         $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
   
    $tbl.= '<div><span></span>';

    $tbl.='<textarea id="' . $textboxname . '" name="' . $textboxname . '" class="" rows="5">' . stripslashes($textboxvalue) . '</textarea>';
    $tbl.=form_error($textboxname);
    $tbl.='<script type="text/javascript"> CKEDITOR.replace( "' . $textboxname . '"); </script>	';    
        
        
        return $tbl;
               
               
    }
    public function dropdown_box_normal($name = 0,$arr, $postdata) {
        
        $tbl = '';
       $textboxname = $name ;
                $textboxvalue = $name ;
                $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'class' => 'span12 required',
                );
                $tbl.= '<div>';
               $tbl.=form_dropdown($name,$arr,$value) . '</div>';
        $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
         return $tbl;
    }
    
    public function editdropdown_box_normal($name = 0,$id = 0, $tablename = 0, $fieldname, $tableIdName,$arr) {
        
        $tbl = '';
       $textboxname = $name ;
                 $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
                $value = $textboxvalue;
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'class' => 'span12 required',
                );
                $tbl.= '<div>';
               $tbl.=form_dropdown($name,$arr,$value) . '</div>';
        $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
         return $tbl;
    }
    public function input_editbox_normal($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName) {
        $tbl = "";
        $textboxname = $name ;
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 required',
                );
                $tbl.='<div>'.form_input($data) . '</div>';
                $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
                //$tbl .='</tr>';
        return $tbl;
    }
    
    public function check_box_normal($name = 0, $postdata,$value=NULL) {
        
        $tbl = '';
        $textboxname = $name ;
                $textboxvalue = $name ;
                $checked='';
                if(isset($postdata[$textboxvalue]))
                {
                    $checked=TRUE;
                }
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'checked'=>$checked,
                    
                );
                $tbl.= '<div>';
               $tbl.=form_checkbox($data) . '</div>';
                return $tbl;
    }
    public function edit_check_box_normal($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName,$required_value) {
        $tbl = "";
        $textboxname = $name ;
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id"));
                 $checked='';
                if($textboxvalue==$required_value)
                {
                    $checked=TRUE;
                }
               
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => $required_value,
                    'checked'=>$checked
                );
                $tbl.='<div>'.  form_checkbox($data) . '</div>';
                
        return $tbl;
    }
    
    public function input_box_for_array_type($name = 0, $postdata,$index) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        $tbl = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                //$tbl .='<tr><td width="30%">';
                $textboxname = $name."[".$index."][".$line->id."]";
                $textboxvalue = $name."[".$index."][".$line->id."]";
               $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 required',
                );
                $tbl.= '<div><span>' . $line->language_name . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_input($data) . '</div>';

                //$tbl.= '<img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '">';
                $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
            }
        }
        return $tbl;
    }
    public function textarea_box_for_array_type($name = 0, $postdata,$index, $rows = 5, $cols = 5) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                // $tbl .='<tr><td width="30%">';
               $textboxname = $name."[".$index."][".$line->id."]";
                $textboxvalue = $name."[".$index."][".$line->id."]";
                $value = $postdata[$textboxvalue];
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($value),
                    'rows' => $rows,
                    'cols' => $cols,
                    'class' => 'input-medium-textarea span12',
                );

                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_textarea($data) . '</div>';
                $tbl.='<div class="error">' . form_error($textboxname) . '</div>';
                // $tbl.= form_textarea($data);
                //$tbl.= '</td><td style="padding-top:12px;" align="left"><img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '"></td>';
                //$tbl.= '<td>' . form_error($textboxname) . '</td>';
                //$tbl .='</tr>';
            }
        }

        // $tbl .='</table>';
        return $tbl;
    }
    
    public function input_editbox_for_array_type($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName,$index) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                //$tbl .='<tr><td width="30%">';
               $textboxname = $name."[".$index."][".$line->id."]";
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'maxlength' => '200',
                    'size' => '200',
                    'class' => 'span12 ',
                );

                $tbl.= '<div><span>' . $line->language_name . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_input($data) . '</div>';
                //  $tbl.= form_input($data);
                //$tbl.= '<img src="' . SHOWPATH . 'language/thumb/' . $line->language_flag . '" alt="' . stripslashes($line->language_name) . '" title="' . stripslashes($line->language_name) . '">';
                $tbl.= '<div class="error">' . form_error($textboxname) . '</div>';
                //$tbl .='</tr>';
            }
        }
        //$tbl .='</table>';
        return $tbl;
    }


    public function input_edittextareabox_for_array_type($name = 0, $id = 0, $tablename = 0, $fieldname, $tableIdName, $index,$rows = 5, $cols = 5) {
        $tbl = "";
        $this->db->where("status", '1');
        $this->db->where('deleted_at', NULL);
        $query = $this->db->get(TBL_LANGUAGE);
        $value = "";
        //$tbl = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align:center;" align="center">';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                // $tbl .='<tr><td width="30%">';
                $textboxname = $name."[".$index."][".$line->id."]";
                $textboxvalue = stripslashes($this->fetchValue($tablename, $fieldname, $tableIdName . " = $id and langId = '" . $line->id . "'"));
                $data = array(
                    'name' => $textboxname,
                    'id' => $textboxname,
                    'value' => stripslashes($textboxvalue),
                    'cols' => $rows,
                    'rows' => $cols,
                    'class' => 'input-medium-textarea span12',
                );
                $tbl.= '<div><span>' . $line->language_code . '</span>';
                if (is_file(DIR_MAGE_THUMB . $line->language_flag)) {
                    $tbl.='&nbsp;<img src="' . URL_IMAGE_THUMB . $line->language_flag . '"/>';
                }
                $tbl.=form_textarea($data) . '</div>';
                $tbl.='<div class="error">' . form_error($textboxname) . '</div>';
            }
        }
        // $tbl .='</table>';
        return $tbl;
    }
    public function sendMessage($to, $subject, $message){
        $from = SITE_EMAIL;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Create email headers
	$headers .= 'From: '.$from."\r\n".
			'Reply-To: '.$from."\r\n" .
			'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
    }
}
 

?>