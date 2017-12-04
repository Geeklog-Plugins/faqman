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

// This script is for internal use in the Control Panel only
// so you should NOT edit it

// The first 3 arrays contain "display data", i.e. instructions for the user

// This array is a list of the template names
$templates = array(
        "1"     => "Global",
        "2"     => "Index",
        "3"     => "Category",
        "4"     => "FAQ Topic",
        "5"     => "Search",
        "6"     => "Search Results",
        "7"     => "No Matches",
        "8"     => "Printer Friendly",
        "9"     => "MySQL Error",
        "10"    => "General Error",
        "11"    => "Query Log"
        );
// This array is a list of the "place markers" which can be used in each template
$vars = array(
        "1"  => array(
             "TITLE"         => "Indicates where the page title should appear (Required)",
             "CONTENT"       => "Indicates the location of the main output (Required)",
             "VERSION"       => "Indicates the version of FAQ Manager that is being used",
             "QUERIES"       => "Indicates the position of the SQL query log",
             "STATS"         => "Indicates where to display the script statistics"
             ),
        "2"  => array(
             "CAPTION"       => "Used to position the section title on the page (Required)",
             "CATEGORY"      => "Indicates the location of a category name (Required)",
             "ID"            => "Marks the location of the category ID number (Required)",
             "TOTAL"         => "Used to display the total number of topics within a category",
             "DESCRIPTION"   => "Indicates where the category description should be placed"
             ),
        "3"  => array(
             "CAPTION"       => "Displays the Category name (Required)",
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "THIS"          => "Marks the current position within the system, used for navigation (Required)",
             "QUESTION"      => "Indicates where the Question should be displayed (Required)",
             "ID"            => "Represents the topic ID number (Required)",
             "CAT_JUMP"      => "Positions the &quot;Category Jump&quot; list",
             "DESCRIPTION"   => "Indicates where the category description should be placed"
             ),
        "4"  => array(
             "QUESTION"      => "Displays the FAQ Question (Required)",
             "ANSWER"        => "Indicates where to display the answer (Required)",
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "CAT_ID"        => "The ID number of the current category, used for navigation (Required)",
             "CAT_NAME"      => "The name of the current category, used for navigation (Required)",
             "THIS"          => "Marks the current position within the system, used for navigation (Required)",
             "PRINTER"       => "Used to show the location to the printer friendly page",
             "CAT_JUMP"      => "Positions the &quot;Category Jump&quot; list"
             ),
        "5"  => array(
             "ACTION"        => "The action to take to search (Required)",
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "THIS"          => "Marks the current position within the system, used for navigation (Required)",
             "CAT_JUMP"      => "Positions the &quot;Category Jump&quot; list"
             ),
        "6"  => array(
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "QUESTION"      => "Indicates where the Question should be displayed (Required)",
             "ID"            => "Represents the topic ID number (Required)",
             "CAT_JUMP"      => "Positions the &quot;Category Jump&quot; list"
             ),
        "7"  => array(
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "CAT_JUMP"      => "Positions the &quot;Category Jump&quot; list"
             ),
        "8"  => array(
             "TITLE"         => "Represents the name of FAQ Manager (Required)",
             "QUESTION"      => "Indicates where the Question should be displayed (Required)",
             "ANSWER"        => "Indicates where the Answer should be placed (Required)"
             ),
        "9"  => array(
             "ERR_MSG"       => "Represents the error message generated by MySQL (Required)",
             "ERR_NUM"       => "Represents the error number generated by MySQL"
             ),
        "10" => array(
             "CAPTION"       => "Displays the Error Type (Required)",
             "MSG"           => "Displays the Error Message (Required)",
             "INDEX"         => "Indicates the name of FAQ Manager, used for navigation (Required)",
             "THIS"          => "Marks the current position within the system, used for navigation (Required)"
             ),
        "11" => array(
             "SQL"           => "Displays the SQL query (Required)"
             )
        );

