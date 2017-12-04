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

if (!include("../data/settings.inc.php"))
{
    die(include_error("settings"));
}
else if (!include("../data/common.lib.php"))
{
    die(include_error("library"));
}

// Geeklog Modification
require_once("../../lib-common.php");
// Use Geeklog's Settings for the Database
$dbhost     = $_DB_host;
$dbuser     = $_DB_user;
$dbpass     = $_DB_pass;
$dbname     = $_DB_name;
$tbprefix   = $_DB_table_prefix . "faq";

$PHP_SELF = param("PHP_SELF", "SERVER");

if (basename($PHP_SELF) == "topics.php" && !include("../data/stopwords.inc.php"))
{
    die(include_error("stopwords"));
}
else if (basename($PHP_SELF) == "templates.php" && !include("../data/templates.inc.php"))
{
    die(include_error("templates"));
}

if ($debug != 0)
{
    error_reporting($debug);
}

$starttime = starttime(); // Find out the microtime for the current time

if (!SEC_hasRights('faqman.edit')) {
      echo COM_siteHeader();
	  echo COM_startBlock($LANG_GF00['access_denied']);
	  echo $LANG_GF00['admin_only'];
	  echo COM_endBlock();
	  echo adminfooter();
	  echo COM_siteFooter(true);
	  exit();
}

?>