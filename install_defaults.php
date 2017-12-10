<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/install_defaults.php                               |
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

/**
* FaqMan default settings
*
* Initial Installation Defaults used when loading the online configuration
* records.  These settings are only used during the initial installation
* and not referenced any more once the plugin is installed
*/
global $_FAQM_CONF_DEFAULT;

$_FAQM_CONF_DEFAULT = array();

/**
* Initializes FaqMan plugin configuration
*
* Creates the database entries for the configuation if they don't already
* exist.  Initial values will be taken from $_FAQM_CONF_DEFAULT
* if available (e.g. from an old config.php), uses $_FAQM_CONF_DEFAULT
* otherwise.
*
* @return bool true: success; false: an error occurred
*/
function plugin_initconfig_faqman() {
    global $_FAQM_CONF, $_FAQM_CONF_DEFAULT;

    if (is_array($_FAQM_CONF) && (count($_FAQM_CONF) > 0)) {
        $_FAQM_CONF_DEFAULT = array_merge($_FAQM_CONF_DEFAULT, $_FAQM_CONF);
    }

    $me = 'faqman';
    $c = config::get_instance();

    if (!$c->group_exists($me)) {
//        $c->add('sg_main', null, 'subgroup', 0, 0, null, 0, true, $me);
//        $c->add('fs_main', null, 'fieldset', 0, 0, null, 0, true, $me);
    }

    return true;
}
