<?php
/**
 @ Created by Prioranjan Chowdhury <sumon.sustcse@gmail.com> .
 @ Created on 02th February, 2025
 */

use App\AppConfig;
/**
 * Check if the application is installed.
 *
 * This method determines whether the application has already been installed.
 * If installed, it returns `true`; otherwise, it returns `false`.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 1st March, 2025
 * @return bool
 */
function isAppInstalled() {

    $val = getInstallationValueFromEnvFile();

    if($val == AppConfig::DEFAULT_ENUM_VAL_DONE) {
        Session::put(AppConfig::SESSION_KN_APP_INSTALLED, true);

        return true;
    }
    Session::put(AppConfig::SESSION_KN_APP_INSTALLED, false);
    return false;
}
/**
 * Get the installation status value from the environment file.
 *
 * This method retrieves the value associated with the `INSTALLATION` key from the `.env` file.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 1st March, 2025
 * @return string
 */

function getInstallationValueFromEnvFile() {

    $key = 'INSTALLATION';

    return getValueByKeyFromEnvFile($key);
}

/**
 * Retrieve the value for a specific key from the `.env` file.
 *
 * This method reads the environment file and returns the value associated with a given key.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 1st March, 2025
 * @param string $key The environment variable key to retrieve.
 * @return string The value of the given key, or an empty string if not found.
 */
function getValueByKeyFromEnvFile($key) {

    $envFile = app()->environmentFilePath();

    $handle = fopen($envFile, "r");
    $ret = '';

    if($handle) {
        while(($line = fgets($handle)) !== false) {

            if(strpos($line, $key) !== false) {

                $tmp = trim($line);
                $tmp = explode('=', $tmp);
                $ret = empty($tmp[1]) ? '' : trim($tmp[1]);

                break;
            }
        }

        fclose($handle);
    }

    return $ret;
}
/**
 * Set the environment value for a specific key in the `.env` file.
 *
 * This method updates or adds a given key-value pair in the environment file.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 2nd March, 2025
 * @param string $envKey The key to be updated or added in the `.env` file.
 * @param string $envValue The new value to be set for the given key.
 * @return bool Returns `true` if the operation was successful.
 */
 function setEnvironmentValue($envKey, $envValue) {
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);
    $lines = explode("\n", trim($str)); // Trim to remove extra newlines
    $found = false;

    foreach ($lines as $index => $line) {
        if (preg_match("/^{$envKey}=.*/", $line)) { // Match the exact key
            $lines[$index] = "{$envKey}={$envValue}";
            $found = true;
            break;
        }
    }

    if (!$found) {
        $lines[] = "{$envKey}={$envValue}";
    }

    // Reassemble the .env file ensuring a single newline at the end
    file_put_contents($envFile, implode("\n", $lines) . PHP_EOL);

    return true;
}


 /*
function setEnvironmentValue($envKey, $envValue) {

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);
    $arr = explode("\n", $str);
    $oldValue = "";

    foreach($arr as $index => $string) {
        if(strpos($string, $envKey) !== false) {
            $oldValue = $arr[$index];
        }
    }

    $newValue = "{$envKey}={$envValue}";

    if($oldValue) {
        $str = str_replace("$oldValue", "$newValue", $str);

    } else {
        $str = $str . "\n".$newValue;
    }

    $fp = fopen($envFile, 'w');
    fwrite($fp, $str);
    fclose($fp);

    return true;
}*/

/**
 * Validate the database host name.
 *
 * This method checks whether the provided database host name is valid or not.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 2nd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing the database host name.
 * @return array An associative array containing validation status and messages.
 */
