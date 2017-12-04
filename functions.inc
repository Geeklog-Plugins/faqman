<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Geeklog FAQ Plugin 0.8 for Geeklog - The Ultimate Weblog                  |
// | Official release date: Mar 9,2003                                         |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// | This file implements the necessary Geeklog Plugin API functions and       |
// | includes some common Forum functions                                      |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000,2001,2002,2003 by the following authors:               |
// | Geeklog Author: Tony Bibbs       - tony@tonybibbs.com                     |
// +---------------------------------------------------------------------------+
// | FAQ Plugin Author                                                         |
// | Authors: FAQ Appllication:   Stephen Ball, stephen@aquonics.com           |
// | Conversion to Geeklog Plugin: Blaine Lang, blaine@portalparts.com         |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Stephen Ball, the author of FAQ Manager gave me (Blaine Lang) permission  |
// | to create this Geeklog Plugin using his application and make it available |
// | to the Geeklog community.                                                 |
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

if (strpos(strtolower($_SERVER['PHP_SELF']), 'functions.inc') !== false) {
    die('This file can not be used on its own.');
}

$langfile = $_CONF['path'] . 'plugins/faqman/language/' . $_CONF['language'] . '.php';
if (file_exists ($langfile)) 
{
    include_once ($langfile);
}
else 
{
    include_once ($_CONF['path'] . 'plugins/faqman/language/english.php');
}

require_once ($_CONF['path'] . 'plugins/faqman/config.php');

$_FAQM_CONF['version'] = '0.8.1';

/**
* Returns the items for this plugin that should appear on the main menu
*/
function plugin_getmenuitems_faqman() {
    global $_CONF,$LANG_FAQ;

    $menuitems[$LANG_FAQ['headerlabel']] ="{$_CONF['site_url']}/faqman/index.php";
    return $menuitems;
}

/**
* returns the administrative option for this plugin
* *
*/

function plugin_getadminoption_faqman() {
    global $_CONF, $_TABLES, $LANG_FAQ;
    if (SEC_hasRights('faqman.edit')) {
        $result = DB_query("SELECT COUNT(*) FROM {$_TABLES['faq_topics']}");
        list($faqtopics) = DB_fetchARRAY($result);
        return array($LANG_FAQ['admin_menu'], $_CONF['site_url'] . '/faqman/admin/index.php', $faqtopics);
   }
}

/**
* This will put an option for Filemgmt Admin in the command and control block on
* moderation.php
*
*/
function plugin_cclabel_faqman() {
    global $LANG_FAQ, $_CONF;

    if (SEC_hasRights('faqman.edit')) {
        return array($LANG_FAQ['plugin_name'],$_CONF['site_url'] . '/faqman/admin/index.php',$_CONF['site_url'] . '/faqman/images/faqman.gif');
    }

    return false;
}


/**
* Geeklog is asking us to provide any new items that show up in the type drop-down
* on search.php.  Let's let users search the Filelistings in the Filemgmt Plugin
*
*/
function plugin_searchtypes_faqman()
{
    global $LANG_FAQ;

    $tmp['faqman'] = $LANG_FAQ['searchlabel'];

    return $tmp;
}

/**
* this searches for files matching the user query and returns an array of 
* for the header and table rows back to search.php where it will be formated and
* printed 
*
* @query            string          Keywords user is looking for
* @datestart        date/time       Start date : Required by common function format but not used
* @dateend          date/time       End date : Required by common function format but not used
* @topic            string          Required by common function format but not used
* @type             string          Type of items they are searching - will be "all" or "faq"
* @author           string          Required by common function format but not used
*
*/

