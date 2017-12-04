<?php

/*
 * FAQ Manager Version 2
 * Copyright (c) 2001 Aquonics Scripting
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

require_once("../../lib-common.php"); // Path to your lib-common.php

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

if ($op == "edit")
{
    $error = FALSE;
    $tmp = param("tmp", "GET");
    if ($tmp < 1 || $tmp > 11)
    {
        header("Location: ./index.php");
    }

    if (!$fp = fopen($files[$tmp], "r"))
    {
        $fdata = "There was an error loading the template, please ensure that the file " . $files[$tmp] . " is readable";
        $error = TRUE;
    }
    else
    {
        $fdata = fread($fp, filesize($files[$tmp]));

        // Convert entities so they display correctly
        $fdata = preg_replace("/&(.+?);/", "&amp;\\1;", $fdata);
        fclose($fp);
    }

    $pagetitle = "Edit the " . $templates[$tmp] . " Template";

    $data = "<form action=\"$PHP_SELF\" method=\"post\" name=\"templates\">\r\n";
    $data .= "<input type=\"hidden\" name=\"op\" value=\"update\">\r\n";
    $data .= "<input type=\"hidden\" name=\"tmp\" value=\"" . $tmp . "\">\r\n";
    $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Edit the " . $templates[$tmp] . " Template</font></th>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "    <tr>\r\n";
    $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
    $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td align=\"left\">\r\n";
    $data .= "                <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\r\n";
    $data .= "                    <tr>\r\n";
    $data .= "                        <th align=\"left\" colspan=\"2\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Available Placemarkers</font></th>\r\n";
    $data .= "                    </tr>\r\n";

    foreach ($vars[$tmp] as $key => $var)
    {
        $data .= "                    <tr>\r\n";
        $data .= "                        <td align=\"left\" width=\"15%\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&#123;" . $key . "&#125;</font></td>\r\n";
        $data .= "                        <td align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $var . "</font></td>\r\n";
        $data .= "                    </tr>\r\n";
    }

    $data .= "                </table>\r\n";
    $data .= "                </td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "            <tr>\r\n";
    $data .= "                <td align=\"center\"><textarea name=\"fdata\" cols=\"87\" rows=\"25\" wrap=\"soft\">{FDATA}</textarea></td>\r\n";
    $data .= "            </tr>\r\n";
    
    if ($error == FALSE)
    {
        if ($info[$tmp] != "")
        {
            $data .= "            <tr>\r\n";
            $data .= "                <td align=\"center\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $info[$tmp] . "</font></td>\r\n";
            $data .= "            </tr>\r\n";
        }

        $data .= "            <tr align=\"center\">\r\n";
        $data .= "                <td><input type=\"submit\" value=\"Update Template!\" name=\"submit\"> <input type=\"button\" name=\"wrapping\" value=\"Disable Wrapping\" onclick=\"wrap()\"></td>\r\n";
        $data .= "            </tr>\r\n";
    }
    
    $data .= "            <tr>\r\n";
    $data .= "                <td bgcolor=\"#FFFFFF\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Edit the " . $templates[$tmp] . " Template</font></td>\r\n";
    $data .= "            </tr>\r\n";
    $data .= "        </table>\r\n";
    $data .= "        </td>\r\n";
    $data .= "    </tr>\r\n";
    $data .= "</table>\r\n";
    $data .= "</form>\r\n";
}
else if ($op == "update")
{
    $fdata  = trim(param("fdata", "POST"));
    $tmp    = param("tmp", "POST");
    $error  = FALSE;
    $tag    = NULL;

    if (get_magic_quotes_gpc() == true)
    {
        $fdata = stripslashes($fdata);
    }

    foreach ($required[$tmp] as $var)
    {
        if (!ereg($var, $fdata))
        {
            $error = TRUE;
            $tag = $var;
            break;
        }
    }

    if (empty($fdata))
    {
        // No data was entered
        $pagetitle = "User Error";
        $data = msg("You didn't enter any data for the template<br><br><a href=\"javascript:history.go(-1);\">&laquo; Go back and add some content</a>", "<a href=\"templates.php?op=edit&tmp=$tmp\">Edit " . $templates[$tmp] . " template</a> &raquo; User Error");
    }
    else if ($error == TRUE)
    {
        // No data was entered
        $pagetitle = "User Error";
        $data = msg("The required place marker " . $tag . " is missing<br><br><a href=\"javascript:history.go(-1);\">&laquo; Go back and enter it into the template</a>", "<a href=\"templates.php?op=edit&tmp=$tmp\">Edit " . $templates[$tmp] . " Template</a> &raquo; User Error");
    }
    
    else
    {
        if (!$fp = fopen($files[$tmp], "w"))
        {
            // The template could not be opened for writing
            $pagetitle = "Server Error";
            $data = msg("There was an error saving the template, please ensure that the file " . $templates[$tmp] . " is writeable", "<a href=\"templates.php?op=edit&tmp=$tmp\">Edit " . $templates[$tmp] . " Template</a> &raquo; User Error", "Server Error");
        }
        else
        {
            // Convert entities back to normal
            $fdata = preg_replace("/&amp;(.+?);/", "&\\1;", $fdata);
            fwrite($fp, $fdata);
            fclose($fp);

            // The template was successfully updated!
            $pagetitle = "Template Updated!";
            $data = msg("The template was succesfully updated!", "<a href=\"templates.php?op=edit&tmp=$tmp\">Edit " . $templates[$tmp] . " Template</a> &raquo; Template Updated!", "Template Updated!");
        }
    }
}
else
{
    header("Location: ./index.php");
    exit();
}

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}

?>