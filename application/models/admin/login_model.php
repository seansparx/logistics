<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("session");
    }

    /*************** Start function adminLogin() to login ***************/

    function adminLogin($post) {
        $username = $post['username'];
        $password = $post['password'];
        $this->db->where("username", $username);
        $this->db->where("status", '1');
        $query = $this->db->get(TBL_ADMINLOGIN);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $rows) {
                if ($this->validate_password($password, $rows->password)) {
                   
                    $session_id = $this->session->userdata('session_id');
                    $lastLogin = date("d M Y h:i a", strtotime($rows->lastLogin));
                    $newdata = array(
                        SITE_SESSION_NAME.'ADMINID' => $rows->id,
                        SITE_SESSION_NAME.'ADMINNNAME' => $rows->username,
                        SITE_SESSION_NAME.'USERIMAGE' => $rows->userImage,
                        SITE_SESSION_NAME.'ADMINTBL_USERHASH' => $rows->hash,
                        SITE_SESSION_NAME.'USERLEVELID' => $rows->adminLevelId,
                        SITE_SESSION_NAME.'PHPSESSIONID' => $session_id,
                        SITE_SESSION_NAME.'ADMIN_LAST_LOGIN' => $lastLogin,
                        //SITE_SESSION_NAME.'DEFAULTLANGUAGEID' => $this->general_model->fetchValue(TBL_LANGUAGE,"id","isDefault = '1'")
                    );
                    $updatedata = array(
                        'lastLogin' => date('Y-m-d H:i:s'),
                    );
                    $this->db->where('id', $rows->id);
                    $this->db->update(TBL_ADMINLOGIN, $updatedata);
                    $insertdata = array(
                        'sessionId' => $session_id,
                        'adminId' => $rows->id,
                        'ipAddress' => $_SERVER['REMOTE_ADDR'],
                        'signInDateTime' => date('Y-m-d H:i:s'),
                        'signDate' => date('Y-m-d'),
                    );
                    $this->db->insert(TBL_SESSIONDETAIL, $insertdata);
                    $this->session->set_userdata($newdata);
                    return true;
                } else {
                    $this->session->set_flashdata('errordata', 'Login Authentication Failed');
                    return false;
                }
            }
        } else {
            $this->session->set_flashdata('errordata', 'Login Authentication Failed');
            return false;
        }
    }

    /*************** End function adminLogin() ***************/
    
    /*************** Start function validate_password() to validate user password ***************/

    function validate_password($plain, $encrypted) {
        if (!is_null($plain) && !is_null($encrypted)) {
            // split apart the hash / salt
            $stack = explode(':', $encrypted);
            if (sizeof($stack) != 2)
                return false;
            if (md5($stack[1] . $plain) == $stack[0]) {
                return true;
            }
        }
        return false;
    }

    /*************** End function validate_password() ***************/
    
    /*************** Start function curPageURL() to get current page url ***************/

    function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
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

    /*************** End function curPageUrl() ***************/
    
    /*************** Start function checkSession() to check current user session ***************/

    function checkSession() {
        if (!$this->session->userdata(SITE_SESSION_NAME.'ADMINID')) {
            $newdata = array(
                'RETURN_URL' => $this->curPageURL()
            );
            $this->session->set_userdata($newdata);
            $this->session->set_flashdata('flashdata', 'OOPS! your session has been expired!');
            return false;
        } else {
            $this->db->where("id", $this->session->userdata(SITE_SESSION_NAME.'ADMINID'));
            $query = $this->db->get(TBL_ADMINLOGIN);
            foreach ($query->result() as $rows) {
                $adminUserHash = $rows->hash;
            }
            if ($adminUserHash != $this->session->userdata(SITE_SESSION_NAME.'ADMINTBL_USERHASH')) {
                $this->session->set_flashdata('flashdata', 'OOPS! your session has been expired!');
                $newdata = array(
                    SITE_SESSION_NAME.'RETURN_URL' => $this->curPageURL()
                );
                $this->session->set_userdata($newdata);
                return false;
            }
            return true;
        }
        return true;
    }

    /*************** End function checkSession() ***************/
    
    /*************** Start function isExistsAdminEmailId() to check existance of admin email id ***************/

    function isExistsAdminEmailId($emailId) {
        $this->db->where("emailId", $emailId);
        $query = $this->db->get(TBL_ADMINLOGIN);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            $this->session->set_flashdata('errordata', 'Email Id does not exist!');
            return false;
        }
    }

    /*************** End function isExistsAdminEmailId() ***************/
    
    /*************** Start function adminResendPassword() to resend admin password ***************/

    function adminResendPassword($post) {
        $this->db->where("emailId", $post[email]);
        $query = $this->db->get(TBL_ADMINLOGIN);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $line) {
                $uid = $line->id;
                $possible = '012dfds3456789bcdfghjkmnpq454rstvwx54yzABCDEFG5HIJ5L45MNOP352QRSTU5VW5Y5Z';
                $newPassword = substr($possible, mt_rand(0, strlen($possible) - 10), 8);
                $Password = $this->encrypt_password($newPassword);
                $updatedata = array(
                    'password' => $Password,
                );
                $this->db->where('id', $uid);
                $this->db->update(TBL_ADMINLOGIN, $updatedata);
                $this->session->set_flashdata('success', 'Password has been send to your email.');
                $to = $line->emailId;
                $subject = "Forget Password";
                $from = $line->emailId;
                $message = '<table cellspacing="0" cellpadding="0" align="center" width="100%">
                                <tbody>
                                    <tr>
                                        <td valign="top" height="81" colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="10">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="30" style="font-family: tahoma; text-decoration: none; font-weight: bold; font-size: 11px; color: rgb(55, 64, 73);" colspan="3">&nbsp;Hello ' . $line->username . ',</td>
                                    </tr>
                                    <tr>
                                        <td height="30" width="76%" style="font-family: Tahoma; font-size: 12px; font-weight: bold; text-decoration: none; color: rgb(83, 91, 97);" colspan="3">&nbsp;Your Password is given bellow.</td>
                                    </tr>
                                    <tr>
                                        <td height="30" style="font-family: Tahoma; font-size: 12px; font-weight: normal; text-decoration: none; color: rgb(83, 91, 97);" colspan="4">&nbsp;' . $newPassword . '</td>
                                    </tr>

                                    <tr>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td height="30" colspan="4">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>';
                @mail($to, $subject, "$message\r\n", "From: $from\n" . "MIME-Version: 1.0\n" . "Content-type:text/html;charset=iso-8859-1" . "\r\n" . 'X-Mailer: PHP/' . phpversion());
                return true;
            }
        }
    }

    /*************** End function adminResendPassword() ***************/
    
    /*************** Start function encrypt_password() to encrypt user password ***************/

    function encrypt_password($plain) {
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $this->tep_rand();
        }
        $salt = substr(md5($password), 0, 2);
        $password = md5($salt . $plain) . ':' . $salt;
        return $password;
    }

    /*************** End function encrypt_password() ***************/
    
    /*************** Start function tep_rand() generate random number series ***************/

    function tep_rand($min = null, $max = null) {
        static $seeded;
        if (!$seeded) {
            mt_srand((double) microtime() * 1000000);
            $seeded = true;
        }
    }

    /*************** End function tep_rand() ***************/
    
    /*************** Start function fetchValue() to fetch single value from table ***************/
    
    function fetchValue($table, $field, $where) {
        $result = "";
        $this->db->select($field);
        $this->db->where($where);
        $query = $this->db->get($table);
        foreach ($query->result() as $value) {
            $result = $value->$field;
        }
        return $result;
    }
    
    /*************** End function fetchValue() ***************/
}

?>
