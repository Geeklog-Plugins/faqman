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
    $PHP_SELF   = param("PHP_SELF", "SERVER");
    $op         = param("op", "GET");

    if (empty($op))
    {
        $op = param("op", "POST");
    }

    if ($op == "add")
    {
        $result = query("SELECT catID, name FROM " . $tbprefix . "_categories ORDER BY name");
        
        if (mysql_num_rows($result) == 0)
        {
            // No categories exist
            $pagetitle = "User Error";
            $data = msg("You cannot use this section until you have added at least 1 category.<br><br><a href=\"category.php?op=add\">&laquo; Go and add a category</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else
        {
            // Categories exist so show a list
            $options = "";
            while ($query_data = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $options .= "<option value=\"" . $query_data["catID"] . "\">" . $query_data["name"] . "</option>";
            }

            $pagetitle = "Add an FAQ Topic";

            $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
            $data .= "<input type=\"hidden\" name=\"op\" value=\"insert\">\r\n";
            $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Add an FAQ Topic</font></th>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
            $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category</font></th>\r\n";
            $data .= "                <td width=\"80%\" align=\"left\"><select name=\"cat\">" . $options . "</select></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Question</font></th>\r\n";
            $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"question\" size=\"60\"></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <td width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>Answer</b><br><small>HTML can be used<br>PHP can be used</small></font></td>\r\n";
            $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"answer\" cols=\"59\" rows=\"8\"></textarea></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Keywords</font></th>\r\n";
            $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"keywords\" cols=\"59\" rows=\"8\"></textarea></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap>&nbsp;</th>\r\n";
            $data .= "                <td width=\"80%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">When using keywords remember that MySQL will not return results where the word appears in more than 50% of the rows. Once MySQL 4.0.1 is released Boolean search can be used to overcome this.<br><br>Seperate each word with a space</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr align=\"center\">\r\n";
            $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Add it!\"></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Add an FAQ Topic</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "        </table>\r\n";
            $data .= "        </td>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "</table>\r\n";
            $data .= "</form>\r\n";
        }
    }
    else if($op == "insert")
    {
        // Get the variables and clean them up
        $cat        = param("cat", "POST");
        $question   = trim(param("question", "POST"));
        $answer     = trim(param("answer", "POST"));
        $keywords   = trim(param("keywords", "POST"));

        $question   = str_replace("\"", "&quot;", $question);

        // Define variables
        $error  = 0;
        $word   = NULL;

        // Check to see if the user has entered a "stopword" or short word
        $parts = explode(" ", $keywords);
        foreach ($parts as $var)
        {
            if (in_array($var, $stopwords))
            {
                $error = 1;
                $word  = $var;
                break;
            }
            else if (strlen($var) <= 3)
            {
                $error = 2;
                $word  = $var;
                break;
            }
        }

        if (empty($question))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter a question!<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a question</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else if (empty($answer))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter an answer.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter an answer</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else if (empty($keywords))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter any keywords.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter saome keywords</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else if ($error == 1)
        {
            // One of the keywords is on the MySQL stop list
            $pagetitle = "User Error";
            $data = msg("Sorry but the keyword &quot;" . $word . "&quot; is on the MySQL stoplist<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and remove it</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else if ($error == 2)
        {
            // One of the keywords is too short
            $pagetitle = "User Error";
            $data = msg("Sorry but the keyword &quot;" . $word . "&quot; is is too short<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and change it</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; User Error");
        }
        else
        {
            $question = preparefordb($question);
	    $answer = preparefordb($answer);
	    $keywords = preparefordb($keywords);
	    query("INSERT INTO " . $tbprefix . "_topics (catID, question, answer, keywords) VALUES ('$cat', '$question', '$answer', '$keywords')");
            $id = mysql_insert_id($link_id);
            query("UPDATE " . $tbprefix . "_categories SET total = total + 1 WHERE catID = '$cat'");

            $pagetitle = "Topic Created";
            $data = msg("The FAQ Topic was successfully added to the database<br><br>You can view the topic by clicking <a href=\"../index.php?op=view&t=" . $id . "\" target=\"_blank\">here</a>", "<a href=\"topics.php?op=add\">Add an FAQ Topic</a> &raquo; Topic Created", "Topic Created");
        }
    }
    else if ($op == "edit" || $op == "del")
    {
        $act = $op == "edit" ? "Edit" : "Delete";
        $nextact = $op == "edit" ? "edit2" : "del2";
        $selected = FALSE;

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
            $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"3\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; " . $act . " an FAQ Topic</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "        </table>\r\n";
            $data .= "        </td>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "</table>\r\n";
            $data .= "</form>\r\n";
        }
    }
    else if ($op == "edit2" || $op == "del2")
    {
        $prevop = $op == "edit2" ? "edit" : "del";
        $act = $op == "edit2" ? "Edit" : "Delete";
        $nextact = $op == "edit2" ? "change" : "confirm";
        $selected = FALSE;

        $id = param("id", "POST");
        $result = query("SELECT topicID, question FROM " . $tbprefix . "_topics WHERE catID = '$id' ORDER BY question");

        if (mysql_num_rows($result) == 0)
        {
            // No topics exist
            $pagetitle = "User Error";
            $data = msg("There are no FAQ topics currently in this category", "<a href=\"topics.php?op=$prevop\">" . $act . " an FAQ Topic</a> &raquo; User Error");
        }
        else
        {
            // Display the list of topic
            $pagetitle = "Select a Topic";

            $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
            $data .= "<input type=\"hidden\" name=\"op\" value=\"" . $nextact . "\">\r\n";
            $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Select a Topic</font></th>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "    <tr>\r\n";
            $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
            $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";

            while ($query_data = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $data .= "            <tr>\r\n";
                
                if ($selected == FALSE)
                {
                    $data .= "                <td width=\"1%\" align=\"left\" valign=\"top\"><input type=\"radio\" name=\"id\" value=\"" . $query_data["topicID"] . "\" checked=\"checked\"></td>\r\n";
                    $selected = TRUE;
                }
                else
                {
                    $data .= "                <td width=\"1%\" align=\"left\" valign=\"top\"><input type=\"radio\" name=\"id\" value=\"" . $query_data["topicID"] . "\"></td>\r\n";
                }

                $data .= "                <td align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">" . $query_data["question"] . "</font></td>\r\n";
                $data .= "            </tr>\r\n";
            }

            $data .= "            <tr align=\"center\">\r\n";
            $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Proceed!\"></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "            <tr>\r\n";
            $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"3\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; " . $act . " an FAQ Topic</font></td>\r\n";
            $data .= "            </tr>\r\n";
            $data .= "        </table>\r\n";
            $data .= "        </td>\r\n";
            $data .= "    </tr>\r\n";
            $data .= "</table>\r\n";
            $data .= "</form>\r\n";
        }
    }
    else if ($op == "change")
    {   
        $id         = param("id", "POST");
        $result     = query("SELECT catID, question, answer, keywords FROM " . $tbprefix . "_topics WHERE topicID = '$id'");
        list($catID, $question, $answer, $keywords) = mysql_fetch_row($result);

        $result     = query("SELECT catID, name FROM " . $tbprefix . "_categories ORDER BY name");

        $options    = "";
        while ($query_data = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            if ($query_data["catID"] == $catID)
            {
                $options .= "<option value=\"" . $query_data["catID"] . "\" selected>" . $query_data["name"] . "</option>";
            }
            else
            {
                $options .= "<option value=\"" . $query_data["catID"] . "\">" . $query_data["name"] . "</option>";
            }
        }

        $pagetitle = "Edit an FAQ Topic";

        $data = "<form action=\"$PHP_SELF\" method=\"post\">\r\n";
        $data .= "<input type=\"hidden\" name=\"op\" value=\"update\">\r\n";
        $data .= "<input type=\"hidden\" name=\"oldid\" value=\"" . $catID . "\">\r\n";
        $data .= "<input type=\"hidden\" name=\"topic\" value=\"" . $id . "\">\r\n";
        $data .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <th bgcolor=\"#5485C9\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Edit an FAQ Topic</font></th>\r\n";
        $data .= "    </tr>\r\n";
        $data .= "    <tr>\r\n";
        $data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
        $data .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Category</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><select name=\"cat\">" . $options . "</select></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Question</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><input type=\"text\" name=\"question\" size=\"60\" value=\"" . $question . "\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <td width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><b>Answer</b><br><small>HTML can be used<br>PHP can be used</small></font></td>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"answer\" cols=\"59\" rows=\"8\">" . $answer . "</textarea></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Keywords</font></th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><textarea name=\"keywords\" cols=\"59\" rows=\"8\">" . $keywords . "</textarea></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <th width=\"10%\" align=\"left\" valign=\"top\" nowrap>&nbsp;</th>\r\n";
        $data .= "                <td width=\"80%\" align=\"left\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">When using keywords remember that MySQL will not return results where the word appears in more than 50% of the rows. Once MySQL 4.0.1 is released Boolean search can be used to overcome this.<br><br>Seperate each word with a space</font></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr align=\"center\">\r\n";
        $data .= "                <td colspan=\"2\"><input type=\"submit\" value=\"Update it!\"></td>\r\n";
        $data .= "            </tr>\r\n";
        $data .= "            <tr>\r\n";
        $data .= "                <td bgcolor=\"#FFFFFF\" colspan=\"2\"><br><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\"><a href=\"index.php\">Control Panel Index</a> &raquo; Edit an FAQ Topic</font></td>\r\n";
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
        $cat        = param("cat", "POST");
        $topic      = param("topic", "POST");
        $oldid      = param("oldid", "POST");
        $question   = trim(param("question", "POST"));
        $answer     = trim(param("answer", "POST"));
        $keywords   = trim(param("keywords", "POST"));

        $question   = str_replace("\"", "&quot;", $question);

        // Define variables
        $error  = 0;
        $word   = NULL;

        // Check to see if the user has entered a "stopword" or short word
        $parts = explode(" ", $keywords);
        foreach ($parts as $var)
        {
            if (in_array($var, $stopwords))
            {
                $error = 1;
                $word  = $var;
                break;
            }
            else if (strlen($var) <= 3)
            {
                $error = 2;
                $word  = $var;
                break;
            }
        }

        if (empty($question))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter a question!<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter a question</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; User Error");
        }
        else if (empty($answer))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter an answer.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter an answer</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; User Error");
        }
        else if (empty($keywords))
        {
            $pagetitle = "User Error";
            $data = msg("You didn't enter any keywords.<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and enter saome keywords</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; User Error");
        }
        else if ($error == 1)
        {
            // One of the keywords is on the MySQL stop list
            $pagetitle = "User Error";
            $data = msg("Sorry but the keyword &quot;" . $word . "&quot; is on the MySQL stoplist<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and remove it</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; User Error");
        }
        else if ($error == 2)
        {
            // One of the keywords is too short
            $pagetitle = "User Error";
            $data = msg("Sorry but the keyword &quot;" . $word . "&quot; is is too short<br><br><a href=\"JavaScript:history.go(-1);\">&laquo; Go back and change it</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; User Error");
        }
        else
        {
            // If the user changes the category we need to remove one from the old category and add one to the new category
            
            $question = preparefordb($question);
	    $answer = preparefordb($answer);
	    $keywords = preparefordb($keywords);
	    query("UPDATE " . $tbprefix . "_topics SET question = '$question', answer = '$answer', keywords = '$keywords', catID = '$cat' WHERE topicID = '$topic'");

            if ($cat != $oldid)
            {
                query("UPDATE " . $tbprefix . "_categories SET total = total - 1 WHERE catID = '$oldid'");
                query("UPDATE " . $tbprefix . "_categories SET total = total + 1 WHERE catID = '$cat'");
            }

            $pagetitle = "Topic Update";
            $data = msg("The FAQ Topic was successfully updated<br><br>You can view the topic by clicking <a href=\"../index.php?op=view&t=" . $topic . "\" target=\"_blank\">here</a>", "<a href=\"topics.php?op=edit\">Edit an FAQ Topic</a> &raquo; Topic Updated", "Topic Updated");
        }
    }
    else if ($op == "confirm")
    {
        // Ask the user to confirm the deletion
        $id = param("id", "POST");
        $result = query("SELECT question FROM " . $tbprefix . "_topics WHERE topicID = '$id'");
        list($name) = mysql_fetch_row($result);

        $pagetitle = "Are you Sure?";
        $data = msg("Are you sure you want to delete the topic &quot;" . $name . "&quot;?<br><form action=\"$PHP_SELF\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"op\" value=\"remove\"><input type=\"submit\" name=\"answer\" value=\"Yes\"> <input type=\"submit\" name=\"answer\" value=\"No\"></form>", "<a href=\"topics.php?op=del\">Delete an FAQ Topic</a> &raquo; Confirm Deletion", "Confirm Deletion");
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

        $result = query("SELECT catID FROM " . $tbprefix . "_topics WHERE topicID = '$id'");
        list($catID) = mysql_fetch_row($result);

        query("DELETE FROM " . $tbprefix . "_topics WHERE topicID = '$id'");
        query("UPDATE " . $tbprefix . "_categories SET total = total - 1 WHERE catID = '$catID'");

        $pagetitle = "FAQ Topic Deleted!";
        $data = msg("The FAQ Topic has been removed from the database", "<a href=\"topics.php?op=del\">Delete an existing topic</a> &raquo; Topic Deleted!", "Topic Deleted!");
    }
    else
    {
        header("Location: ./index.php");
        exit();
    }
}

mysql_close();

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}
?>