function plugin_dopluginsearch_faqman ($query, $datestart, $dateend, $topic, $type, $author, $keyType, $page = -1, $perpage = -1) 
{
    global $_CONF, $_TABLES, $LANG_FAQ;

    require_once ($_CONF['path_html'] ."faqman/data/stopwords.inc.php");
		
    if (empty ($type)) {
        $type = 'all';
    }
    
    // Bail if we aren't supppose to do our search
    if (($type != 'all') && ($type != 'faqman')) {
        $plugin_results = new Plugin();
        $plugin_results->plugin_name = $LANG_FAQ['plugin_name'];
        $plugin_results->searchlabel = $LANG_FAQ['searchlabel'];

        return $plugin_results;
    }

    if (empty ($query) && (!empty ($author) || !empty ($datestart) || !empty ($dateend) || !empty ($topic))) {
        $plugin_results = new Plugin();
        $plugin_results->plugin_name = $LANG_FAQ['plugin_name'];
        $plugin_results->searchlabel = $LANG_FAQ['searchlabel'];

        return $plugin_results;
    }

    $select = 'SELECT topicID, question, answer, name AS category';
    $sql = " FROM {$_TABLES['faq_topics']} topic LEFT JOIN {$_TABLES['faq_categories']} category ON topic.catID = category.catID";

    $prefix = ' WHERE';
    if (!empty ($query)) {
        if ($keyType == 'phrase') {
            $mysearchterm = addslashes ($query);
            $sql .= " WHERE ((question LIKE '%$mysearchterm%')"
                 . " OR (answer LIKE '%$mysearchterm%'))";
        } else if ($keyType == 'all') {
            $mywords = explode (' ', $query);
            $sql .= ' WHERE (';
            $tmp = '';
            foreach ($mywords AS $mysearchterm) {
                $mysearchterm = addslashes (trim ($mysearchterm));
                if (!empty ($mysearchterm)) {
                    $tmp .= "((question LIKE '%$mysearchterm%')"
                         . " OR (answer LIKE '%$mysearchterm%')"
                         . " OR (MATCH (keywords) AGAINST ('%$mysearchterm%'))) AND ";
                }
            }
            $tmp = substr ($tmp, 0, strlen ($tmp) - 5);
            $sql .= $tmp . ')';
        } else if ($keyType == 'any') {
            $mywords = explode (' ', $query);
            $sql .= ' WHERE (';
            $tmp = '';
            foreach ($mywords AS $mysearchterm) {
                $mysearchterm = addslashes (trim ($mysearchterm));
                if (!empty ($mysearchterm)) {
                    $tmp .= "((question LIKE '%$mysearchterm%')"
                         . " OR (answer LIKE '%$mysearchterm%')"
                         . " OR (MATCH (keywords) AGAINST ('%$mysearchterm%'))) OR ";
                }
            }
            $tmp = substr ($tmp, 0, strlen ($tmp) - 4);
            $sql .= $tmp . ')';
        } else {
            $mysearchterm = addslashes ($query);
            $sql .= " WHERE ((question LIKE '%$mysearchterm%')"
                 . " OR (answer LIKE '%$mysearchterm%'))";
        }
        $prefix = ' AND';
    }
    $sql .= ' ORDER BY topicID';
    if (($perpage > 0) && ($page > 0)) {
        $l = ($perpage * $page) - $perpage;
        $sql .= ' LIMIT ' . $l . ',' . $perpage;
    }

    // Perform search
    $result = DB_query ($select . $sql);
    $mycount = DB_numRows ($result);
    if (($perpage > 0) && ($page > 0)) {
        $result_count = DB_query ('SELECT COUNT(*)' . $sql);
    } else {
        $result_count = DB_query ('SELECT COUNT(*) FROM ' . $_TABLES['faq_topics']);
    }
    $B = DB_fetchArray ($result_count, true);

	// OK, now return table header labels
	require_once ($_CONF['path_system'] . 'classes/plugin.class.php');

	$plugin_results = new Plugin();
	$plugin_results->plugin_name = 'faqman';
	$plugin_results->searchlabel = $LANG_FAQ['searchresults'];
	$plugin_results->addSearchHeading($LANG_FAQ['CATEGORY']);
	$plugin_results->addSearchHeading($LANG_FAQ['QUESTION']);
	$plugin_results->addSearchHeading($LANG_FAQ['ANSWER']);
    $plugin_results->num_searchresults = 0;
    $plugin_results->num_itemssearched = $B[0];
    if (($perpage > 0) && ($page > 0)) {
        $plugin_results->supports_paging = true;
    }

    for ($i = 0; $i < $mycount; $i++) {
	    $A = DB_fetchArray ($result);
	    $searchcount++;
	    $question = strip_tags($A['question']);
	    $answer = strip_tags(substr($A['answer'],0,60)) . '...';
	    $url = $_CONF['site_url']. "/faqman/index.php?op=view&t={$A['topicID']}";
	    $row = array($A['category'],"<a href=\"$url\">$question</a>",$answer);
	    $plugin_results->addSearchResult($row);
        $plugin_results->num_searchresults++;
	}	

    return $plugin_results;
}

/**
* Automatic uninstall function for plugins
*
* @return   array
*
* This code is automatically uninstalling the plugin.
* It passes an array to the core code function that removes
* tables, groups, features and php blocks from the tables.
* Additionally, this code can perform special actions that cannot be
* foreseen by the core code (interactions with other plugins for example)
*
*/
function plugin_autouninstall_faqman()
{
    $out = array(
        /* give the name of the tables, without $_TABLES[] */
        'tables' => array('faq_categories', 'faq_topics'),
        /* give the full name of the group, as in the db */
        'groups' => array('faqman Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('faqman.edit', 'faqman.user'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array(),
        /* give all vars with their name */
        'vars' => array('faqman_admin')
    );

    return $out;
}

function plugin_autotags_faqman ($op, $content = '', $autotag = '')
{
    global $_CONF, $_TABLES;

    if ($op == 'tagname' ) {
        return 'faq';     } else if ($op == 'parse') { 
        if (is_numeric ($autotag['parm1'])) {
            if (!empty ($autotag['parm2'])) {
                $question = $autotag['parm2'];
            } else {
                $question = DB_getItem ($_TABLES['faq_topics'], 'question',
                                        "topicID = {$autotag['parm1']}");
            }
            if (!empty ($question)) {
                $url = $_CONF['site_url'] . '/faqman/index.php?op=view&amp;t='                     . $autotag['parm1'];
                $link = '<a href="' . $url . '">' . $question . '</a>';
                $content = str_replace ($autotag['tagstr'], $link, $content);
        
                return $content;
            } else {
                return '(FAQ topic #' . $autotag['parm1'] . ' not found)';
            }
        } else {
            return '(error in FAQ link)';
        }
    }
}

function plugin_statssummary_faqman ()
{
    global $LANG_FAQ, $_DB_table_prefix;

    $tbprefix   = $_DB_table_prefix . 'faq';
    $topics = DB_count ($tbprefix . '_topics');

    return array ($LANG_FAQ['statslabel'], COM_numberFormat ($topics));
}

function plugin_chkversion_faqman ()
{
    global $_FAQM_CONF;

    return $_FAQM_CONF['version'];
}

function plugin_upgrade_faqman()
{
    global $_TABLES, $_FAQM_CONF;

    // the plugin needs this function so complain when it doesn't exist
    if (!function_exists('PLG_uninstall')) {
        return 3002;
    }

    DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = '{$_FAQM_CONF['version']}' WHERE pi_name = 'faqman'");

    return true;
}

?>
