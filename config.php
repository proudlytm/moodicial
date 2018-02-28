<?php
 // This array saves the website information
 $info = array (
  'title'			=> 'Moodicial'
 );

 // This array saves the website credentials.
 $credentials = array (
  'hostname'     => '127.0.0.1',
  'port'         => '3306',
  'username'     => 'root',
  'password'     => '',
  'db'           => 'moodicial',
  'ptable'       => 'moodicial_posts',
  'ctable'       => 'moodicial_comments',
  'mtable'       => 'moodicial_metrics'
 );

 // Whether to enable or not automatic language switch.
 $autolanguage = true;

 if ($autolanguage)
 {
  // Automatic language detection code block
  require_once("lang/detect.php");
 }
 else
 {

  /* Choose the website language

  For more information and different options take a look at the 'lang' folder in the root of your installation.*/

  $language = 'en_US';

 }

 // Server document root. Set this to the path to your website files, omitting the root dir. If you put them in '/var/www/moodicial' write just '/moodicial'.
 $path = '';
 // Should people be able to report?
 $reports = true;
 // Max amount of reports
 $maxrep = 5;
 // Allow empty posts? (without text)
 $allowempty = false;

 // Enable metrics?
 $metrics = true;
 // What should we track?
 $metricsset = array (
   'visits'      => 'yes'
 );

 // From now on, don't edit anything as you could break the whole website.


 // Set the URL that would point to the root of the server and hold the index.
 $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . $path;

 // Create the PDO object for the MySQL server connection.
  try {
   $server = new PDO('mysql:host=' . $credentials['hostname'] . ';port=' . $credentials['port'] . ';dbname=' . $credentials['db'] . ';charset=utf8', $credentials['username'], $credentials['password']);
  } catch (\Exception $errcondb) {
   try {
   $server = new PDO('mysql:host=' . $credentials['hostname'] . ';port=' . $credentials['port'] . ';charset=utf8', $credentials['username'], $credentials['password']);
   $server->query("CREATE DATABASE " . $credentials['db']);
   header("location: " . $root . "/install.php");
   } catch (\Exception $errcon) {
    die("The connection to the SQL server is totally broken, please double check your settings in 'config.php'.");
   }
  }

?>
