<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/language/english.php                               |
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
// +---------------------------------------------------------------------------|
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
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// |                                                                           |
// +---------------------------------------------------------------------------|

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
    die('This file cannot be used on its own!');
}

$LANG_FAQ = array(
    'plugin'               => 'FAQ',
	'plugin_name'          => 'FAQ Manager',
    'CATEGORY'             => 'Category',
    'QUESTION'             => 'Question',
    'ANSWER'               => 'Answer',
    'headerlabel'          => 'FAQ',
    'searchlabel'          => 'FAQ',
    'searchresults'        => 'FAQ Manager Search Results',
    'statslabel'           => 'Total FAQ Manager Postings',
    'no_cats'              => 'There are not any FAQ Categories.',
    'no_topics'            => 'There are not any FAQ Topics in this category.',
    'admin'                => 'Plugin Admin',
    'admin_menu'           => 'FAQ Manager',
    'admin_menu_desc'      => 'Manages FAQ categories and topics.  <span style="color: red;">If you delete a category, ALL topics belonging to it will be deleted.</span>',
    'admin_create_cat'     => 'Create category',
    'admin_create_topic'   => 'Create topic',
    'admin_list_cats'      => 'List of Categories',
    'admin_cat_editor'     => 'FAQ Category Editor',
    'admin_cat_id'         => 'ID',
    'admin_cat_name'       => 'Name',
    'admin_cat_desc'       => 'Description',
    'admin_cat_num_topics' => 'Number of Topics',
    'admin_list_topics'    => 'List of Topics',
    'admin_topic_editor'   => 'FAQ Topic Editor',
    'admin_topic_id'       => 'ID',
    'admin_topic_name'     => 'Name',
    'admin_topic_question' => 'Question',
    'admin_topic_answer'   => 'Answer',
    'error'                => 'Error',
    'error_page'           => 'Error|FAQ',
    'error_1'              => 'The ID of the category you were trying to edit was not found.',
    'error_2'              => 'Could not save the category you were editing.',
    'error_3'              => 'The ID of the topic you were trying to edit was not found.',
    'error_4'              => 'Could not save the topic you were editing.',
    'error_5'              => 'You did not select a valid category.',
    'error_6'              => 'There are not any FAQ Topics in this category.',
    'error_7'              => 'Topic not found.',
    'message_1'            => 'Successfully saved the category you were editing.',
    'message_2'            => 'Successfully deleted the category.',
    'message_3'            => 'Successfully saved the topic you were editing.',
    'message_4'            => 'Successfully deleted the topic.',
);

// Localization of the Admin Configuration UI
$LANG_configsections['faqman'] = array(
    'label' => 'FaqMan',
    'title' => 'FaqMan Configuration'
);

$LANG_confignames['faqman'] = array(
    'default_permissions' => 'Page Default Permissions',
);

$LANG_configsubgroups['faqman'] = array(
    'sg_main' => 'Main Settings'
);

$LANG_fs['faqman'] = array(
    'fs_main'        => 'FaqMan Main Settings',
    'fs_permissions' => 'Default Permissions',
);

// Note: entries 0, 1, 9, and 12 are the same as in $LANG_configselects['Core']
$LANG_configselects['faqman'] = array(
    0 => array('True' => 1, 'false' => 0),
    1 => array('True' => true, 'false' => FALSE),
    9 => array('Forward to page' => 'item', 'Display List' => 'list', 'Display Home' => 'home', 'Display Admin' => 'admin'),
    12 => array('No access' => 0, 'Read-Only' => 2, 'Read-Write' => 3),
);
