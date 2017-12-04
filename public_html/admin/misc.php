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

$link_id = db_connect();
if (!$link_id)
{
    $pagetitle = "MySQL Server Error";
    $data = msg("There was an error connecting to the database.<br><br><b>Error Number</b>: " . $MYSQL_ERRNO . "<br><b>Error Message</b>: " . $MYSQL_ERROR, "MySQL Error", "MySQL Server Error");
}
else
{
    if ($op == "recount")
    {
        $result = query("SELECT catID, name, description FROM " . $tbprefix . "_categories ORDER BY name");
        
        if (mysql_num_rows($result) == 0)
        {
            // No categories exist
            $pagetitle = "User Error";
            $data = msg("You cannot use this section until you have added at least 1 category.<br><br><a href=\"category.php?op=add\">&laquo; Go and add a category</a>", "<a href=\"topics.php?op=$op\">" . $act . " an FAQ Topic</a> &raquo; User Error");
        }
        else
        {
            // Display the list of categories
            $pagetitle = "Select a Category";

            $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
            $data .= "<input type=\"hidden\" name=\"op\" value=\"process\">\r\n";
            $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Select a category</font></th>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
            $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";

            while ($query_data = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $data .= "            <tr>\r\n";
                
                if ($selected == FALSE)
                {
                    $data .= "                <td width=\"1%\" align=\"left\" valign=\"top\"><input type=\"radio\" name=\"id\" value=\"" . $query_data["catID"] . "\" checked=\"checked\"></td>\r\n";
                    $selected = TRUE;
                }
                else
                {
                    $data .= "                <td width=\"1%\" align=\"left\" valign=\"top\"><input type=\"radio\" name=\"id\" value=\"" . $query_data["catID"] . "\"></td>\r\n";
                }

                $data .= "                <td width=\"4%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $query_data["name"] . "</font></td>\r\n";
                $data .= "                <td width=\"85%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $query_data["description"] . "</font></td>\r\n";
                $data .= "            </tr>\r\n";
            }

            $data .= "            <tr align=\"center\">\r\n";
            $data .= "                <td colspan=\"3\"><input type=\"submit\" value=\"Recount!\"></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"3\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Recount FAQ Topics</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "        </table>\r\n";
            $data .= "        </td>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "</table>\r\n";
            $data .= "</form>\r\n";
        }
    }
    else if ($op == "process")
    {
        $id = param("id", "POST");
        $result = query("SELECT * FROM " . $tbprefix . "_topics WHERE catID = '$id'");
        $total = mysql_num_rows($result);
        query("UPDATE " . $tbprefix . "_categories SET total = " . $total . " WHERE catID = '$id'");
        $result = query("SELECT name FROM " . $tbprefix . "_categories WHERE catID = '$id'");
        list($name) = mysql_fetch_row($result);

        // Topics Recounted!
        $pagetitle = "Topics Recounted";
        $data = msg("The topics have been recounted, the category &quot;" . $name . "&quot; had a total of $total topics", "<a href=\"misc.php?op=recount\">Recount FAQ Topics</a> &raquo; Topics Recounted", "Topics Recounted");
    }   
    else if($op == "update")
    {
        $v = rawurlencode($version);
        header("Location: http://www.aquonics.com/updates/faqman.php?v=$v");
        exit();
    }
    else
    {
        header("Location: ./index.php");
        exit();
    }
}

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}
?>