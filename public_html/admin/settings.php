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

error_reporting(0);

// A nice function to help us die gracefully if there is an error
function include_error($type)
{
    echo "<html>\r\n";
    echo "<body bgcolor=\"#FFFFFF\">\r\n";
    echo "<center><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\">There was an error loading the " . $type . " file.</font></center>\r\n";
    echo "</body>\r\n";
    echo "</html>";
}

if (!include("./header.lib.php"))
{
    die(include_error("header"));
}

$op = param("op", "GET");
if (empty($op))
{
    $op = param("op", "POST");
}

if ($op == "update")
{
    $new = param("new", "POST");
    $new = array_map("trim", $new); // Trim each item in the array

    if (empty($new["title"]))
    {
        // The user didn't enter a title
        $pagetitle = "User Error";
        $data = msg("You didn't enter a title.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a title</a>", "<a href=\"settings.php\">Edit the Scripts Settings</a> &raquo; User Error");
    }
    elseif (empty($new["dbhost"]) || empty($new["dbuser"]) || empty($new["dbpass"]) || empty($new["dbname"]) || empty($new["tbprefix"]))
    {
        // The user didn't enter the db details
        $pagetitle = "User Error";
        $data = msg("You didn't enter the required database details.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter the details</a>", "<a href=\"settings.php\">Edit the Scripts Settings</a> &raquo; User Error");
    }
    elseif (!empty($new["adminpass"]) && $new["adminpass"] != $new["adminpass2"])
    {
        // The users new passwords don't match
        $pagetitle = "User Error";
        $data = msg("The passwords do not match.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and correct this</a>", "<a href=\"settings.php\">Edit the Scripts Settings</a> &raquo; User Error");
    }
    else if(!empty($new["adminpass"]) && strlen($new["adminpass"]) < 5)
    {
        // The users new password is too short
        $pagetitle = "User Error";
        $data = msg("The new password is too short.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and correct this</a>", "<a href=\"settings.php\">Edit the Scripts Settings</a> &raquo; User Error");
    }
    else
    {
        if (!$fp = fopen("../data/settings.inc.php", "w"))
        {
            // The users new password is too short
            $pagetitle = "Server Error";
            $data = msg("There was an error saving the settings, please ensure that the file ../data/settings.inc.php is writeable", "<a href=\"settings.php\">Edit the Scripts Settings</a> &raquo; User Error", "Server Error");
        }
        else
        {
            // Create the file data
            $fdata ="<?php\r\n\r\n";
            $fdata .= "\$title      = \"" . $new["title"] . "\";\r\n\r\n";

            if (!empty($new["adminpass"]))
            {
                $fdata .= "\$adminpass  = \"" . md5($new["adminpass"]) . "\";\r\n";
            }
            else
            {
                $fdata .= "\$adminpass  = \"" . $adminpass . "\";\r\n";
            }
            $fdata .= "\$dbhost     = \"" . $new["dbhost"] . "\";\r\n";
            $fdata .= "\$dbuser     = \"" . $new["dbuser"] . "\";\r\n";
            $fdata .= "\$dbpass     = \"" . $new["dbpass"] . "\";\r\n";
            $fdata .= "\$dbname     = \"" . $new["dbname"] . "\";\r\n";
            $fdata .= "\$tbprefix   = \"" . $new["tbprefix"] . "\";\r\n\r\n";
            $fdata .= "\$debug      = " . $new["debug"] . ";\r\n";
            $fdata .= "\$sql        = " . (string) $new["sql"] . ";\r\n\r\n";
            $fdata .= "?" . chr(62);

            fwrite($fp, $fdata);
            fclose($fp);

            // The users new password is too short
            $pagetitle = "Settings Updated!";
            $data = msg("The Scripts Settings have been updated, if you have changed the admin password you will need to log in again", "<a href=\"settings.php\">Edit Scripts Settings</a> &raquo; Settings Updated!", "Settings Updated!");
        }
    }
}
else
{
    $err_levels = array("0"     => "Disable Error Reporting",
                        "2039"  => "All Errors &amp; Warnings, but not Notices",
                        "2047"  => "All Errors, Warnings &amp; Notices",
                        "1"     => "Fatal Errors Only",
                        "8"     => "Notices Only");
    
    $err_options = "";
    foreach ($err_levels as $key => $var)
    {
        if ((string) $key == (string) $debug)
        {
            $err_options .= "<option value=\"$key\" selected>$var</option>";
        }
        else
        {
            $err_options .= "<option value=\"$key\">$var</option>";
        }
    }

    $sql_levels = array("0" => "Disable SQL Logging",
                        "1" => "Enable SQL Logging",
                        "2" => "User Controlled SQL Logging");
    $sql_options = "";
    foreach ($sql_levels as $key => $var)
    {
        if ((string) $key == (string) $sql)
        {
            $sql_options .= "<option value=\"$key\" selected>$var</option>";
        }
        else
        {
            $sql_options .= "<option value=\"$key\">$var</option>";
        }
    }
                    
    $pagetitle = "Edit the Scripts Settings";

    $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
    $data .= "<input type=\"hidden\" name=\"op\" value=\"update\">\r\n";
    $data .= "<table width=\"75%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Edit the Script Settings</font></th>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
    $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">FAQ Manager Name</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"new[title]\" size=\"60\" value=\"$title\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td colspan=\"2\" align=\"left\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Database Settings</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Database Server</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"new[dbhost]\" size=\"60\" value=\"$dbhost\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Database User</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"new[dbuser]\" size=\"60\" value=\"$dbuser\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Database Password</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"password\" name=\"new[dbpass]\" size=\"60\" value=\"$dbpass\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Database Name</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"new[dbname]\" size=\"60\" value=\"$dbname\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Table Prefix</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"new[tbprefix]\" size=\"60\" value=\"$tbprefix\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td width=\"10%\" align=\"left\" nowrap>&nbsp;</td>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>Note</b>: If you edit any of the 5 values above, remember to make the correct changes to your database or table</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td colspan=\"2\" align=\"left\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Error Settings</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Error Reporting Level</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><select name=\"new[debug]\">" . $err_options . "</select></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">SQL Log</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><select name=\"new[sql]\">" . $sql_options . "</select></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap>&nbsp;</th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">If you select User Controlled the user can view the SQL log by adding &sql=1 to the URL</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td colspan=\"2\" align=\"left\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Security Settings</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Control Panel Password</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"password\" name=\"new[adminpass]\" size=\"60\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Retype New Password</font></th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><input type=\"password\" name=\"new[adminpass2]\" size=\"60\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <th width=\"10%\" align=\"left\" nowrap>&nbsp;</th>\r\n";
    $data .= "                <td width=\"80%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">If you do not want to change the password leave the two fields above blank</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr align=\"center\">\r\n";
    $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Update Settings!\"></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Edit the Scripts Settings</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "        </table>\r\n";
    $data .= "        </td>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "</table>\r\n";
    $data .= "</form>\r\n";
}

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}
?>