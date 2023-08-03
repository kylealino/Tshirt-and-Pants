<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
  
class Mylogin extends BaseController
{
    public function index()
    {
        echo view('login/mylogin');
    } 
  
    public function auth()
    {
        //https://www.tutsmake.com/codeigniter-4-login-and-registration-tutorial-example/
        $session = session();
        //$model = new UserModel();
        $db_erp = $this->mydbname->medb(0);
        $meusername = $this->request->getVar('mUsername');
        $password = $this->request->getVar('mPassword');
        $data = $this->myusermod->Verify_User($meusername)->getRowArray();
        $nrows = $this->myusermod->Verify_User($meusername)->resultID->num_rows;
		//$listCat = $this->asArray()->where(['id_user' => $session->id])->get();
		//return $listCat->num_rows();
		//countAll() or countAllResults() 


        if($data) {

            $curdate = substr($data['xcurdate'],0,10);
            $dvalis = substr($data['myuservalis'],0,10);
            $dvalie = substr($data['myuservalie'],0,10);
            $myuser_aremote = $data['myuser_aremote'];

            $passdb = $data['myuserpass'];
            $verify_pass = $this->myusermod->Verify_Password($passdb, $password);
            if($verify_pass){
                if($curdate >= $dvalis and $curdate <= $dvalie) { 

                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $mclient_ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $mclient_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $mclient_ip = $_SERVER['REMOTE_ADDR'];
                    }
                    $meclient_ip = '';
                    $aclient_ip = explode(".", $mclient_ip);
                    if(count($aclient_ip) > 2) {
                        $meclient_ip = $aclient_ip[0] . "." . $aclient_ip[1] . "." . $aclient_ip[2];
                    }

                    if($myuser_aremote == 'Y') {                        
                            $ses_data = array(
                            '__xsys_myusernrc_is_logged__' => TRUE,
                            '__xsys_myusernrc__' => $data['myusername'],
                            '__xsys_myusernrcgroup__' => $data['myusergroup'],
                            '__xsys_myusernrclevel__' => $data['myuserlevel'],
                            '__xsys_myusernrcdept__' => $data['myuser_dept'],
                            '__xsys_myusernrcpfullname__' => $data['myuserfulln'],
                            '__xsys_myusernrcnewui__' => $data['myuser_new_ui'],
                            '__xsys_myusernrcrema__' => $data['myuserrema']
                            );

                            $session->set($ses_data);
                            $this->mylibzdb->user_logs_activity_module($db_erp,'USER_LOG_IN','',$meusername,'','URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            return redirect()->to('/');
                    } else {
                        $str = "select `m_client_ip` from {$db_erp}.`mst_netw_farm` where (`m_client_ip` = '$mclient_ip' or `m_client_ip` = '$meclient_ip') and `m_activate` = 'Y'";
                        $qip = $this->mylibzdb->myoa_sql_exec($str,'URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                        if($qip->resultID->num_rows > 0) { 
                            $ses_data = array(
                            '__xsys_myusernrc_is_logged__' => TRUE,
                            '__xsys_myusernrc__' => $meusername,
                            '__xsys_myusernrcgroup__' => $data['myusergroup'],
                            '__xsys_myusernrclevel__' => $data['myuserlevel'],
                            '__xsys_myusernrcdept__' => $data['myuser_dept'],
                            '__xsys_myusernrcpfullname__' => $data['myuserfulln'],
                            '__xsys_myusernrcnewui__' => $data['myuser_new_ui'],
                            '__xsys_myusernrcrema__' => $data['myuserrema']
                            );

                            $session->set($ses_data);                            
                            $this->mylibzdb->user_logs_activity_module($db_erp,'USER_LOG_IN','',$meusername,'','URI: ' . $_SERVER['PHP_SELF'] . chr(13) . chr(10) . 'File: ' . __FILE__  . chr(13) . chr(10) . 'Line Number: ' . __LINE__);
                            return redirect()->to('/');
                        } else {
                            $session->setFlashdata('nrc_memsg_login', 'Network Access Denied!!! [' . $mclient_ip . ']');
                            return redirect()->to('/mylogin');

                        }
                        $qip->freeResult();
                    }
                } else {
                    $session->setFlashdata('nrc_memsg_login', 'Expired User Login');
                    return redirect()->to('/mylogin');
                }
            } else {
                $session->setFlashdata('nrc_memsg_login', 'Wrong Password');
                return redirect()->to('/mylogin');
            }
        } else {
            $session->setFlashdata('nrc_memsg_login', 'User Name not Found');
            return redirect()->to('/mylogin');
        }
    }
  
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/mylogin');
    }
} //end main Mylogin
