<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/functions.inc                                      |
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
// | Copyright (C) 2002-2022 by the following authors:                         |
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
    die('This file cannot be used on its own.');
}

// Language file Include
$langfile = __DIR__ . '/language/' . $_CONF['language'] . '.php';
clearstatcache();

if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once __DIR__ . '/language/english.php';
}

// Loads the plugin configuration
global $_FAQM_CONF;

require_once __DIR__ . '/config.php';
FAQMAN_loadConfig();

if (version_compare($_FAQM_CONF['pi_version'], '0.9.3', '<')) {
    plugin_upgrade_faqman();
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

/**
* Returns a URL to icon
*/
function plugin_geticon_faqman() {
    global $_CONF;

    return $_CONF['site_url'] . '/faqman/images/faqman.gif';
}

/**
* Returns code to be added to <head> section
*/
function plugin_getheadercode_faqman() {
    global $_CONF;

}

/**
* Returns code to be added to footer
*
* @since  GL-1.8.0
*/
function plugin_getfootercode_faqman() {
    global $_CONF;

}

/**
* Callback function when an item was just saved
*
* @param   string  $id     unique ID of the item
* @param   string  $type   type of the item, e.g. 'article'
* @param   string  $old_id (optional) old ID when the ID was changed
*/
function plugin_itemsaved_faqman($id, $type, $old_id) {
    global $_CONF;

}

/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*/
function plugin_getmenuitems_faqman() {
    global $_CONF, $LANG_FAQ;

    return array(
        $LANG_FAQ['headerlabel'] => $_CONF['site_url'] . '/faqman/index.php',
    );
}

/**
* Checks to see if this plugin uses comments
*
* @return   boolean true or false
*/
function plugin_commentsupport_faqman() {
    return false;
}

/**
* Puts an option for the plugin in the command and control block on moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*/
function plugin_cclabel_faqman() {
    global $LANG_FAQ, $_CONF;

    if (SEC_hasRights('faqman.edit')) {
        return array(
            $LANG_FAQ['plugin'],
            $_CONF['site_admin_url'] . '/plugins/faqman/index.php',
            plugin_geticon_faqman(),
        );
    } else {
        return array();
    }
}

/**
* Returns the administrative option for this plugin
*
* @return   array   Array containing (plugin name, plugin admin url, # of items
* in plugin or '')
*/
function plugin_getadminoption_faqman() {
    global $_CONF, $_TABLES, $LANG_FAQ;

    if (SEC_hasRights('faqman.edit')) {
        $result = DB_query("SELECT COUNT(*) FROM {$_TABLES['faq_topics']}");
        list($faqtopics) = DB_fetchArray($result);

        return array($LANG_FAQ['admin_menu'], $_CONF['site_admin_url'] . '/plugins/faqman/index.php', $faqtopics);
    } else {
        return array();
    }
}

/**
* Returns the version for this plugin
*
* @return string VersionNo
*/
function plugin_chkVersion_faqman() {
    global $_FAQM_CONF;

    return $_FAQM_CONF['pi_version'];
}

/**
 * Geeklog is asking us to provide any new items that show up in the type drop-down
 * on search.php.  Let's let users search the Filelistings in the Filemgmt Plugin
 */
function plugin_searchtypes_faqman() {
    global $LANG_FAQ;
    
    $tmp['faqman'] = $LANG_FAQ['searchlabel'];

    return $tmp;
}

function plugin_searchformat_faqman($id, $contentType, $content)
{
    global $_CONF;

    if ($contentType === 'url') {
        $content = $_CONF['site_url'] . '/faqman/index.php?op=view&amp;t=' . (int) $content;
    }

    return $content;
}

/**
* this searches for files matching the user query and returns an array of 
* for the header and table rows back to search.php where it will be formated and
* printed 
*
* @param  string $query     Keywords user is looking for
* @param  int    $datestart Start date : Required by common function format but not used
* @param  int    $dateend   End date : Required by common function format but not used
* @param  string $topic     Required by common function format but not used
* @param  string $type      Type of items they are searching - will be "all" or "faq"
* @param  string $author    Required by common function format but not used
*/
function plugin_dopluginsearch_faqman($query, $datestart, $dateend, $topic, $type,
    $author, $keyType, $page = -1, $perPage = -1) {
    global $_CONF, $_TABLES, $_USER, $LANG_FAQ;

    if (empty($type)) {
        $type = 'all';
    }

    // Bail if we aren't supposed to do our search
    if ((($type !== 'all') && ($type !== 'faqman')) ||
            (empty($query) && (!empty($author) ||
             !empty($datestart) ||
             !empty($dateend) ||
             !empty($topic)))) {
        return array();
    }

    $sql = "SELECT topicID AS id, question AS title, answer AS description, hits, name, CURRENT_TIMESTAMP() AS date, 1 AS uid "
        . "  FROM {$_TABLES['faq_topics']} AS t "
        . "LEFT JOIN {$_TABLES['faq_categories']} AS c "
        . "ON t.catID = c.catID "
        . "WHERE 1 ";

    $sc = new SearchCriteria('faqman', 'FAQ');
    $columns = array('title' => 'question', 'answer');
    list($sql, $ftsql) = $sc->buildSearchSQL($keyType, DB_escapeString($query), $columns, $sql);

    $sql .= " GROUP BY t.catID ";

    $sc->setSQL($sql);
    $sc->setFtSQL($ftsql);

    return array($sc, null);
}

/**
* Returns the user option for this plugin
*
* @return   array   Array containing (plugin name, plugin user url, 0)
*/
function plugin_getuseroption_faqman() {
//    global $_CONF;

//    if (SEC_hasRights('faqman.edit,faqman.view','OR')) {
//        $pi_name = FAQMAN_str('plugin');
//        $url     = $_CONF['site_url'] . '/faqman/index.php';
//        return array($pi_name, $url, 0);
//    }
}

/**
* Upgrade the plugin
*
* @return   boolean true (= success)
*/
function plugin_upgrade_faqman() {
    global $_CONF, $_DB_dbms, $_TABLES, $_FAQM_CONF, $_UPDATES;

    // Version info stored in database
    $pi_version = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name = 'faqman'");

    // Do upgrading

    while (version_compare($pi_version, $_FAQM_CONF['pi_version'], '<')) {
        switch ($pi_version) {
          case '0.9.2':
          default:
            require_once __DIR__ . '/sql/' . $_DB_dbms . '_updates.php';
        
            foreach ($_UPDATES['0.9.2'] as $sql) {
                DB_query($sql);
            }
        
            $pi_version = '0.9.3';
            break;
        }
    }

    // Updates plugin info

    $sql = "UPDATE {$_TABLES['plugins']} "
         . "SET pi_version = '" . DB_escapeString($_FAQM_CONF['pi_version']) . "' "
         . "WHERE (pi_name = 'faqman') ";
    $result = DB_query($sql);
    COM_errorLog("faqman plugin was successfully updated to version {$_FAQM_CONF['pi_version']}.");

    return true;
}

/**
* Automatic uninstall function for plugins
*
* @return   array
*/
function plugin_autouninstall_faqman() {
    global $_FAQM_CONF;

    $out = array (
        // give the name of the tables, without $_TABLES[]
        'tables' => array('faq_categories', 'faq_topics'),
        // give the full name of the group, as in the db
        'groups' => array_keys($_FAQM_CONF['GROUPS']),
        // give the full name of the feature, as in the db
        'features' => array_keys($_FAQM_CONF['FEATURES']),
        // give the full name of the block, including 'phpblock_', etc
        'php_blocks' => array(),
        // give all vars with their name
        'vars'=> array(),
    );

    return $out;
}

function plugin_autotags_faqman($op, $content = '', $autotag = '') {
    global $_CONF, $_TABLES;

    if ($op === 'tagname') {
        return 'faq';
    } elseif ($op === 'parse') { 
        if (is_numeric($autotag['parm1'])) {
            if (!empty($autotag['parm2'])) {
                $question = $autotag['parm2'];
            } else {
                $question = DB_getItem($_TABLES['faq_topics'], 'question', "topicID = {$autotag['parm1']}");
            }

            if (!empty($question)) {
                $url = $_CONF['site_url'] . '/faqman/index.php?op=view&amp;t=' . $autotag['parm1'];
                $link = '<a href="' . $url . '">' . FAQMAN_esc($question) . '</a>';
                $content = str_replace($autotag['tagstr'], $link, $content);

                return $content;
            } else {
                return '(FAQ topic #' . $autotag['parm1'] . ' not found)';
            }
        } else {
            return '(error in FAQ link)';
        }
    }
}

function plugin_statssummary_faqman() {
    global $_TABLES, $_FAQM_CONF, $LANG_FAQ;

    $topics = DB_count($_TABLES['faq_topics']);

    return array($LANG_FAQ['statslabel'], COM_numberFormat($topics));
}

/**
* Returns the URL to a given document
*/
function plugin_getdocumentationurl_faqman($file) {
    global $_CONF;

    $lang = COM_getLanguageName();
    $path = $_CONF['path_html'] . 'admin/plugins/faqman/docs/' . $lang . '/'
          . $file . '.html';
    clearstatcache();

    if (!file_exists($path)) {
        $lang = 'english';
    }

    $retval = $_CONF['site_admin_url'] . '/plugins/faqman/docs/' . $lang . '/'
            . $file . '.html';

    return $retval;
}

/**
* Provides text for a Configuration tooltip
*
* @param    string  $id     Id of config value
* @return   mixed           Text to use regular tooltip, null to use config 
*                           tooltip hack, or empty string when not available
*/
function plugin_getconfigtooltip_faqman($id) {
    // Use config tooltip hack where tooltip is read from the config documentation
    return null;
}

/**
* Loads config infor with config.class.php
*/
function FAQMAN_loadConfig() {
    global $_CONF, $_FAQM_CONF;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    $config = config::get_instance();
    if ($config->group_exists('faqman')) {
        $temp = $config->get_config('faqman');

        if (is_array($temp) && (count($temp) > 0)) {
            $_FAQM_CONF = array_merge($_FAQM_CONF, $temp);
        }
    }
}

/**
 * Return a string to safely display as HTML text
 *
 * @param  string
 * @return string
 */
function FAQMAN_esc($str) {
    static $encoding = null;

    if ($encoding === null) {
        if (is_callable('COM_getEncodingt')) {
            $encoding = COM_getEncodingt();
        } else {
            $encoding = COM_getCharset();
        }
    }

    return htmlspecialchars($str, ENT_QUOTES, $encoding);
}
