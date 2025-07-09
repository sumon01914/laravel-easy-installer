
1	Introduction
1.1	Purpose 
This document describes the installation process for the Laravel Easy Installer V1.0 web application from scratch. This installer can be integrated into an existing system to manually install your project or system. 
In this documentation, I explain how to integrate this plugin into your existing system, how to install your system using this plugin, how to create an admin user during installation, and more. 
This document covers all aspects of the installation, including the software required to run the Laravel Easy Installer V1.0  successfully on workstations. This manual assumes that the reader has the necessary expertise for the installation process.
2	   Requirements
To install this installer , installation environment should meet following requirements:
Resource Type	Name	Version Requirements/Notes
Web server	Apache	Minimum version 2.4
Database	MySQL	Minimum version  8.0
Server-side Scripting Language	PHP	Minimum version  PHP 8.1 required. Must support mysqli, pdo_mysql 
Package Manager	Composer	Recommended version 2.x
Port	80 and 443	Port number should be open in the firewall of the web server.

For installation of any of the above requirements, please follow the corresponding installation document. 



2.1   Copy source files
Assume that on the web server, the folder that will contain the source files of the installer is named "installer", and its path is:
C:/xampp/htdocs/installer
To set up the installer correctly, follow these steps:
1.	Obtain the Source Files: The installer source files are typically distributed as a ZIP archive.
2.	Copy the Source Files: Copy the ZIP archive containing the installer files to the target location: C:/xampp/htdocs/installer
3.	Extract the Files:
a.	Extract the contents of the ZIP archive directly into the installer folder.
b.	Ensure that the files are placed at the root of the target folder and not inside a subdirectory within the folder.
Folder Structure Example:
C:/xampp/htdocs/installer/
 ├── app/
 ├── bootstrap/
 ├── config/
 ├── public/
 ├── routes/
 ├── vendor/
 ├── .env
 ├── composer.json
 ├── index.php
2.2   Create configuration file
Configuration file needs to be created. In the source folder, a sample configuration file has been provided, which can be copied to create the configuration file.
Open PowerShell and navigate to the source directory.
cp .env.example .env



2.3   Generate new application key
Run following code in command prompt to generate application key
php artisan key:generate
2.4 Run the application: 
Now, you can run the application using Laravel's built-in development server by executing:
php artisan serve
This will start the server at http://localhost:8000 by default. You can now access the installer through your web browser.
3. Laravel Easy Installer-Installation Guide
To install the Laravel Easy Installer, you need to provide certain information at each step. The installation process consists of five steps, which must be completed sequentially to ensure a successful setup.
3.1 . Step 1: Configuration Check:
In this step, the installer will verify whether your system meets all the necessary requirements. It will check:
●	The PHP version to ensure compatibility.
●	The MySQL version to confirm it meets the minimum requirements.
●	Other essential server configurations, including required PHP extensions.
 
Then go to the next step (Step 2) after clicking on 'Next'.

3.2 Step 2: Application Settings:
In this section, you need to provide key details about your application, including:
●	Application Name: The name of your project
●	Time Zone: Select the default timezone for your application.
●	Application URL (APP URL): The base URL where your Laravel application will be hosted.
●	App Key: The application key used for encryption security. It is optional, and you can keep it as null
●	Admin Credential(Admin Account Setup): To manage the application, you need to create an administrator account. Provide the following details:
✔	Admin Name: The display name for the administrator.
✔	Admin Email: The email address used for logging in.
✔	Admin Password: A strong password for security.
 
This step ensures your application is correctly set up with the necessary initial settings. Then, click on 'Next' to proceed to the database settings. If you need to make any changes to the previous step (Application Settings), click on the 'Back' button.
3.3 Step3: Database Settings:
At this stage, you must enter the database credentials to connect the system. It is essential to provide the correct database-related settings under the Database Settings section. The required details include:
●	Database Host Name: Usually localhost for local development.
●	Port Number: The default port is usually 3306.
●	Database Username: Typically root when using XAMPP/WAMP.
●	Database Password: Leave blank if no password is set for MySQL.
●	Database Name: The name of the database where your system will store data.
●	Socket Name: The default socket path is /tmp/mysql.sock.
 
