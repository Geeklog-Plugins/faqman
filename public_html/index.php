<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/sql/mysql_install.php                              |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000,2001,2002,2003 by the following authors:               |
// | Geeklog Author: Tony Bibbs       - tony@tonybibbs.com                     |
// +---------------------------------------------------------------------------+
// | FAQ Plugin Author                                                         |
// | Authors: FAQ Appllication:   Stephen Ball, stephen@aquonics.com           |
// | Conversion to Geeklog Plugin: Blaine Lang, blaine@portalparts.com         |
// +---------------------------------------------------------------------------+
// | Based on the Universal Plugin and prior work by the following authors:    |
// | Upgraded for GL version 1.5 online config manager                         |
// |                                                                           |
// | Copyright (C) 2002-2017 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs        - tony AT tonybibbs DOT com                    |
// |          Tom Willett       - tom AT pigstye DOT net                       |
// |          Blaine Lang       - blaine AT portalparts DOT com                |
// |          Dirk Haun         - dirk AT haun-online DOT de                   |
// |          Vincent Furia     - vinny01 AT users DOT sourceforge DOT net     |
// |          Kenji ITO         - mystralkk AT gmail DOT com                   |
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

require_once '../lib-common.php';

/**
 * Return error message
 *
 * @return string
 */
function display_error($msg) {
    global $LANG_FAQ;

    $content = COM_startBlock($LANG_FAQ['error'], '', COM_getBlockTemplate('_msg_block', 'header'))
        . '<p>' . $msg . '</p>'
        . COM_endBlock(COM_getBlockTemplate('_msg_block', 'footer'));
    $retval = COM_createHtmlDocument(
        $content,
        array(
            'pagetitle' => $LANG_FAQ['error_page'],
            'what'      => 'menu',
        )
    );

    return $retval;
}

/**
 * Return FAQ Navbar
 *
 * @param  string $category
 * @param  int    $c
 * @param  string $topic
 * @return string
 */
function displayNavbar($category = '', $c = 0, $topic = '') {
    global $_CONF, $LANG_FAQ;

    $retval = '<p><a href="' . $_CONF['site_url'] . '/faqman/">' . $LANG_FAQ['headerlabel'] . '</a>';

    if (!empty($category)) {
        $retval .= ' &#187; ';

        if (empty($topic)) {
            $retval .= FAQMAN_esc($category);
        } else {
            $retval .= '<a href="' . $_CONF['site_url'] . '/faqman/index.php?op=cat&amp;c='
                . $c . '" rel="category tag">' . FAQMAN_esc($category) . '</a>';
        }
    }

    if (!empty($topic)) {
        $retval .= ' &#187; ' . FAQMAN_esc($topic);
    }

    $retval .= '</p>' . LB;

    return $retval;
}

/**
 * Return FAQ main display
 *
 * @return string
 */
function displayMain() {
    global $_CONF, $_TABLES, $LANG_FAQ;

    $T = COM_newTemplate($_CONF['path'] . 'plugins/faqman/templates');
    $T->set_file(array(
        'main' => 'main.thtml',
        'item' => 'main-item.thtml',
    ));

    $result = DB_query("SELECT * FROM {$_TABLES['faq_categories']} ORDER BY name");
    $total = DB_numRows($result);
    $T->set_var(array(
        'has_cats'      => ($total > 0),
        'lang_CATEGORY' => $LANG_FAQ['CATEGORY'],
        'lang_QUESTION' => $LANG_FAQ['QUESTION'],
        'lang_ANSWER'   => $LANG_FAQ['ANSWER'],
    ));

    if ($total > 0) {
        while (($A = DB_fetchArray($result, false)) !== false) {
            $T->set_var(array(
                'catID'           => $A['catID'],
                'name'            => FAQMAN_esc($A['name']),
                'description'     => FAQMAN_esc($A['description']),
                'total'           => $A['total'],
                'lang_num_topics' => $LANG_FAQ['admin_cat_num_topics'],
            ));
            $T->parse('cats', 'item', true);
        }
    }

    $T->set_var('lang_no_cats', $LANG_FAQ['no_cats']);
    $T->parse('output', 'main', true);
    $content = COM_startBlock($LANG_FAQ['headerlabel'])
        . $T->finish($T->get_var('output'))
        . COM_endBlock ();
    $retval = COM_createHTMLDocument(
        $content,
        array(
            'pagetitle' => $LANG_FAQ['headerlabel'],
            'what'      => 'menu',
        )
    );

    return $retval;
}

/**
 * Return FAQ category display
 *
 * @param  int $catID
 * @return string
 */