function isDbHostNameValid($request) {

    $DbHostName = $request->get('DatabaseHostName');
    $arr = [];

    if($DbHostName) {
        if(checkHost($DbHostName)) {
            $status = true;
        } else {
            $arr['field_id'] = 'DatabaseHostName';
            $arr['label'] = 'Database Host Name';
            $arr['message'] = 'Provided Database Host Name is invalid.';
            $status = false;
        }
    } else {
        $arr['field_id'] = 'DatabaseHostName';
        $arr['label'] = 'Database Host Name';
        $arr['message'] = 'Database Host Name can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Validate database connection parameters.
 *
 * This function checks whether the provided database parameters are valid.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing database credentials.
 * @return array An associative array containing the validation status and messages.
 */
function isDbCrudValid($request) {

    $DbHostName = $request->get('DatabaseHostName');
    $dbUser = $request->get('DatabaseUserName');
    $DbPassword = $request->get('DatabasePassword');
    $dbName = $request->get('DatabaseName');
    $portNum = $request->get('PortNumber');
    $socName = $request->get('SocketName');
    $arr = [];

    if($DbHostName && $dbUser && $dbName && $portNum && $socName) {
        updateEnv($DbHostName, $dbUser, $DbPassword, ' ', $portNum);
        if(isDbCredentialValid()) {
            $status = true;
        } else {
            $arr['field_id'] = 'DatabasePassword';
            $arr['label'] = 'Database Credential';
            $arr['message'] = 'Provided Database Credential is invalid.';
            $status = false;
        }
    } else {
        $arr['field_id'] = 'DatabasePassword';
        $arr['label'] = 'Database Credential';
        $arr['message'] = 'Database Credential can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Check if the provided database name is valid.
 *
 * This function checks whether the provided database name is blank or, if not blank, 
 * whether it already exists in the system.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing the database name and credentials.
 * @return array An associative array containing the validation status and messages.
 */

function isDbNameValid($request) {

    $DbHostName = $request->get('DatabaseHostName');
    $dbUser = $request->get('DatabaseUserName');
    $DbPassword = $request->get('DatabasePassword');
    $dbName = $request->get('DatabaseName');
    $portNum = $request->get('PortNumber');
    $socName = $request->get('SocketName');
    $arr = [];
    if($dbName) {
        $data = isDbNameExist($DbHostName, $dbUser, $DbPassword, $dbName, $portNum, $socName);
        if($data["success"]) {
            $status = true;
        } else {
            $arr['field_id'] = 'DatabaseName';
            $arr['label'] = 'Database Name';
            $arr['message'] = 'Database Name already exist.';
            $status = false;
        }
    } else {
        $arr['field_id'] = 'DatabaseName';
        $arr['label'] = 'Database Name';
        $arr['message'] = 'Database Name can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Check the validation for the admin password.
 *
 * This function validates if the provided admin password is not blank.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing the admin password.
 * @return array An associative array containing the validation status and messages.
 */
function isAdminPassValid($request) {
    $Password = trim($request->get('Password'));
    $arr = [];
    if($Password=="") {
			$arr['field_id'] = 'Password';
			$arr['label'] = 'Password';
			$arr['step']=2;
			$arr['message'] = 'Password can not be blank';
        $status = false;
    } else {
       $status = true;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Check the validation for the admin full name.
 *
 * This function validates if the provided admin full name is not blank.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing the admin full name.
 * @return array An associative array containing the validation status and messages.
 */
function isAdminFullNameValid(\Illuminate\Http\Request $request): array
{
    $name = trim($request->get('AdminFullName'));
    $response = [
        'status'  => true,
        'message' => [],
    ];

    if ($name == "") {
        $response['status'] = false;
        $response['message'] = [
            'field_id' => 'AdminFullName',
            'step'     => 2,
            'label'    => 'Admin Full Name',
            'message'  => 'Admin Full Name cannot be blank.',
        ];
    }

    return $response;
}


/**
 * Check the validation for the admin email address.
 *
 * This function validates if the provided admin email address is not blank and follows the correct email format.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param \Illuminate\Http\Request $request The request object containing the admin email address.
 * @return array An associative array containing the validation status and messages.
 */
function isAdminEmailValid($request) {
    $AdminEmail = trim($request->get('AdminEmailAddress'));
    $arr = [];
    if($AdminEmail) {
        if(isValidEmail($AdminEmail)) {
           $status = true;
        } else {
            $arr['field_id'] = 'AdminEmailAddress';
            $arr['label'] = 'Email Address';
			$arr['step2']=2;
            $arr['message'] = 'Provided Email Address is invalid.';
            $status = false;
        }
    } else {
        $arr['field_id'] = 'AdminEmailAddress';
        $arr['label'] = 'Email Address';
        $arr['message'] = 'Email Address can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}

/**
 * Check email settings and verify the email configuration.
 *
 * This function sets environment values for email configuration and attempts to send a test email
 * to verify if the configuration is valid.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $MailDriver The mail driver (e.g., SMTP, Mailgun).
 * @param string $MailHost The mail server hostname (e.g., smtp.gmail.com).
 * @param string $MailPort The port number for the mail server.
 * @param string $mailUser The mail username (usually the email address).
 * @param string $mailPassword The password or app-specific password for the mail account.
 * @param string $mailEncryp The encryption method (e.g., tls, ssl).
 * @param string $systemEmail The email address from which emails are sent.
 * @return bool Returns true if the email configuration is successful, otherwise false.
 */
function CheckEmailConfig($MailDriver, $MailHost, $MailPort, $mailUser, $mailPassword, $mailEncryp,$systemEmail) {

    if($MailDriver != '') {
        setEnvironmentValue('MAIL_MAILER', "$MailDriver");
    }
    if($MailHost != '') {
        setEnvironmentValue('MAIL_HOST', "$MailHost");
    }
    if($MailPort != '') {
        setEnvironmentValue('MAIL_PORT', "$MailPort");
    }
    if($MailDriver != '') {
        setEnvironmentValue('MAIL_USERNAME', "$mailUser");
    }
    if($mailPassword != '') {
        setEnvironmentValue('MAIL_PASSWORD', "$mailPassword");
    }
    if($mailEncryp != '') {
        setEnvironmentValue('MAIL_ENCRYPTION', "$mailEncryp");
    }
	if($systemEmail!=''){
		 setEnvironmentValue('MAIL_FROM_ADDRESS', "$systemEmail");
	}

    Artisan::call('config:cache');
    $resultMail = true;
    try {
        Mail::send('mail.email_settings', ['studyName' => '', 'user_name' => ''], function ($message) use ($systemEmail) {
            $message->from($systemEmail,AppConfig::GENERAL_PRODUCT_NAME)->to($systemEmail)->subject('Email Configured Successfully');
        });
    } catch(\Exception $e) {
        /*setEnvironmentValue('MAIL_DRIVER', "");
        setEnvironmentValue('MAIL_HOST', "");
        setEnvironmentValue('MAIL_PORT', "");
        setEnvironmentValue('MAIL_USERNAME', "");
        setEnvironmentValue('MAIL_PASSWORD', "");
        setEnvironmentValue('MAIL_ENCRYPTION', "");

        Artisan::call('config:cache');*/
		\Log::error('Mail error: ' . $e->getMessage());

        $resultMail = false;
    }

    return $resultMail;
}

/**
 * This function will get $host as parameter and check whether the host name is valid or invalid.
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $host
 * @return boolean
 */
function checkHost($host) {

    $ip = gethostbyname("$host");

    return filter_var($ip, FILTER_VALIDATE_IP);
}
/**
 * Update the .env file with database environment variables.
 *
 * This method updates the environment variables for database configuration 
 * such as the host, username, password, database name, and port.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @created 3rd March, 2025
 * @param string $DbHostName The database host name.
 * @param string $dbUser The database username.
 * @param string $DbPassword The database password.
 * @param string $dbName The database name.
 * @param string $portNum The port number for the database connection.
 * @return void
 */
function updateEnv($DbHostName, $dbUser, $DbPassword, $dbName, $portNum){
    if($DbHostName != '') {
        setEnvironmentValue('DB_HOST', "$DbHostName");
    }
    if($dbUser != '') {
        setEnvironmentValue('DB_USERNAME', "$dbUser");
    }
    setEnvironmentValue('DB_PASSWORD', "$DbPassword");
    if($dbName != '') {
        setEnvironmentValue('DB_DATABASE', "$dbName");
    }
    if($portNum != '') {
        setEnvironmentValue('DB_PORT', "$portNum");
    }

    Artisan::call('config:cache');
}
/**
 * Check if the provided database credentials are valid.
 *
 * This function accepts database credentials as parameters and checks whether they are valid or invalid.
 * It attempts to establish a connection to the database using the provided credentials.
 * 
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @return boolean Returns true if the credentials are valid, otherwise false.
 */
function isDbCredentialValid() {
    $message = "";

    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $message = $e->getMessage();
        //die("Could not connect to the database.  Please check your configuration. error:" . $message );
    }
    if($message != "") {
        return false;
    } else return true;
}

/**
 * This function will get host and port as parameter and check whether the port is valid or invalid.
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $dbHost
 * @param number $port
 * @return boolean
 */
function isPortValid($dbHost, $port){
    $ip = gethostbyname("$dbHost");

    $fp = @fsockopen($ip, $port);
    if (!$fp) {
        return false;
    } else {
        fclose($fp);
        return true;
    }
}
/**
 * Check if the provided socket connection is valid or invalid.
 *
 * This method attempts to establish a connection to the database using the provided socket and database credentials.
 * If the connection fails, it will return false; otherwise, it will return true.
 * 
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $DbHostName The database host name.
 * @param string $dbUser The database username.
 * @param string $DbPassword The database password.
 * @param string $portNum The port number for the database connection.
 * @param string $socName The socket name for the connection.
 * @return bool Returns true if the socket connection is valid, otherwise false.
 */
function isSocketValid($DbHostName, $dbUser, $DbPassword, $portNum, $socName){
    $message = "";
    try {
        $conn = new \mysqli($DbHostName, $dbUser, $DbPassword,"", $portNum, $socName);
        if(!$conn->ping()) {

        }
        $conn->close();
    } catch(\Exception $e) {
        $message = $e->getMessage();
    }
    if($message != "") {
        return false;
    } else return true;
}

/**
 * Check if the provided database name exists.
 *
 * This function checks if the database with the provided name already exists.
 * If the database exists, it returns an error message; otherwise, it returns a success message.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $DbHostName The database host name.
 * @param string $dbUser The database username.
 * @param string $DbPassword The database password.
 * @param string $dbName The name of the database to check.
 * @param string $portNum The port number to connect to.
 * @param string $socName The socket name to use for the connection.
 * @return array.
 */
function isDbNameExist($DbHostName, $dbUser, $DbPassword, $dbName, $portNum, $socName) {

    //Connect to the database
    $conn = new \mysqli($DbHostName, $dbUser, $DbPassword,"", $portNum, $socName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    //if the database is already exist return error with message
	$dbExists = $conn->query("SHOW DATABASES LIKE '$dbName'");
	//echo $dbExists->num_rows;exit;
   if ($dbExists->num_rows > 0) {
        $id = "#DatabaseName";
        $success = 0;
        $message = "Provided database name already exist";
    }//DB name is valid
    else {
        $id = "#DatabaseName";
        $success = 1;
        $message = "";
		 setEnvironmentValue('DB_DATABASE', "$dbName");
    }
    $conn->close();
    $data = array('success' => $success, 'id' => $id, 'txt' => $message);

    return $data;
}
/**
 * Get all required extensions that need to be checked in the configuration.
 *
 * This function checks whether the required extensions are loaded and returns the status for each extension.
 *
 * @author Prioranjan Chowdhury
 * @param void
 * @return array.
 */
function checkExtensionStatus(){
	$requiredExtensions=AppConfig::REQUIRED_EXTENSIONS;
	$extensionsStatus = [];
	foreach ($requiredExtensions as $extension) {
		$extensionsStatus[$extension] = extension_loaded($extension);
	}
	return $extensionsStatus;
}
/**
 * Check if the application name is blank or not.
 *
 * This function checks whether the application name is provided or not.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param \Illuminate\Http\Request $request The request object containing the application name.
 * @return array An associative array containing the validation status and messages.
 */
function isApplicationNameValid($request) {
	
    $applicationName = trim($request->get('application_name'));
    $arr = [];
	$status=true;
    if($applicationName=="") 
       {
        $arr['field_id'] = 'ApplicationName';
		$arr['step'] = 2;
        $arr['label'] = 'Application Name';
        $arr['message'] = 'Application Name can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Check if the application time zone is blank or not.
 *
 * This function checks whether the application ime zone is provided or not.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param \Illuminate\Http\Request $request The request object containing the application name.
 * @return array An associative array containing the validation status and messages.
 */
function isAppTimeZoneValid($request) {
    $appTimeZone = trim($request->get('timeZone'));
    $arr = [];
	$status=true;
    if($appTimeZone=="") 
       {
        $arr['field_id'] = 'timeZone';
		$arr['step'] = 2;
        $arr['label'] = 'Time Zone';
        $arr['message'] = 'Application Time Zone can not be blank';
        $status = false;
    }

    return array(
        'status'  => $status,
        'message' => $arr,
    );
}
/**
 * Check if the provided email address is in a valid format.
 *
 * This function validates the email format using PHP's filter_var function.
 *
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @param string $email The email address to validate.
 * @return bool Returns true if the email format is valid, false otherwise.
 */
function isValidEmail($email) {
    // Use filter_var to validate the email format
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function getApplicationName() {

    return \App\AppConfig::GENERAL_PRODUCT_NAME;
}