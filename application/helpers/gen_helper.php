<?php

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);   
}


function convertToHoursMins($time, $format = '%02d:%02d') 
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}


/**
 * 
 * @return string
 */
function clean_name($string)
{

   return preg_replace('/[^A-Za-z0-9\- ]/', '', $string); // Removes special chars.
}

/**
 * 
 * @return string
 */
function clean_text($string)
{
   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}

/**
 * Unique code format
 * 
 * @return string
 */
function code($id)
{
    return '#'.str_pad($id, 3, '0', FALSE); 
}


/**
 * Employee code format
 * 
 * @return string
 */
function emp_code($id)
{
    return 'EMP-'.str_pad($id, 3, '0', FALSE); 
}


/**
 * Date format
 * 
 * @return string
 */
function display_date($date = null)
{
    return $date ? date(get_system_config('DATE_FORMAT'), strtotime($date)) : date("d/m/Y");
}


/**
 * Time format
 * 
 * @return string
 */
function display_time($time = null)
{
    return $time ? date("H:i", strtotime($time)) : date("H:i");
}


/**
 * Date/Time format
 * 
 * @return string
 */
function display_datetime($datetime = null)
{
	return $datetime ? date(get_system_config('DATE_FORMAT').", h:i A", gmt_to_local($datetime)) : date("d/m/Y, h:i A");
}


/**
 * Date format for reports
 * 
 * @return string
 */
function reports_date($datetime = null)
{
	return strtoupper(date("D - d/m", strtotime($datetime)));
}


function current_timezone()
{
    $CI = & get_instance();
    
    $CI->load->model('admin/systemconfig_model');

    $tz = $CI->systemconfig_model->getSystemConfigurations('TIMEZONE');

    $obj = $CI->db->get_where(TBL_TIMEZONE, array("timezone" => $tz['TIMEZONE']))->row();
    
    return $obj->timezone;
}



/**
 * Convert GMT to local timezone.
 * 
 * @return string
 */
function gmt_to_local($dt = null)
{
        $datetime = $dt ? $dt : gmdate("Y-m-d H:i:s");

        $CI = & get_instance();

        $CI->load->model('admin/systemconfig_model');

        $tz = $CI->systemconfig_model->getSystemConfigurations('TIMEZONE');

        $obj = $CI->db->get_where(TBL_TIMEZONE, array("timezone" => $tz['TIMEZONE']))->row();

        if($obj->offset != 'GMT') {

                $time_diff = explode(":", str_replace("GMT", "", $obj->offset));

                $symbol = substr($time_diff[0], 0,1);

                if($symbol == '-') {

                        $offset = '-'.  intval(substr($time_diff[0], 1,2)).' hours '.intval($time_diff[1]).' minutes';
                        return strtotime($datetime.' '.$offset);
                }

                if($symbol == '+') {

                        $offset = '+'.  intval(substr($time_diff[0], 1,2)).' hours '.intval($time_diff[1]).' minutes';
                        return strtotime($datetime.' '.$offset);
                }
        }

        return strtotime($datetime);
}


/**
 * Get left navigation menus.
 * 
 * @return array
 */
function left_menu()
{
    $menus = array();
    
    $role_id = role_id();
    
    $CI = & get_instance();
    
    $menus['main_menu'][1] = $CI->db->query("SELECT * FROM ".TBL_PERMISSION." WHERE id=1")->row();
    
    $qry = $CI->db->query("SELECT DISTINCT(a.permission_id), b.* FROM ".TBL_ROLE_PERMISSION." AS a LEFT JOIN ".TBL_PERMISSION." AS b ON(b.id = a.permission_id) WHERE a.role_id='".$role_id."' ORDER BY b.sort ASC");
    
    if($qry->num_rows() > 0) {
        
        foreach($qry->result() as $row){
            
            if($row->parent_id > 0){
                
                $menus['sub_menu'][$row->parent_id][] = $row;
                
                if(! isset($menus['main_menu'][$row->parent_id])) {
                    $menus['main_menu'][$row->parent_id] = $CI->db->query("SELECT * FROM ".TBL_PERMISSION." WHERE id='".$row->parent_id."'")->row();
                }
            }
            else if(! isset($menus['main_menu'][$row->id])) {
                $menus['main_menu'][$row->id] = $row;
            }
        }
    }
    
    ksort($menus['main_menu']);
    
    return $menus;
}


/**
 * Get top node for role permission module.
 * 
 * @return array
 */
function main_menus($role_id)
{
    $CI = & get_instance();
    
    $qry = $CI->db->query("SELECT DISTINCT(a.permission_id),b.* FROM ".TBL_ROLE_PERMISSION." AS a LEFT JOIN ".TBL_PERMISSION." AS b ON(b.id = a.permission_id) WHERE a.role_id='".$role_id."' AND b.parent_id=0 ORDER BY b.sort ASC");
    
    if($qry->num_rows() > 0) {
        return $qry->result();
    }
}


/**
 * Get leaf node for role permission module.
 * 
 * @return array
 */
function sub_menus($role_id, $parent_id)
{
    $CI = & get_instance();
    
    $qry = $CI->db->query("SELECT DISTINCT(a.permission_id), b.* FROM ".TBL_ROLE_PERMISSION." AS a LEFT JOIN ".TBL_PERMISSION." AS b ON(b.id = a.permission_id) WHERE a.role_id='".$role_id."' AND b.parent_id='".$parent_id."' ORDER BY b.sort ASC");
    
    if($qry->num_rows() > 0) {
        return $qry->result();
    }
}


/**
 * Check module permission
 * 
 * @return bool
 */
