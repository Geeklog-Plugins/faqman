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

error_reporting(0);

// A nice function to help us die gracefully if there is an error
function include_error($type)
{
    echo "<html>\r\n";
    echo "<body bgcolor=\"#FFFFFF\">\r\n";
    echo "<center><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\">There was an error loading the $type file.</font></center>\r\n";
    echo "</body>\r\n";
    echo "</html>";
}

if (!include("./header.lib.php"))
{
    die(include_error("header"));
}

// See if the user is logging in and check their password
$password = param("password", "POST");
if (!empty($password))
{
    if (md5($password) == $adminpass)
    {
        setcookie("faq_admin", md5($password));
        #header("Location: ./index.php");
        exit("<html><head><meta http-equiv=\"refresh\" content=\"0; url=index.php\"></head><body></body></html>");
    }
    else
    {
        setcookie("faq_admin");
    }
}
else
{
    setcookie("faq_admin");
}

$pagetitle = "Please Log In";

$data = "<table border=\"0\" width=\"75%\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#000000\">\r\n";
$data .= "    <tr>\r\n";
$data .= "        <th align=\"left\" bgcolor=\"#5485C9\"><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"2\" color=\"#FFFFFF\">&raquo; Please log in</font></th>\r\n";
$data .= "    </tr>\r\n";
$data .= "    <tr>\r\n";
$data .= "        <td bgcolor=\"#FFFFFF\">\r\n";
$data .= "        <table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">\r\n";
$data .= "          <tr>\r\n";
$data .= "              <td bgcolor=\"#FFFFFF\">\r\n";
$data .= "              <form method=\"post\" action=\"$PHP_SELF\">\r\n";
$data .= "              <table border=\"0\" width=\"100%\">\r\n";
$data .= "                  <tr>\r\n";
$data .= "                      <th align=\"left\" width=\"10%\" nowrap><font face=\"Verdana, Geneva, Arial, Helvetica, Sans-Serif\" size=\"1\" color=\"#000000\">Password</font></th>\r\n";
$data .= "                      <td align=\"left\" width=\"90%\"><input type=\"password\" name=\"password\" size=\"60\"></td>\r\n";
$data .= "                  </tr>\r\n";
$data .= "                  <tr>\r\n";
$data .= "                       <td align=\"center\" valign=\"middle\" colspan=\"2\"><input type=\"submit\" value=\"Log in!\"></td>\r\n";
$data .= "                  </tr>\r\n";
$data .= "                </table>\r\n";
$data .= "                </form>\r\n";
$data .= "                </td>\r\n";
$data .= "           </tr>\r\n";
$data .= "         </table>\r\n";
$data .= "         </td>\r\n";
$data .= "    </tr>\r\n";
$data .= "</table>\r\n";

if (!include("./footer.lib.php"))
{
    die(include_error("footer"));
}

?>