function displayCategory($catID) {
    global $_CONF, $_TABLES, $LANG_FAQ;

    $retval = '';

    $catID = (int) $catID;
    $result = DB_query("SELECT name, description FROM {$_TABLES['faq_categories']} WHERE catID = {$catID}");
    $A = DB_fetchArray($result, false);

    if (!isset($A, $A['name'], $A['description'])) {
        return display_error($LANG_FAQ['error_5']);
    } else {
        $name = $A['name'];
        $description = $A['description'];
    }

    $result = DB_query("SELECT * FROM {$_TABLES['faq_topics']} WHERE catID = {$catID} ORDER BY question");
    $total = DB_numRows($result);

    if ($total == 0) {
        return display_error($LANG_FAQ['error_6']);
    }

    $T = COM_newTemplate($_CONF['path'] . 'plugins/faqman/templates');
    $T->set_file(array(
        'cat'  => 'category.thtml',
        'item' => 'category-item.thtml',
    ));
    $T->set_var(array(
        'description'   => FAQMAN_esc($description),
        'lang_CATEGORY' => $LANG_FAQ['CATEGORY'],
        'lang_QUESTION' => $LANG_FAQ['QUESTION'],
        'lang_ANSWER'   => $LANG_FAQ['ANSWER'],
    ));
    $content = COM_startBlock($LANG_FAQ['CATEGORY'] . ': ' . FAQMAN_esc($name));

    while (($A = DB_fetchArray($result, false)) !== false) {
        $T->set_var(array(
            'topicID'  => $A['topicID'],
            'question' => FAQMAN_esc($A['question']),
        ));
        $T->parse('cats', 'item', true);
    }

    $T->parse('output', 'cat');
    $content .= $T->finish($T->get_var('output'))
        . displayNavbar($name, $catID)
        . COM_endBlock();
    $retval = COM_createHTMLDocument(
        $content,
        array(
            'pagetitle' => $name,
            'what'      => 'menu',
        )
    );

    return $retval;
}

/**
 * Return FAQ topic display
 *
 * @param  int $topicID
 * @return string
 */
function displayTopic($topicID) {
    global $_CONF, $_TABLES, $LANG_FAQ;

    $retval = '';

    $topicID = (int) $topicID;
    $result = DB_query(
        "SELECT catID, question, answer FROM {$_TABLES['faq_topics']} "
        . "WHERE topicID = {$topicID}"
    );
    $A = DB_fetchArray($result, false);

    if (empty($A)) {
        return display_error($LANG_FAQ['error_7']);
    }

    $catID = $A['catID'];
    $question = $A['question'];
    $answer = $A['answer'];

    $content = COM_startBlock($LANG_FAQ['QUESTION'] . ': ' . $question);

    if (function_exists('PLG_replaceTags')) {
        $answer = PLG_replaceTags($answer);
    }

    $answer = str_replace(array("\r\n", "\n", "\r"), '<br>', $answer);

    $T = COM_newTemplate($_CONF['path'] . 'plugins/faqman/templates');
    $T->set_file(array(
        'topic' => 'topic.thtml',
    ));
    $name = DB_getItem($_TABLES['faq_categories'], 'name', "catID = {$catID}");
    $T->set_var(array(
        'answer' => $answer,
        'navbar' => displayNavbar($name, $catID, $question),
        'lang_CATEGORY' => $LANG_FAQ['CATEGORY'],
        'lang_QUESTION' => $LANG_FAQ['QUESTION'],
        'lang_ANSWER'   => $LANG_FAQ['ANSWER'],
    ));
    $T->parse('output', 'topic');
    $content .= $T->finish($T->get_var('output'))
        . COM_endBlock ();
    $retval = COM_createHTMLDocument(
        $content,
        array(
            'pagetitle' => FAQMAN_esc($question),
            'what'      => 'menu',
        )
    );

    return $retval;
}

// MAIN
$_SCRIPTS->setCSSFile('faqman', '/faqman/faqman.css', true);

$op = Geeklog\Input::fGet('op', '');
if (($op !== 'cat') && ($op !== 'view')) {
    $op = '';
}

if ($op === 'cat') {
    $c  = (int) Geeklog\Input::fGet('c', 0);

    if ($c > 0) {
        $display = displayCategory($c);
    } else {
        $display = displayMain();
    }
} elseif ($op === 'view') {
    $t  = (int) Geeklog\Input::fGet('t', 0);
    if ($t > 0) {
        $display = displayTopic($t);
    } else {
        $display = displayMain();
    }
} else {
    $display = displayMain();
}

COM_output($display);