// The final array contains any special information on each template, such as a "tip" or "hint"
$info = array(
        "1"  => "Please do not remove the copyright information, or make it difficult to read. Doing so is illegal.<br>The reason we allow you to edit this information so easily is so that you can modify the font to suit your site, please do not abuse this power<br><br><b>Warning</b>: You should not edit or remove the Javascript from this template",
        "2"  => "<b>Hint</b>: Remember to place &lt-- BEGIN DYNAMIC BLOCK --&gt; before the section of code which is to be repeated for each category and  &lt-- END DYNAMIC BLOCK --&gt; afterwards",
        "3"  => "<b>Hint</b>: Remember to place &lt-- BEGIN DYNAMIC BLOCK --&gt; before the section of code which is to be repeated for each question and  &lt-- END DYNAMIC BLOCK --&gt; afterwards",
        "4"  => "<b>Note</b>: You can also edit these files in a plain ASCII editor such as notepad (do not use a WYSIWYG editor such as Frontpage or Dreamweaver). To find out which file to edit look up the tmp number from the \$files array in ./data/templates.inc.php",
        "5"  => "Be very careful when editing this file, if you muck up the form fields you will need to restore the file ./templates/search.tpl from the original archive",
        "6"  => "<b>Hint</b>: Remember to place &lt-- BEGIN DYNAMIC BLOCK --&gt; before the section of code which is to be repeated for each question and  &lt-- END DYNAMIC BLOCK --&gt; afterwards",
        "7"  => "<b>Note</b>: You can also use PHP code within the templates and it will be executed. You shouldn't use too much or complex PHP though as it will slow down the execution time",
        "8"  => "Remember the aim of this page is to allow the user to print the topic, so it should just contain black text, no images or colour",
        "9"  => "<b>Note</b>: The error messages are generated by the MySQL server, there is no way to edit them without recompiling MySQL",
        "10" => "<b>Note</b>: You can also use PHP code within the templates and it will be executed. You shouldn't use too much or complex PHP though as it will slow down the execution time",
        "11" => "<b>Hint</b>: Remember to place &lt-- BEGIN DYNAMIC BLOCK --&gt; before the section of code which is to be repeated for each query and  &lt-- END DYNAMIC BLOCK --&gt; afterwards"
        );

// The next 2 arrays contain "parser" data i.e. data used to update the template

// An array of the "place markers" required for each template
$required = array(
        "1"  => array("TITLE", "CONTENT"),
        "2"  => array("CAPTION", "CATEGORY", "ID", "<!-- BEGIN DYNAMIC BLOCK -->", "<!-- END DYNAMIC BLOCK -->"),
        "3"  => array("CAPTION", "INDEX", "THIS", "QUESTION", "ID", "<!-- BEGIN DYNAMIC BLOCK -->", "<!-- END DYNAMIC BLOCK -->"),
        "4"  => array("QUESTION", "ANSWER", "INDEX", "CAT_ID", "CAT_NAME", "THIS"),
        "5"  => array("ACTION", "INDEX", "THIS"),
        "6"  => array("INDEX", "QUESTION", "ID", "<!-- BEGIN DYNAMIC BLOCK -->", "<!-- END DYNAMIC BLOCK -->"),
        "7"  => array("INDEX"),
        "8"  => array("TITLE", "QUESTION", "ANSWER"),
        "9"  => array("ERR_MSG"),
        "10" => array("CAPTION", "MSG", "INDEX", "THIS"),
        "11" => array("SQL")
        );

// This array is the name of the file to use for each template
$files = array(
        "1"  => "../templates/global.tpl",
        "2"  => "../templates/index.tpl",
        "3"  => "../templates/category.tpl",
        "4"  => "../templates/answer.tpl",
        "5"  => "../templates/search.tpl",
        "6"  => "../templates/results.tpl",
        "7"  => "../templates/noresult.tpl",
        "8"  => "../templates/printer.tpl",
        "9"  => "../templates/mysql.tpl",
        "10" => "../templates/msg.tpl",
        "11" => "../templates/queries.tpl"
        );
?>