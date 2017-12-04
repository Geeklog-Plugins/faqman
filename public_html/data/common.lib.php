<?php

/*
 * FAQ Manager Version 2
 * Copyright (c) 2002 Aquonics Scripting
 * -------------------------------------
 * You may not remove the copyright or
 * redistribute the script in any form.
 * This program is Freeware, please read the
 * license at http://www.aquonics.com/license.php
 *
 * Visit www.aquonics.com for more top scripts, free and custom.
 *
 * Authors: Stephen Ball <stephen@aquonics.com>
 *
 */
$version = "2.0.2"; // Script version

// Connects to the database 
function db_connect()
{
    static $MYSQL_ERROR = NULL, $MYSQL_ERRNO = NULL;
    global $dbhost, $dbuser, $dbpass, $dbname;
    global $MYSQL_ERROR, $MYSQL_ERRNO;

    $link_id = mysql_connect($dbhost, $dbuser, $dbpass);

    if (!$link_id)
    {
        $MYSQL_ERRNO = mysql_errno();
        $MYSQL_ERROR = mysql_error();
        return 0;
    }
    else if (!mysql_select_db($dbname))
    {
        $MYSQL_ERRNO = mysql_errno();
        $MYSQL_ERROR = mysql_error();
        return 0;
    }
    else
    {
        return $link_id;   
    }
}

// Finds out the startime time
function starttime()
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;

    return $starttime;
}

// Finds out the end time
function endtime($starttime)
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);

    $totaltime = round($totaltime, 3);

    return $totaltime;
}

// A function to handle the dynamic templates
function dynam_template($file, $data = array())
{
    if (!is_array($data))
    {
        return false;
    }

    if(!$fp = fopen($file, "r"))
    {
        $file = basename($file);
        die(include_error($file));
    }
    else
    {
        $template = fread($fp, filesize($file));
        fclose($fp);

        foreach ($data as $key => $var)
        {
            $key = "{" . $key . "}";
            $template = str_replace($key, $var, $template);
        }
        
        // Find out the dynamic block!
        $begin           = strpos($template, "<!-- BEGIN DYNAMIC BLOCK -->");
        $end             = strpos($template, "<!-- END DYNAMIC BLOCK -->") + 26;
        $dynam['top']    = substr($template, 0, $begin);
        $dynam['block']  = substr($template, $begin, $end - $begin);
        $dynam['bottom'] = substr($template, $end);

        return $dynam;
    }   
}

// A function to parse the dynamic blocks
function parse_block($block, $data = array())
{
    if (!is_array($data))
    {
        return false;
    }

    foreach ($data as $key => $var)
    {
        $key = "{" . $key . "}";
        $block = str_replace($key, $var, $block);
    }

    return $block;
}

// A function to handle the static templates
function template($file, $data = array())
{
    if (!is_array($data))
    {
        return false;
    }

    if(!$fp = fopen($file, "r"))
    {
        $file = basename($file);
        die(include_error($file));
    }
    else
    {
        $template = fread($fp, filesize($file));
        fclose($fp);

        foreach ($data as $key => $var)
        {
            $key = "{" . $key . "}";
            $template = str_replace($key, $var, $template);
        }

        return $template;
    }
}

$QUERY_COUNT = 0;
// A function which counts the queries
function query($query)
{
    static $QUERY_COUNT = 0;
    global $QUERY_COUNT, $sql_log;

    $QUERY_COUNT++;
    $sql_log[] = $query;

    return mysql_query($query);
}

// Generates the category jumps
function generatecjump()
{
    global $PHP_SELF, $tbprefix;

    $result = query("SELECT catID, name FROM " . $tbprefix . "_categories ORDER BY name");

    if (mysql_num_rows($result) == 1)
    {
        return "&nbsp;";
    }

    $html = "<form method=\"post\">";
    $html .= "<select name=\"cjump\" onchange=\"jumpMenu(this)\">";
    $html .= "<option value=\"$PHP_SELF\">Category Jump:</option>";
    $html .= "<option value=\"$PHP_SELF\">--------</option>";

    while($query_data = mysql_fetch_array($result))
	{
        $html .= "<option value=\"$PHP_SELF?op=cat&c=" . $query_data["catID"] . "\">" . $query_data["name"] . "</option>";
    }

    $html .= "</select>";
    $html .= "</form>";

    return $html;
}

// A function to return the correct variable
function param($name, $method)
{
   global $debug;

   // Turn off error reporting as this function will return errors alot of the time
   error_reporting(0);

   // Use ternary, check if the method exists in the super global area, ie _METHOD
   // If so return the value from that array, otherwise use the GLOBALS array
   // along with the HTTP_METHOD_VARS index to return the correct value
   $var = isset($_{$method}) ? $_{$method}[$name] : $GLOBALS["HTTP_{$method}_VARS"][$name];
   
   // Reset the error reporting level
   error_reporting($debug);
   return $var;
}

/* 
Functions for use in the Admin CP only
*/

// Checks the user has access to the CP
function auth()
{
    global $HTTP_COOKIE_VARS, $adminpass;

    if ($HTTP_COOKIE_VARS['faq_admin'] != $adminpass)
    {
        header("Location: $PHP_SELF?op=out");
    }
}

// Build query log
function build_query($log)
{
    if (!is_array($log) || $log[0] == "")
    {
        return false;
    }

    $queries = "<br>\r\n";
    $queries .= "<table border=\"0\" width=\"75%\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
    $queries .= "    <tr>\r\n";
    $queries .= "        <th align=\"left\" bgcolor=\"#5485C9\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Queries Used</font></th>\r\n";
    $queries .= "    </tr>\r\n";
    $queries .= "    <tr>\r\n";
    $queries .= "        <td bgcolor=\"#FFFFFF\">\r\n";
    $queries .= "        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">\r\n";

    foreach ($log as $entry)
    {
        $queries .= "    <tr>\r\n";
        $queries .= "        <td width=\"100%\" bgcolor=\"#FFFFFF\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><i>" . htmlspecialchars($entry) . "</i></font></td>\r\n";
        $queries .= "    </tr>\r\n";    
    }

    $queries .= "        </table>\r\n";
    $queries .= "        </td>\r\n";
    $queries .= "    </tr>\r\n";
    $queries .= "</table>\r\n";

    return $queries;
}

// A function to display an error message
function msg($msg, $nav, $title = "User Error")
{
    $data = "<br><p><table border=\"0\" width=\"95%\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <th align=\"left\" bgcolor=\"#5485C9\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; " . $title . "</font></th>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
    $data .= "        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $msg . "</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; " . $nav . "</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "        </table>\r\n";
    $data .= "        </td>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "</table>\r\n";

    return $data;
}

function preparefordb($message) {
    if(!get_magic_quotes_gpc() ) {
	    $message = addslashes($message);
	}	
	return $message;
}




// Enable GZIP output compression if possible
$PHP_Ver = phpversion();
$gzip = "Disabled";

if ($PHP_Ver >= "4.0.4pl1" && extension_loaded("zlib"))
{
    // Start the GZIP Compression
    ob_start("ob_gzhandler");
    $gzip = "Enabled";
}
else if ($PHP_Ver > "4.0")
{
    // Check if the users browser allows encoded content and that zlib is loaded
    if(strstr(param("HTTP_ACCEPT_ENCODING", "SERVER"), "gzip") && extension_loaded("zlib"))
    {
        // Start GZIP and sent the correct HTTP header
        ob_start();
        ob_implicit_flush(0);

        // Fix this error later
        #header("Content-Encoding: gzip");
        #header("Accept-Encoding: gzip");
        #$gzip = "Enabled";
    }
}

?>