The application will continuously validate the provided database settings. If any value is incorrect, an error message will appear, prompting you to fix the issue.
Important Note: The Database Name must be unique and should not exist already. The application is designed to automatically create a new database using the provided name. Therefore, ensure you enter a database name that is not currently in use.
Once all values are correctly entered and validated, click "Next" to proceed to the Email Settings step.
3.4 Step4: Email Settings:
Since the system will send notifications and other information to users via email, it is essential to provide the correct email configuration during installation. This can be either a local email server configuration or a remote email service provider configuration.
At the bottom of the page, there is a "Check Configuration" button, which allows you to verify whether the provided email settings are working correctly.
Required Email Settings:
●	Mail Driver: The mail service to be used (e.g., SMTP, Sendmail, Mailgun).
●	Mail Host: The email server (e.g., smtp.gmail.com).
●	Mail Port: The port number used for sending emails (e.g., 465 for SSL or 587 for TLS).
●	Mail Username: The email address used for authentication.
●	Mail Password: The password or app-specific password for authentication.
●	Mail Encryption: The encryption type (e.g., SSL or TLS).
A test email address is pre-configured in the App\AppConfig.php file using the SYSTEM_EMAIL constant variable. You can modify it from there if needed.
After entering all the required information, click on the "Check Configuration" button.
●	If all details are correct, the system will send a test email to the configured test email address and display a success message.
●	If there is an error, you will need to review and correct the settings before proceeding.
Once the email configuration is successfully validated, click "Next" to proceed to the Final Review & Installation step.
 
Once all configurations have been entered correctly, the user can proceed to the Preview and Install step as the final stage of the installation process.
3.5 Step5: Preview and Install:
What Happens in This Step?
●	The application will display a preview of all the entered information for review.
●	If any information is incorrect or a mistake is found, you can edit the details using the "Edit" button.
●	Alternatively, you can click on the corresponding step to go back and modify specific settings.
●	Once everything is verified, click the "Install" button to begin the installation process.
Installation Process:
●	During installation, the system will display a progress bar with corresponding status updates.
●	Backend validation will check for any incorrect data before proceeding with the installation. If any errors are found, the process will stop, and an error message will be displayed.
●	Once the installation is successfully completed, a success message will appear, confirming that the application has been installed.
During the installation, the system will perform the following tasks:
•	Update environment variables such as the application name, app key, timezone, and other necessary configurations.
•	Generate the application key for encryption security if it was provided in the application settings.
•	Create the database using the provided database name.
•	Run database migrations to set up the required tables.
•	Seed initial data if required.
•	Create the admin user account based on the information provided during the installation.

After installation, you will be redirected to the login page, where you can access the system using the credentials set during the Admin Account Setup step.
 
4. How to integrate into your existing system:
1.	Please copy the following route definitions from my web.php file and add them to your Laravel project 
2.	Copy the InstallerController.php file from app/Http/Controllers and move it to your app/Http/Controllers directory.
3.	Copy the InstallerController.php file from app/Http/Controllers and move it to your app/Http/Controllers directory.
Open the User.php file from app/Models and copy the makeUser function. Add this function to your User.php model. If a function with the same name already exists in your model, you can rename it. This function creates an admin user in the users table using the credentials provided during installation. 
4.	Copy the AppConfig.php (this configuration file is usually used for common settings that users can easily change the configuration values) from the app folder and place it into your app folder.
5.	Copy the helper file InstallerHelper.php from app/Http/Helpers and place it into your app/Http/Helpers directory. If the Helpers folder does not exist in your system, you can either create a new Helpers folder or place the file inside the app directory. Then, proceed to the next step
- Open composer.json in your project root.
--Find the "autoload" section and add the Helpers file under "files".
-- Modify composer.json like this:
 
-- Run composer dump-autoload
 
6.	Copy the view files from resources/views (including main.blade.php, installer, installer-layouts, and the mail folder, which is used for email content where test emails are sent during email configuration) and place them into  resources/views folder.
7.	Copy the assets folder from the public directory, which includes the css, custom, jquery-validation, js, and webfonts folders. These folders contain several CSS and JS files. You can include specific parts of these files into your assets, depending on where you're keeping the asset files.
8.	Open the VerifyCsrfToken.php file from app/Http/Middleware and add the following routes under the $except array variable for installer/seed and installer/createUser, as shown in the screenshot below
 
5. IMPORTANT NOTE:
When integrating the installer into your existing system, you must set the value for INSTALLATION to "DONE" in line 517 of the app/Http/Controllers/InstallerController.php file, as shown in the screenshot below:
 

Additionally, use the isAppInstalled function from the app/Helpers/InstallerHelper.php file to verify whether the application is already installed or not.
 
6. Revision History
Revision History
Revision	Date	Author	Reason for Change
Installation_Manual_laravel_Easy_Installer_v1.0	25- Mar-2025	Prioranjan Chowdhury	First Version Release
