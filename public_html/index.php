<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | FAQ Manager 0.8                                                           |
// +---------------------------------------------------------------------------+
// | index.php                                                                 |
// |                                                                           |
// | Replacement for the original FAQ Manager index file                       |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006 by the following authors:                              |
// |                                                                           |
// | Authors: Dirk Haun  - dirk AT haun-online DOT de                          |
// |                                                                           |
// | Based on earlier work by Stephen Ball (original standalone FAQ Manager)   |
// | and Blaine Lang (original FAQ Manager plugin for Geeklog).                |
// |                                                                           |
// | Special thanks to Soopaman for pushing some pixels around.                |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//
// $Id$

require_once ('../lib-common.php');
$tbprefix   = $_DB_table_prefix . 'faq';

function display_error ($msg)
{
    $retval = COM_siteHeader ('menu', 'FAQ Manager - Error')
            . COM_startBlock ('Error', '',
                              COM_getBlockTemplate ('_msg_block', 'header'))
            . '<p>' . $msg . '</p>'
            . COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'))
            . COM_siteFooter ();

    return $retval;
}

function display_navbar ($category = '', $c = 0, $topic = '')
{
    global $_CONF;

    $retval = '';

    $retval .= '<p><a href="' . $_CONF['site_url'] . '/faqman/">FAQ Manager</a>';

    if (!empty ($category)) {
        $retval .= ' &#187; ';

        if (empty ($topic)) {
            $retval .= $category;
        } else {
            $retval .= '<a href="' . $_CONF['site_url'] . '/faqman/index.php?op=cat&amp;c=' . $c . '" rel="category tag">' . $category . '</a>';
        }
    }

    if (!empty ($topic)) {
        $retval .= ' &#187; ';

        $retval .= $topic;
    }

    $retval .= '</p>' . LB;

    return $retval;
}

function display_main ()
{
    global $_CONF, $_TABLES, $tbprefix;

    $retval = '';

    $result = DB_query ("SELECT * FROM " . $tbprefix . "_categories ORDER BY name");
    $total = DB_numRows ($result);
    if ($total == 0) {
        return display_error ('You have yet to add any categories to FAQ Manager.');
    } else {
        $book_icon = $_CONF['site_url'] . '/faqman/images/faq.gif';
        $ul_style = ' style="margin: 0; margin-top: 10px; padding: 0px; list-style: none;"';
        $li_style = ' style="padding-top: 0px; padding-left: 20px; margin-bottom: 3px; margin-top: 3px; background: transparent url(' . $book_icon . ') top left no-repeat;"';

        $retval .= COM_siteHeader ('menu', 'FAQ Manager');
        $retval .= COM_startBlock ('FAQ Manager');

        $retval .= '<ul' . $ul_style . '>' . LB;
        for ($i = 0; $i < $total; $i++) {
            $A = DB_fetchArray ($result);

            $retval .= '<li' . $li_style . '><a href="' . $_CONF['site_url'] . '/faqman/index.php?op=cat&amp;c=' . $A['catID'] . '">' . $A['name'] . '</a><br><b>Number of topics:</b> ' . $A['total'] . '<br>' . $A['description'] . '</li>' . LB;
        }
        $retval .= '</ul>' . LB;
    }

    $retval .= COM_endBlock ();
    $retval .= COM_siteFooter ();

    return $retval;
}

function display_category ($c)
{
    global $_CONF, $_TABLES, $tbprefix;

    $retval = '';

    $result = DB_query ("SELECT name, description FROM " . $tbprefix . "_categories WHERE catID = '$c'");
    list($name, $description) = DB_fetchArray ($result);

    if (empty ($name) || empty ($description)) {
        return display_error ('You did not select a valid category.');
    } else {
        $result = DB_query ("SELECT * FROM " . $tbprefix . "_topics WHERE catID = '$c' ORDER BY question");
        $total = DB_numRows ($result);
        if ($total == 0) {
            return display_error ('There are not any FAQ Topics in this category.');
        } else {
            $book_icon = $_CONF['site_url'] . '/faqman/images/faq.gif';
            $ul_style = '';
            $li_style = ' style="list-style-image: url(' . $book_icon . ');"';

            $retval .= COM_siteHeader ('menu', $name);
            $retval .= COM_startBlock ($name);
            $retval .= '<p>' . $description . '</p>';

            $retval .= '<ul' . $ul_style . '>' . LB;
            for ($i = 0; $i < $total; $i++) {
                $A = DB_fetchArray ($result);

                $retval .= '<li' . $li_style . '><a href="' . $_CONF['site_url'] . '/faqman/index.php?op=view&amp;t=' . $A['topicID'] . '">' . $A['question'] . '</a></li>' . LB;
            }
            $retval .= '</ul>' . LB;

            $retval .= display_navbar ($name, $c);
        }
    }

    $retval .= COM_endBlock ();
    $retval .= COM_siteFooter ();

    return $retval;
}

function display_topic ($t)
{
    global $_CONF, $_TABLES, $tbprefix;

    $retval = '';

    $result = DB_query ("SELECT topicID,catID,question,answer FROM " . $tbprefix . "_topics WHERE topicID = '$t'");
    list ($topicID, $catID, $question, $answer) = DB_fetchArray ($result);

    if (empty ($topicID) || empty ($catID) || empty ($question) || empty ($answer)) {
        return display_error ('Topic not found.');
    } else {
        $retval .= COM_siteHeader ('menu', $question);
        $retval .= COM_startBlock ($question);

        if (function_exists ('PLG_replaceTags')) {
            $answer = PLG_replaceTags ($answer);
        }

        $answer = str_replace ("\r\n", '<br>', $answer);
        $answer = str_replace ("\n", '<br>', $answer);
        $answer = str_replace ("\r", '<br>', $answer);

        $retval .= '<p>' . $answer . '</p>' . LB;

        $name = DB_getItem ($tbprefix . '_categories', 'name',
                            "catID = '$catID'");
        $retval .= display_navbar ($name, $catID, $question);
    }

    $retval .= COM_endBlock ();
    $retval .= COM_siteFooter ();

    return $retval;
}

// MAIN
$display = '';

$op = COM_applyFilter ($_GET['op']);
if (($op != 'cat') && ($op != 'view')) {
    $op = '';
}

if ($op == 'cat') {
    $c  = COM_applyFilter ($_GET['c'], true);
    if ($c > 0) {
        $display .= display_category ($c);
    } else {
        $display .= display_main ();
    }
} else if ($op == 'view') {
    $t  = COM_applyFilter ($_GET['t'], true);
    if ($t > 0) {
        $display .= display_topic ($t);
    } else {
        $display .= display_main ();
    }
} else {
    $display .= display_main ();
}

echo $display;

?>
