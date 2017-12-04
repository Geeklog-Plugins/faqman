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


// Check to see if the user wants to view the SQL queries used
if ($sql == 2)
{
	$sql = param("sql", "GET");
}

if ($sql == 1 && $sql_log[0] != "")
{
 //   $data .= build_query($sql_log);
}

$endtime = endtime($starttime);
// Populate the admin template
$page = array("VERSION" => $version, "TITLE" => $pagetitle, "CONTENT" => trim($data), "STATS" => "This page took $endtime seconds to produce<br>Total queries to generate page: $QUERY_COUNT<br>GZIP Compression Status: " . $gzip,);

// A fix to stop the template source being parsed

echo COM_siteHeader();
if (basename($PHP_SELF) == "templates.php")
{
    $page["FDATA"] = $fdata;
}
echo template("./admin.tpl", $page); // Finallise the template
echo COM_siteFooter();

?>