function has_access($module_name)
{
    $CI = & get_instance();
    
    $role_id = role_id();
    
    $menu = $CI->db->query("SELECT id FROM ".TBL_PERMISSION." WHERE name='".$module_name."'");
    
    $menu_id = $menu->row()->id;    
    
    $qry = $CI->db->query("SELECT b.id, b.name, b.route_url, b.display_name FROM ".TBL_ROLE_PERMISSION." AS a LEFT JOIN ".TBL_PERMISSION." AS b ON(b.id = a.permission_id) WHERE a.role_id='".$role_id."' AND ( b.id='".$menu_id."' OR b.parent_id='".$menu_id."')");
    
    if( ! ($qry->num_rows() > 0)) {
        show_error("You don't have permission to access this page<br/> Please contact your administrator", 403, "Access is Denied");
    }
    else{
        return true;
    }
}


/**
 * Get role id login user.
 * 
 * @return int
 */
function role_id()
{
    $CI = & get_instance();
    return $CI->db->select('role_id')->get_where(TBL_ADMINLOGIN, array('id' => $CI->session->userdata(SITE_SESSION_NAME.'ADMINID')))->row()->role_id;
}






/*
 * Method displayUserName() to display username
 * @param string $nickname
 * @param string $f_name
 * @param string $l_name
 * @return string display name
 */

function displayUserName($nickname, $f_name, $l_name) {
    $display_name = trim($nickname) ? $nickname : ($f_name . ' ' . $l_name);
    return (strlen($display_name) > MAX_ALLOWED_CHARACTERS_IN_USERNAME) ? substr($display_name, 0, MAX_ALLOWED_CHARACTERS_IN_USERNAME) . '...' : $display_name;
}


function addSession($arr = array()) {
    $ci = & get_instance();
    clearSession($arr);
    foreach ($arr as $key => $value) {
        $ci->session->set_userdata($key, $value);
    }
}

function clearSession($arr = array()) {
    $ci = & get_instance();
    // print_r($arr);exit;

    $ci->session->unset_userdata($arr);
}

function removeAllSession() {
    $ci = & get_instance();
    $arr = $ci->session->all_userdata();
    foreach ($arr as $key => $value) {
        $ci->session->unset_userdata($key);
    }

    //$ci->session->unset_userdata($arr);
}

function checkSession($key, $val_to_check = null) {
    $ci = & get_instance();
    $val = $ci->session->userdata($key);
    if ($val_to_check == null) {
        if (!empty($val)) {
            return true;
        }
    } else {

        if (!empty($val) && $val == $val_to_check) {
            return true;
        }
    }

    return false;
}


function get_system_config($var) 
{
    $ci = & get_instance();
    $query = $ci->db->select('systemVal')->from(TBL_SYSTEMCONFIG)->where('systemName', $var)->get();
    $row = $query->row();
    return $row->systemVal;
}


function getYearArray() {
    $current = date('Y', time());
    $start = date('Y', time()) - 100;
    $year_arr = array();

    while ($start <= $current) {
        $year_arr[$start] = $start;
        $start++;
    }
    return array_reverse($year_arr, TRUE);
}


function pr($printr) {
    echo "<pre>";
    print_r($printr);
    echo "</pre>";
}

function base64_url_encode($input) {
    return strtr(rtrim(base64_encode($input), '='), '+/', '-_');
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}


function emp_name($id)
{
    $ci = & get_instance();
    $sql = $ci->db->query("SELECT emp_name FROM " . TBL_EMPLOYEE . " WHERE id IN($id) LIMIT 1");
    
    if($sql->num_rows() > 0){
        
        return $sql->row()->emp_name;
    }
}


function vehicle_name($id)
{
    $ci = & get_instance();
    $sql = $ci->db->query("SELECT regn_number,model FROM " . TBL_VEHICLE . " WHERE id IN($id) LIMIT 1");
    
    if($sql->num_rows() > 0){
        
        $row = $sql->row();
        
        return $row->regn_number;
    }
}


function emp_name_from_ids($ids)
{
    $temp_ids = explode(',', $ids);
    $data = "";

    for ($i = 0; $i < count($temp_ids); $i++)
        {
         $data.= "'" . $temp_ids[$i] . "',";
        }

    $final_ids = rtrim($data, ",");
    $ci = & get_instance();
    $sql = $ci->db->query("SELECT emp_name,id FROM " . TBL_EMPLOYEE . " where id IN($final_ids) and deleted_at IS NULL");
    $result = $sql->result_array();
    $html = "<ul style='list-style-type:none'>";

    for ($i = 0; $i < count($result); $i++)
        {
          $html.= "<li>" . $result[$i]['emp_name']." &nbsp( ".emp_code($result[$i]['id']) ." ) ". "</li>";
        }

    return $html.= "</ul>";

}

function vehicle_name_from_ids($ids)
{
    $temp_ids = explode(',', $ids);
    $data = "";

    for ($i = 0; $i < count($temp_ids); $i++)
        {
         $data.= "'" . $temp_ids[$i] . "',";
        }

    $final_ids = rtrim($data, ",");
    $ci = & get_instance();
    $sql = $ci->db->query("SELECT regn_number,model FROM " . TBL_VEHICLE . " where id IN($final_ids) and deleted_at IS NULL");
    $result = $sql->result_array();
    $html = "<ul style='list-style-type:none'>";

    for ($i = 0; $i < count($result); $i++)
        {
         $html.= "<li>" . $result[$i]['regn_number']." &nbsp( ".$result[$i]['model'] ." ) ". "</li>";
        }

    return $html.= "</ul>";

}

?>
