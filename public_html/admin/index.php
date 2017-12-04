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
 * Mar/2003: Modified to support Geeklog
 * Blaine Lang  blaine@portalparts.com*
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

$pagetitle = "Welcome to the FAQ Manager Control Panel";
$data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
$data .= "    <tr>\r\n";
$data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Select an option</font></th>\r\n";
$data .= "    </tr>\r\n";
$data .= "    <tr>\r\n";
$data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
$data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
$data .= "            <tr>\r\n";
$data .= "                <td width=\"85%\" valign=\"top\">\r\n";
$data .= "                <font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">\r\n";
$data .= "                <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"0\">\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td colspan=\"3\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>Category Management</b>:</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"category.php?op=add\">Add a Category</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Allows you to add a category to FAQ Manager</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"category.php?op=edit\">Edit a Category</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit an existing category</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"category.php?op=del\">Delete a Category</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Delete a category from FAQ Manager (Note all topics in this category <b>WILL</b> be lost)</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                </table>\r\n";
$data .= "                <br \>\r\n";
$data .= "                <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"0\">\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td colspan=\"3\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>FAQ Management</b>:</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"topics.php?op=add\">Add an FAQ Topic</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Add a topic to an FAQ category</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit any of the existing topics</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"topics.php?op=del\">Delete an FAQ Topic</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Delete an existing topic</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                </table>\r\n";
$data .= "                <br \>\r\n";
$data .= "                <table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"0\">\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td colspan=\"3\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>Template Management</b>:</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=1\">Global</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the global template, this template is applied to <b>ALL</b> pages</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=2\">Index</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template used for the front page (category list) of FAQ Manager</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=3\">Category</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template used for the viewing category page (FAQ topic list)</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=4\">FAQ</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template used to display a single FAQ topic</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=5\">Search</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the search form template</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=6\">Search Results</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the search results template</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=7\">No Matches</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template used when searching returns no results</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=8\">Printer Page</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template used to display the printer-friendly page</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=9\">MySQL Error Page</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template which is used to display the MySQL status</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=10\">General Error Page</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template for general error messages such as user input errors</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"templates.php?op=edit&tmp=11\">Query Log</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Edit the template which is used to display the SQL query log</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                    <tr>\r\n";
$data .= "                        <td valign=\"top\" width=\"24%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp;<a href=\"misc.php?op=recount\">Recount FAQ Topics</a></font></td>\r\n";
$data .= "                        <td valign=\"top\" width=\"1%\" nowrap=\"nowrap\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">&nbsp;&nbsp; - </td>\r\n";
$data .= "                        <td><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Allows you to recount the number of topics in each category</font></td>\r\n";
$data .= "                    </tr>\r\n";
$data .= "                </table>\r\n";
$data .= "                </td>\r\n";
$data .= "            </tr>\r\n";
$data .= "        </table>\r\n";
$data .= "        </td>\r\n";
$data .= "    </tr>\r\n";
$data .= "</table>\r\n";

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}

?>