<?php
/**
 * @Created by Prioranjan Chowdhury
 * @Email: sumon.sustcse@gmail.com
 * @created on 20th February, 2025
 */

namespace App;

/**
 * Class AppConfig
 * @version 1.0.0
 * @author Prioranjan Chowdhury <sumon.sustcse@gmail.com>
 * @package App\Http
 */
class AppConfig
{
    const GENERAL_PRODUCT_OWNER         = 'Prioranjan Chowdhury';
    const PRODUCT_OWNER_LINK            = '';
    const GENERAL_PRODUCT_NAME          = 'Laravel Easy Installer';
	const PRODUCT_SUB_TITLE				='Effortless Laravel Installation in Just a Few Clicks';
    const GENERAL_PRODUCT_VERSION       = 'V1.0';
	
	const SUPPORT_MIN_PHP_VERSION       = '8.1';
	const SUPPORT_MIN_MYSQL_VERSION       = '8.0';
    const USER_SYSTEM          			= 'SYSTEM';

    const SYSTEM_INSTALLER              ='Installer';
	const SYSTEM_EMAIL             ='installer@example.com';
	const REQUIRED_EXTENSIONS =  ['gd', 'mysqli', 'mbstring', 'openssl', 'pdo','shell_exec'];
    const SESSION_KN_APP_INSTALLED          = 'app_installed' ;
	const DEFAULT_ENUM_VAL_DONE   = 'DONE' ;
   
}
