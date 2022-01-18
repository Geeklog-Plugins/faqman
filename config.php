<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/config.php                                         |
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

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
    die('This file cannot be used on its own!');
}

global $_DB_table_prefix, $_TABLES, $_FAQM_CONF;

// Add to $_TABLES array the tables your plugin uses
$_TABLES['faq_categories'] = $_DB_table_prefix . 'faq_categories';
$_TABLES['faq_topics']     = $_DB_table_prefix . 'faq_topics';

// Plugin info
$_FAQM_CONF = array(
    'pi_version' => '0.9.2',                                        // Plugin Version
    'gl_version' => '2.1.3',                                        // GL Version plugin for
    'pi_url'     => 'https://github.com/Geeklog-Plugins/faqman',    // Plugin Homepage
    'GROUPS'     => array(
        'faqman Admin' => 'Users in this group can administer the FAQ Manager plugin',
    ),
    'FEATURES'   => array(
        'faqman.edit' => 'Access to FAQ Manager Admin',
    ),
    'MAPPINGS'   => array(
        'faqman.edit' => array('faqman Admin'),
    ),
    'TABLES'     => array(
        $_TABLES['faq_categories'],
        $_TABLES['faq_topics'],
    ),
);
