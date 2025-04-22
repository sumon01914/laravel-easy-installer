<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\AppConfig;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class InstallerController extends Controller
{
	public $validation;
      /**
	* 
	* @param request $request
	* 
	* @return
	*/
	 function validateInput($request){
		// 
        $noError = true;
        updateEnv('', '', '', '', '');
		
        $applicationName = isApplicationNameValid($request);
        if(!$applicationName['status']){
            $this->validation['application_name'] = $applicationName['message'];
            $noError = false;
        }
		$appTimeZone = isAppTimeZoneValid($request);
        if(!$appTimeZone['status']){
            $this->validation['time_zone'] = $appTimeZone['message'];
            $noError = false;
        }
		
	
        $AdminName = isAdminFullNameValid($request);
        if(!$AdminName['status']){
            $this->validation['AdminFullName'] = $AdminName['message'];
            $noError = false;
        }
		
		$password = isAdminPassValid($request);
        if(!$password['status']){
            $this->validation['Password'] = $password['message'];
            $noError = false;
        }


        $email = isAdminEmailValid($request);
        if(!$email['status']){
            $this->validation['EmailAddress'] = $email['message'];
            $noError = false;
        }
        $DbHostName = isDbHostNameValid($request);
        if(!$DbHostName['status']){
            $this->validation['DatabaseHostName'] = $DbHostName['message'];
            $noError = false;
        }

        if($DbHostName['status']){
            $DbCred = isDbCrudValid($request);
            if(!$DbCred['status']){
                $this->validation['DatabasePassword'] = $DbCred['message'];
                $noError = false;
            }
            if($DbCred['status']) {
                $dbName = isDbNameValid($request);
                if (!$dbName['status']) {
                    $this->validation['DatabaseName'] = $dbName['message'];
                    $noError = false;
                }
            }
        }
		
        return $noError;
    }
  	public function index(Request $request){
        $installed = isAppInstalled();
  		if($installed)
  			return redirect('login');
  		else
  		    return view('installer.index');
  	}
	  /**
	* 
	* @param request $request
	* 
	* @return
	*/
  	/*public function applicationSettings(Request $request){
        $installed = isAppInstalled();
		$tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

  		if($installed)
  			return redirect('login');
  		else
  		    return view('installer.application_settings',['time_zone_list' => $tzlist]);
  	}*/
	
	/**
	 * @Description - Process all application forms for each step like Application settings, database settings, and email settings. 
	 * All request data for each step will be stored into the session for future use (such as editing).
	 * @Author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
	 * @Created On 04th March, 2025
	 * @Param Request $request
	 * @Return response
	 */
	public function processApplication(Request $request){
		  $step = $request->input('step');
		//processing data for application settings
		 if($step==2){
			$data=array(
			 'application_name'=>$request->input('application_name'),
			 'timeZone'=>$request->input('timeZone'),
			 'app_url'=>$request->input('app_url'),
			 'app_key'=>$request->input('app_key'),
			 'AdminFullName'=>$request->input('AdminFullName'),
			 'AdminEmailAddress'=>$request->input('AdminEmailAddress'),
			 'Password'=>$request->input('Password'),
			  );
		 }
		 //processing data for database settings
		  if($step==3){
			$data=array(
			  'DatabaseHostName'=>$request->input('DatabaseHostName'),
			  'PortNumber'=>$request->input('PortNumber'),
			  'DatabasePassword'=>$request->input('DatabasePassword'),
			  'DatabaseUserName'=>$request->input('DatabaseUserName'),
			  'DatabaseName'=>$request->input('DatabaseName'),
			  'DatabaseName'=>$request->input('DatabaseName'),
			  'SocketName'=>$request->input('SocketName'),
			  'DatabasePassword'=>$request->input('DatabasePassword')
			  );
		 }
		 //processing data for email settings
		 if($step==4){
			$data=array(
			  'mail_driver'=>$request->input('mail_driver'),
			  'mail_host'=>$request->input('mail_host'),
			  'mail_port'=>$request->input('mail_port'),
			  'mail_username'=>$request->input('mail_username'),
			  'mail_password'=>$request->input('mail_password'),
			  'mail_encryption'=>$request->input('mail_encryption')
			  );
		 }

		// Store the data in the session for each step
		Session::put('step' . $step, $data);
		return response()->json(['status' => "success",'step'=>$step+1]);
}
	/**
     * This function will get request params for database credentials and it will check whether the credentials is valid or invalid.
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
     * @param request $request
     * @return array
     */
	public function checkDbCrud(Request $request){
		$DbHostName	= $request->get('hostName');
		$dbUser		= $request->get('dbUser');
		$DbPassword = $request->get('dbPassword');
		$dbName		= $request->get('dbName');
		$portNum	= $request->get('portNum');
		$socName	= $request->get('socName');
		$action		= $request->get('action');

		//If the request is to check host name.
		if($action == "host"){
		    //If this is a valid host return success else return error with message and field id.
			if(checkHost($DbHostName)){
				$id = "";
				$success = 1;
				$message = "";
			}else{
				$id = "#DatabaseHostName";
				$success = 0;
				$message = "Provided host name is invalid.";
			}
			$data = array('success' => $success, 'id' => $id, 'txt' => $message);
			return $data;
		}

		//If the request is to check the database credentials
		if($action == "credential"){
			
            updateEnv($DbHostName, $dbUser, $DbPassword, ' ', $portNum);
			
			if(checkHost($DbHostName)){
				
                //If this is a valid host
                //if the credential is valid return success, else return error with message
				if(isDbCredentialValid()){
					$id = "";
					$success = 1;
					$message = "";
				}else{
					$id = "";
					$success = 0;
					$message = "Provided credential is invalid";
				}
			}else{
				//echo "here";exit;
                //If the host name is invalid return error with message
				$id = "#DatabaseHostName";
				$success = 0;
				$message = "Provided host name is invalid.";
			}
			
			$data = array('success' => $success, 'id' => $id, 'txt' => $message);
			return $data;
		}

        //If the request is to check the database name
		if($action == "database"){

		    //if host name is valid
			if(checkHost($DbHostName)){
			    //if the credential is valid
				if(isDbCredentialValid()){
				    return isDbNameExist($DbHostName, $dbUser, $DbPassword, $dbName, $portNum, $socName);
				}else{
                    //if the credential is invalid error with message
					$id = "";
					$success = 0;
					$message = "Provided credential is invalid";
				}
			}else{
                //if the host name is invalid error with message
				$id = "#DatabaseHostName";
				$success = 0;
				$message = "Provided host name is invalid.";
			}
			$data = array('success' => $success, 'id' => $id, 'txt' => $message);
			return $data;
		}

		if($action == "checkPort"){
            if(checkHost($DbHostName)){

                //If this is a valid host
                //if the port is valid return success, else return error with message
                if(isPortValid($DbHostName, $portNum)){
                    $id = "";
                    $success = 1;
                    $message = "";
                }else{
                    $id = "";
                    $success = 0;
                    $message = "Provided port number is invalid";
                }
            }else{
                //If the host name is invalid return error with message
                $id = "#DatabaseHostName";
                $success = 0;
                $message = "Provided host name is invalid.";
            }
            $data = array('success' => $success, 'id' => $id, 'txt' => $message);
            return $data;
        }

        if($action == "checkSocket"){
            if(checkHost($DbHostName)){
                //If this is a valid host
                //if the port is valid return success, else return error with message
                if(isPortValid($DbHostName, $portNum)){
                    if(isDbCredentialValid()){
                        if(isSocketValid($DbHostName, $dbUser, $DbPassword, $portNum, $socName)){
                            setEnvironmentValue('DB_SOCKET', "$socName");
                            $id = "";
                            $success = 1;
                            $message = "";
                        }else{
                            $id = "SocketName";
                            $success = 0;
                            $message = "Provided socket is invalid";
                        }
                    }else{
                        //if the credential is invalid error with message
                        $id = "#DatabaseUserName";
                        $success = 0;
                        $message = "Provided credential is invalid";
                    }
                }else{
                    $id = "#PortNumber";
                    $success = 0;
                    $message = "Provided port number is invalid";
                }
            }else{
                //If the host name is invalid return error with message
                $id = "#DatabaseHostName";
                $success = 0;
                $message = "Provided host name is invalid.";
            }
            $data = array('success' => $success, 'id' => $id, 'txt' => $message);
            return $data;
        }
	}
	/**
	 * @Description This function dynamically loads the form for each step.
	 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
	 * @param Request $request
	 * @return void
	 */
	 public function loadForm(Request $request){
        if($request->get('step')==1){
			$extensionsStatus=checkExtensionStatus();
          return view('installer.configuration_settings',compact('extensionsStatus'));
		}
        else if($request->get('step')==2) {
           $installed = isAppInstalled();
			$tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
            return view('installer.application_settings',['time_zone_list' => $tzlist]);
        }
        else if($request->get('step')==3) {
			$installed = isAppInstalled();
			$tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
			return view('installer.database_settings',['time_zone_list' => $tzlist]);
            //return view('installer.database_settings');
        }
		 else if($request->get('step')==4) {
			return view('installer.email_settings');
            //return view('installer.database_settings');
        }
		 else if($request->get('step')==5) {
			$tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
  		    return view('installer.preview_install',['time_zone_list' => $tzlist]);
        }
		
			
    }
	/**
	 * This method will clear session data for all subsequent steps starting from the provided step.
	 * It will remove session data for steps greater than the provided step number.
	 * @Author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
	 * @Created On 24th Feb, 2025
	 * @Param int $step - The current step number from which subsequent session data will be cleared.
	 * @Return void
	 */
	public function clearSessionData($step=1){
		for($i=$step+1;$i<=5;$i++){
			 session()->forget(['step'.$i]);
		}
	}
	public function emailConfiguration(Request $request){
  		    return view('installer.email_settings');
  	}
	
	/**
	 * This function will get the email configuration parameters from the request
	 * and check whether the configuration is valid or invalid.
	 * It sends a test email to the system email address to verify the configuration.
	 *
	 * @Author Prioranjan Chowdhury <sumonsustcse@gmail.com>
	 * @Param \Illuminate\Http\Request $request - The request object containing email configuration parameters.
	 * @Return array - Returns an array indicating success or failure and a message.
	 */
    public function checkEmailConfig(Request $request){
        $MailDriver 	= $request->get('mail_driver');
        $MailHost   	= $request->get('mail_host');
        $MailPort        = $request->get('mail_port');
        $mailUser	    = $request->get('mail_username');
        $mailPassword   = $request->get('mail_password');
        $mailEncryp	    = $request->get('mail_encryption');
		$systemEmail	=AppConfig::SYSTEM_EMAIL;

        $resultMail = CheckEmailConfig($MailDriver, $MailHost, $MailPort, $mailUser, $mailPassword, $mailEncryp,$systemEmail);

        if($resultMail){
            $data = array('success' => "1", 'message' => "Email Configuration is valid.Email sent to ".$systemEmail);
        }else{
            $data = array('success' => "0", 'message' => "Invalid email configuration. Please verify your settings and try again.");
        }

        return $data;
    }
	public function databaseConfiguration(Request $request){
  		    return view('installer.database_settings');
  	}
	public function installPreview(Request $request){
			$tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
  		    return view('installer.preview_install',['time_zone_list' => $tzlist]);
  	}
	 /**
	 * This function will get the request with email configuration parameters and write the configuration to the .env file.
	 * It will also attempt to create a database and return the result.
	 * 
	 * @Author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
	 * @param \Illuminate\Http\Request $request - The request containing the configuration data.
	 * @return array - Returns success or error status with a message.
	 */
	public function CreatingDB(Request $request){
		
		if(!$this->validateInput($request)){
            return array(
                'success' => "error",
                'message' => $this->validation
            );
        }
		
	
		$DbHostName		= trim($request->get('DatabaseHostName'));
		$dbUser			= trim($request->get('DatabaseUserName'));
		$DbPassword 	= trim($request->get('DatabasePassword'));
		$dbName			= trim($request->get('DatabaseName'));
        $portNum		= trim($request->get('PortNumber'));
        $socName		= trim($request->get('SocketName'));
		$timeZone 		= trim($request->get('timeZone'));
		$appUrl			=trim($request->get('app_url'));
		$appKey			=trim($request->get('app_key'));
		$appName		=trim($request->get('application_name'));
		
		/****************************************
		 * Updating .env file and config file	*
		 ****************************************/

        setEnvironmentValue('DB_HOST', "$DbHostName");
        setEnvironmentValue('DB_DATABASE', "$dbName");
        setEnvironmentValue('DB_USERNAME', "$dbUser");
        setEnvironmentValue('DB_PASSWORD', "$DbPassword");
        setEnvironmentValue('DB_PORT', "$portNum");
        setEnvironmentValue('APP_TIME_ZONE', "$timeZone");
        setEnvironmentValue('DB_SOCKET', "$socName");
		if($appUrl!="")
			setEnvironmentValue('APP_URL', "$appUrl");
		//if($appKey!="")
		//	setEnvironmentValue('APP_KEY', "$appKey");
		if($appName!="")
			setEnvironmentValue('APP_NAME', "$appName");
		
		
        //run php artisan config:cache command
        Artisan::call('config:cache');
 
        //Connect to the database
        $conn = new \mysqli($DbHostName, $dbUser, $DbPassword, "", $portNum, $socName);
        //SQL to create database
        $sql = "CREATE DATABASE `$dbName`";

        //If database created successfully return success with message
        if ($conn->query($sql) === TRUE) {
            $data = array('success' => "success", 'message' => "Database created successfully");
        }else{
            //If not possible to create database, return success with message
            $data = array('success' => "error", 'message' => "Installation Failed. Issue occurred during database creation.");
        }
		
        return $data;
	}
	  /**
     * This function will get request to run migration
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com.com>
     * @return array
     */
	public function Migration(){
		set_time_limit(0);
		ini_set('memory_limit','512M');
		/*******************************************
		 *Running Artisan config:cache and migrate *
		 *******************************************/
		Artisan::call('config:cache');

		try{

			Artisan::call('migrate', [
				'--force' => true,
			]);
			$data = array('success' => "success", 'message' => "Migrate run successfully");
		}catch (\Exception $e){
			$message = $e->getMessage();
			$data = array('success' => "error", 'message' => $message);
		}

		return $data;
	}
	 /**
     * This function will get request to run seeder
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
     * @return array
     */
	public function Seed(){
		set_time_limit(0);
		ini_set('memory_limit','256M');
		/****************************************
		 *Running Artisan config:cache and seed	*
		 ****************************************/
		//if(Artisan::call('config:cache')){
		Artisan::call('config:cache');

        try{
            Artisan::call('db:seed', [
                '--force' => true,
            ]);
            //Artisan::call('db:seed');
            $data = array('success' => "success", 'message' => "Seed run successfully");
        }catch (\Exception $e){
            $message = $e->getMessage();
            $data = array('success' => "error", 'message' => $message);
        }

		return $data;
	}


	/**
     * This function will get request to create user
     * This function will create super admin and admin user and finish the installation
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
     * @param request $request
     * @return array
     */
	public function createUser(Request $request){
		//Application Admin Credential Settings
		
			$adminFullName		= trim($request->get('AdminFullName'));
			$AdminEmail			= trim($request->get('AdminEmailAddress'));
			$Password		= trim($request->get('Password'));
		//create user
		$userId 	= User::makeUser($adminFullName, $AdminEmail, $Password);
		if($userId){
			//Write the environment variable INSTALLATION=DONE*/
			/*** IMPORTANT TO UPDATE AFTER INSTALLING  
				After completing each step of the installation process,  
				update the INSTALLATION variable in the .env file as "DONE".  
				I have kept it as a comment because of the testing state.  
			***/
			//setEnvironmentValue('INSTALLATION', "DONE");
			setEnvironmentValue('INSTALLATION', "");

			//Generate redirect url for successfully installed page.
			$url = url('/installer/done');
			$data = array('success' => "success", 'url' => $url);

			return $data;
		}
	}
	 /**
     * This function will get request to role back the installation process
     * This function will remove database and other settings
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
     * @return array
     */
	public function roleBack(){

	    //get all DB credentials from .env file
		$DbHostName 	= getValueByKeyFromEnvFile('DB_HOST');
		$dbUser 		= getValueByKeyFromEnvFile('DB_USERNAME');
		$DbPassword 	= getValueByKeyFromEnvFile('DB_PASSWORD');
		$dbName 		= getValueByKeyFromEnvFile('DB_DATABASE');
		//Connect to the database and drop the database
		$conn = new \mysqli($DbHostName, $dbUser, $DbPassword);
		$sql = "DROP DATABASE $dbName";

		//If database dropped successfully
		if ($conn->query($sql) === TRUE) {
		    //Make all db credentials blank in .env file
			setEnvironmentValue('DB_HOST', "");
			setEnvironmentValue('DB_DATABASE', "");
			setEnvironmentValue('DB_USERNAME', "");
			setEnvironmentValue('DB_PASSWORD', "");
			setEnvironmentValue('INSTALLATION', "");
			setEnvironmentValue('APP_TIME_ZONE', "");
			setEnvironmentValue('DB_SOCKET', "");

			Artisan::call('config:cache');

			$data = array('success' => "success", 'message' => "Role back successfully");
		}else{
			$data = array('success' => "error", 'message' => "Error occurred during roleBack");
		}

		return $data;
	}
	
	  /**
     * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
     * @return resource
     */
	public function done()
	{
		return view('installer.done');
	}


	
}
