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

$link_id = db_connect();
if (!$link_id)
{
    $pagetitle = "MySQL Server Error";
    $data = msg("There was an error connecting to the database.<br><br><b>Error Number</b>: " . $MYSQL_ERRNO . "<br><b>Error Message</b>: " . $MYSQL_ERROR, "MySQL Error", "MySQL Server Error");
}
else
{
    $op = param("op", "GET");

    if (empty($op))
    {
        $op = param("op", "POST");
    }

    // Display the Add a category form
    if ($op == "add")
    {
        $pagetitle = "Add a Category";

        $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
        $data .= "<input type=\"hidden\" name=\"op\" value=\"addcat\">\r\n";
        $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Add a Category</font></th>\r\n";
        $data .= "    </tr>\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
        $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category Name</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"name\" size=\"60\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category Description</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"description\" cols=\"59\" rows=\"8\"></textarea></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr align=\"center\">\r\n";
        $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Add it!\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Add a Category</font></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "        </table>\r\n";
        $data .= "        </td>\r\n";
        $data .= "    </tr>\r\n";
        $data .= "</table>\r\n";
        $data .= "</form>\r\n";
    }
    else if ($op == "addcat")
    {
        // Get the variables and clean them up
        $name           = trim(param("name", "POST"));
        $description    = trim(param("description", "POST"));
        $description    = str_replace("\r\n", "", $description);

        $name              = str_replace("\"", "&quot;", $name);

        if (empty($name))
        {
            // The user didn't enter a name for the category
            $pagetitle = "User Error";
            $data = msg("You didn't enter a category name.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a name</a>", "<a href=\"category.php?op=add\">Add a category</a> &raquo; User Error");
        }
        else if (empty($description))
        {
            // The user didn't enter a description for the category
            $pagetitle = "User Error";
            $data = msg("You didn't enter a category description.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a description</a>", "<a href=\"category.php?op=add\">Add a category</a> &raquo; User Error");
        }
        else
        {
            // Run the query and insert the data
            $name = preparefordb($name);
            $description = preparefordb($description);
            query("INSERT INTO " . $tbprefix . "_categories (catID, name, description, total) VALUES ('', '" . htmlspecialchars($name) . "', '" . htmlspecialchars($description) . "', '0')");
            $id = mysql_insert_id($link_id);

            $pagetitle = "Category Created!";
            $data = msg("The new category was successfully created!<br><br>You can view the category by clicking <a href=\"../index.php?op=cat&c=" . $id . "\" target=\"_blank\">here</a>", "<a href=\"category.php?op=add\">Add a category</a> &raquo; Category Created!", "Category Created!");
        }
    }
    else if ($op == "edit" || $op == "del")
    {
        $act = $op == "edit" ? "Edit" : "Delete";
        $nextact = $op == "edit" ? "editform" : "confirm";
        $selected = FALSE;

        $result = query("SELECT catID, name, description FROM " . $tbprefix . "_categories ORDER BY name");
        
        if (mysql_num_rows($result) == 0)
        {
            // No categories exist
            $pagetitle = "User Error";
            $data = msg("You cannot use this section until you have added at least 1 category.<br><br><a href=\"category.php?op=add\">&laquo; Go and add a category</a>", "<a href=\"category.php?op=$op\">" . $act . " a category</a> &raquo; User Error");
        }
        else
        {
            // Display the list of categories
            $pagetitle = "Select a Category";

            $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
            $data .= "<input type=\"hidden\" name=\"op\" value=\"" . $nextact . "\">\r\n";
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
            $data .= "                <td colspan=\"3\"><input type=\"submit\" value=\"Proceed!\"></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"3\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; " . $act . " an existing Category</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "        </table>\r\n";
            $data .= "        </td>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "</table>\r\n";
            $data .= "</form>\r\n";
        }
    }
    else if ($op == "editform")
    {
        // Display the edit form
        $id = param("id", "POST");
        $result = query("SELECT name, description FROM " . $tbprefix . "_categories WHERE catID = '$id'");

        list($name, $description) = mysql_fetch_row($result);

        $pagetitle = "Edit an existing Category";

        $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
        $data .= "<input type=\"hidden\" name=\"op\" value=\"update\">\r\n";
        $data .= "<input type=\"hidden\" name=\"id\" value=\"$id\">\r\n";
        $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Edit an existing Category</font></th>\r\n";
        $data .= "    </tr>\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
        $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category Name</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"name\" size=\"60\" value=\"$name\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category Description</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"description\" cols=\"59\" rows=\"8\">$description</textarea></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr align=\"center\">\r\n";
        $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Edit it!\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "        </table>\r\n";
        $data .= "        </td>\r\n";
        $data .= "    </tr>\r\n";
        $data .= "</table>\r\n";
        $data .= "</form>\r\n";
    }
    else if ($op == "update")
    {
        // Get the variables and clean them up
        $id             = param("id", "POST");
        $name           = trim(param("name", "POST"));
        $description    = trim(param("description", "POST"));
        $description    = str_replace("\r\n", "", $description);

        $name              = str_replace("\"", "&quot;", $name);

        if (empty($name))
        {
            // The user didn't enter a name for the category
            $pagetitle = "User Error";
            $data = msg("You didn't enter a category name.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a name</a>", "<a href=\"category.php?op=add\">Add a category</a> &raquo; User Error");
        }
        else if (empty($description))
        {
            // The user didn't enter a description for the category
            $pagetitle = "User Error";
            $data = msg("You didn't enter a category description.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a description</a>", "<a href=\"category.php?op=add\">Add a category</a> &raquo; User Error");
        }
        else
        {
            // Run the query and update the data
            $name = preparefordb($name);
            $description = preparefordb($description);     
            query("UPDATE " . $tbprefix . "_categories SET name = '" . htmlspecialchars($name) . "', description = '" . htmlspecialchars($description) . "' WHERE catID = '$id'");

            $pagetitle = "Category Updated";
            $data = msg("The category was successfully updated!", "<a href=\"category.php?op=edit\">Edit an existing category</a> &raquo; Category Updated!", "Category Updated!");
        }
    }
    else if ($op == "confirm")
    {
        // Ask the user to confirm the deletion
        $id = param("id", "POST");
        $result = query("SELECT name FROM " . $tbprefix . "_categories WHERE catID = '$id'");
        list($name) = mysql_fetch_row($result);

        $pagetitle = "Are you Sure?";
        $data = msg("Are you sure you want to delete the category &quot;" . $name . "&quot;?<br><b>Warning</b>: All topics in this category will also be lost<br><p><form action=\"$PHP_SELF\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"op\" value=\"remove\"><input type=\"submit\" name=\"answer\" value=\"Yes\"> <input type=\"submit\" name=\"answer\" value=\"No\"></form>", "<a href=\"category.php?op=del\">Delete an existing category</a> &raquo; Confirm Deletion", "Confirm Deletion");
    }
    else if ($op == "remove")
    {
        // Remove the actual data
        $answer = param("answer", "POST");
        $id     = param("id", "POST");
        
        if ($answer == "No")
        {
            header("Location: ./index.php");
            exit;
        }

        query("DELETE FROM " . $tbprefix . "_categories WHERE catID = '$id'");
        query("DELETE FROM " . $tbprefix . "_topics WHERE catID = '$id'");

        $pagetitle = "Category Deleted!";
        $data = msg("The category and any related topics have been removed from the database", "<a href=\"category.php?op=del\">Delete an existing category</a> &raquo; Category Deleted!", "Category Deleted!");
    }
    else
    {
        header("Location: ./index.php");
        exit;
    }
}

mysql_close();

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}

?>