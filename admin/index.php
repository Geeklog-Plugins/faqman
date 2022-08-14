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
 * Mar/2003: Modified to support Geeklog
 * Blaine Lang  blaine@portalparts.com*
 * 2017-12-10 mystral-kk modified to support Geeklog 2.1.3
 */

require_once '../../../lib-common.php';
require_once $_CONF['path_system'] . 'lib-admin.php';

// Check for permission
if (!SEC_hasRights('faqman.edit')) {
    // Someone is trying to illegally access this page
    $uid = isset($_USER['uid']) ? $_USER['uid'] : '?';
    $userName = isset($_USER['username']) ? $_USER['username'] : '?';
    COM_errorLog("Someone has tried to illegally access the FaqMan Admin page.  User id: {$uid}, Username: {$userName}, IP: {$_SERVER['REMOTE_ADDR']}", 1);
    $content = COM_startBlock(RECAPTCHA_esc($LANG_ACCESS['accessdenied']))
        . $LANG_ACCESS['plugin_access_denied_msg']
        . COM_endBlock();
    $display = COM_createHTMLDocument($content);
    COM_output($display);
    exit;
}

/**
 * Return a form to edit a category
 *
 * @param  int   $catID
 * @param  array $errors
 * @return string
 */
function FAQMAN_editCat($catID, array $errors = array()) {
    global $_CONF, $_TABLES, $LANG_FAQ, $LANG21, $MESSAGE;

    $catID = (int) $catID;

    if ($catID <= 0) {
        $catID = 0;
        $A = array(
            'catID'       => 0,
            'name'        => '',
            'description' => '',
            'total'       => 0,
        );
    } else {
        $sql = "SELECT * FROM {$_TABLES['faq_categories']} WHERE (catID = {$catID})";
        $result = DB_query($sql);

        if (DB_error() || (DB_numRows($result) == 0)) {
            COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?e=1');
        } else {
            $A = DB_fetchArray($result, false);
        }
    }

    $editorStart = COM_startBlock(
        $LANG_FAQ['admin_cat_editor'], '',
        COM_getBlockTemplate('_admin_block', 'header')
    ) . PHP_EOL;
    $editorEnd = COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));

    $T = COM_newTemplate($_CONF['path'] . 'plugins/faqman/templates/admin');
    $T->set_file('cat_editor', 'cat_editor.thtml');
    $T->set_var(array(
        'start_editor'              => $editorStart,
        'end_editor'                => $editorEnd,
        'catID'                     => $A['catID'],
        'name'                      => $A['name'],
        'description'               => $A['description'],
        'total'                     => $A['total'],
        'action_url'                => $_CONF['site_admin_url'] . '/plugins/faqman/index.php?what=cat',
        'token_name'                => CSRF_TOKEN,
        'token_value'               => SEC_createToken(),
        'allow_delete'              => ($catID > 0),
        'lang_admin_cat_id'         => $LANG_FAQ['admin_cat_id'],
        'lang_admin_cat_name'       => $LANG_FAQ['admin_cat_name'],
        'lang_admin_cat_desc'       => $LANG_FAQ['admin_cat_desc'],
        'lang_admin_cat_num_topics' => $LANG_FAQ['admin_cat_num_topics'],
        'lang_save'                 => $LANG21[54],
        'lang_delete'               => $LANG21[56],
        'lang_delete_confirm'       => $MESSAGE[76],
        'lang_cancel'               => $LANG21[55],
    ));

    if (count($errors) > 0) {
        $T->set_var($errors);
    }

    $T->parse('output', 'cat_editor');

    return $T->finish($T->get_var('output'));
}

/**
 * Save a category
 *
 * @param  int $catID
 */
function FAQMAN_updateCat($catID) {
    global $_CONF, $_TABLES, $LANG_VALIDATION;

    $catID = (int) $catID;

    if (!SEC_checkToken()) {
        die();
    }

    $name = trim(Geeklog\Input::post('name', ''));
    $desc = trim(Geeklog\Input::post('description', ''));

    if (is_callable('GLText::remove4byteUtf8Chars')) {
        $name = GLText::remove4byteUtf8Chars($name);
        $desc = GLText::remove4byteUtf8Chars($desc);
    }

    $errors = array();
    if (empty($name)) {
        $error['error_name'] = $LANG_VALIDATION['notEmpty'];
    }
    if (empty($desc)) {
        $error['error_desc'] = $LANG_VALIDATION['notEmpty'];
    }

    if (count($errors) > 0) {
        return FAQMAN_editCat($catID, $errors);
    }

    $name = DB_escapeString($name);
    $desc = DB_escapeString($desc);

    if ($catID === 0) {
        $sql = "INSERT INTO {$_TABLES['faq_categories']} (catID, name, description) "
            . "VALUES (0, '{$name}', '{$desc}')";
    } else {
        $sql = "UPDATE {$_TABLES['faq_categories']} SET name = '{$name}', description = '{$desc}' "
            . "WHERE (catID = {$catID})";
    }

    DB_query($sql);

    if (DB_error()) {
        COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?e=2');
    } else {
        COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?m=1');
    }
}

/**
 * Delete a category
 *
 * If a category is deleted, then all the topics belonging to it will be deleted as well.
 *
 * @param  int $catID
 */
function FAQMAN_deleteCat($catID) {
    global $_CONF, $_TABLES;

    $catID = (int) $catID;

    if (!SEC_checkToken()) {
        die();
    }

    $sql1 = "DELETE FROM {$_TABLES['faq_categories']} WHERE (catID = {$catID}) LIMIT 1";
    DB_query($sql1);
    $sql2 = "DELETE FROM {$_TABLES['faq_topics']} WHERE (catID = {$catID})";
    DB_query($sql2);
    COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?m=2');
}

/**
 * Return a form to edit a topic
 *
 * @param  int   $topicID
 * @param  array $errors
 * @return string
 */
function FAQMAN_editTopic($topicID, array $errors = array()) {
    global $_CONF, $_TABLES, $LANG_FAQ, $LANG21, $MESSAGE;

    $topicID = (int) $topicID;

    if ($topicID <= 0) {
        $topicID = 0;
        $A = array(
            'topicID'  => 0,
            'catID'    => 0,
            'question' => '',
            'answer'   => '',
        );
    } else {
        $sql = "SELECT * FROM {$_TABLES['faq_topics']} WHERE (topicID = {$topicID})";
        $result = DB_query($sql);

        if (DB_error() || (DB_numRows($result) == 0)) {
            COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?e=3');
        } else {
            $A = DB_fetchArray($result, false);
        }
    }

    $catIDOptions = array();
    $sql = "SELECT catID, name FROM {$_TABLES['faq_categories']} ORDER BY catID";
    $result = DB_query($sql);

    while (($row = DB_fetchArray($result, false)) !== false) {
        $selected = ($A['catID'] == $row['catID']) ? ' selected="selected"' : '';
        $catIDOptions[] = sprintf(
            '<option value="%s"%s>%s</option>', 
            $row['catID'], $selected, $row['name']
        );
    }


    $editorStart = COM_startBlock(
        $LANG_FAQ['admin_topic_editor'], '',
        COM_getBlockTemplate('_admin_block', 'header')
    ) . PHP_EOL;
    $editorEnd = COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));

    $T = COM_newTemplate($_CONF['path'] . 'plugins/faqman/templates/admin');
    $T->set_file('topic_editor', 'topic_editor.thtml');
    $T->set_var(array(
        'start_editor'              => $editorStart,
        'end_editor'                => $editorEnd,
        'topicID'                   => $A['topicID'],
        'catIDOptions'              => implode(PHP_EOL, $catIDOptions) . PHP_EOL,
        'question'                  => $A['question'],
        'answer'                    => $A['answer'],
        'action_url'                => $_CONF['site_admin_url'] . '/plugins/faqman/index.php?what=topic',
        'token_name'                => CSRF_TOKEN,
        'token_value'               => SEC_createToken(),
        'allow_delete'              => ($topicID > 0),
        'lang_admin_topic_id'       => $LANG_FAQ['admin_topic_id'],
        'lang_admin_cat_name'       => $LANG_FAQ['admin_cat_name'],
        'lang_admin_topic_question' => $LANG_FAQ['admin_topic_question'],
        'lang_admin_topic_answer'   => $LANG_FAQ['admin_topic_answer'],
        'lang_save'                 => $LANG21[54],
        'lang_delete'               => $LANG21[56],
        'lang_delete_confirm'       => $MESSAGE[76],
        'lang_cancel'               => $LANG21[55],
    ));

    if (count($errors) > 0) {
        $T->set_var($errors);
    }

    $T->parse('output', 'topic_editor');

    return $T->finish($T->get_var('output'));
}

/**
 * Update the number of topics each category has
 */
function FAQMAN_updateTopicCount() {
    global $_TABLES;

    $catIDs = array();
    $sql = "SELECT catID FROM {$_TABLES['faq_categories']} ORDER BY catID";
    $result = DB_query($sql);

    while (($A = DB_fetchArray($result, false)) !== false) {
        $catIDs[] = $A['catID'];
    }

    if (count($catIDs) > 0) {
        foreach ($catIDs as $catID) {
            $topicCount = DB_count($_TABLES['faq_topics'], 'catID', $catID);
            $sql = "UPDATE {$_TABLES['faq_categories']} SET total = {$topicCount} "
                . "WHERE (catID = {$catID})";
            DB_query($sql);
        }
    }
}

/**
 * Save a topic
 *
 * @param  int $topicID
 */
function FAQMAN_updateTopic($topicID) {
    global $_CONF, $_TABLES, $LANG_VALIDATION, $LANG_FAQ;

    $topicID = (int) $topicID;

    if (!SEC_checkToken()) {
        die();
    }

    $catID = (int) Geeklog\Input::fPost('catID', 0);
    $question = trim(Geeklog\Input::post('question', ''));
    $answer = trim(Geeklog\Input::post('answer', ''));

    if (is_callable('GLText::remove4byteUtf8Chars')) {
        $question = GLText::remove4byteUtf8Chars($question);
        $answer = GLText::remove4byteUtf8Chars($answer);
    }

    $answer = COM_checkHTML($answer);

    $errors = array();
    if (DB_count($_TABLES['faq_categories'], 'catID', $catID) == 0) {
        $error['error_catID'] = $LANG_FAQ['error_1'];
    }
    if (empty($question)) {
        $error['error_question'] = $LANG_VALIDATION['notEmpty'];
    }
    if (empty($answer)) {
        $error['error_answer'] = $LANG_VALIDATION['notEmpty'];
    }

    if (count($errors) > 0) {
        return FAQMAN_editTopic($topicID, $errors);
    }

    $question = DB_escapeString($question);
    $answer = DB_escapeString($answer);

    if ($topicID === 0) {
        $sql = "INSERT INTO {$_TABLES['faq_topics']} (topicID, catID, question, answer) "
            . "VALUES (0, {$catID}, '{$question}', '{$answer}')";
    } else {
        $sql = "UPDATE {$_TABLES['faq_topics']} "
            . "SET catID = {$catID}, question = '{$question}', answer = '{$answer}' "
            . "WHERE (topicID = {$topicID})";
    }

    DB_query($sql);

    if (DB_error()) {
        COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?e=4');
    } else {
        FAQMAN_updateTopicCount();
        COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?m=3');
    }
}

/**
 * Delete a topic
 *
 * @param  int $topicID
 */
function FAQMAN_deleteTopic($topicID) {
    global $_CONF, $_TABLES;

    $topicID = (int) $topicID;

    if (!SEC_checkToken()) {
        die();
    }

    $sql = "DELETE FROM {$_TABLES['faq_topics']} WHERE (topicID = {$topicID}) LIMIT 1";
    DB_query($sql);
    FAQMAN_updateTopicCount();
    COM_redirect($_CONF['site_admin_url'] . '/plugins/faqman/index.php?m=4');
}

/**
 * Field function for category list
 *
 * @param  string $fieldName
 * @param  string $fieldValue
 * @param  array  $A
 * @param  array  $iconArray
 * @param  string $suffix
 * @return string
 * @throws Exception
 */
function FAQMAN_getListField_topics($fieldName, $fieldValue, $A, $iconArray) {
    global $_CONF, $_TABLES, $LANG01;

    switch ($fieldName) {
        case 'edit':
            $link = $_CONF['site_admin_url']
                . '/plugins/faqman/index.php?what=topic&amp;do=edit&amp;topicID='. $A['topicID'];
            $retval = '<a href="' . $link . '" title="' . $LANG01[4] . '">' . $iconArray['edit'] . '</a>';
            break;

        case 'catID':
            $retval = DB_getItem($_TABLES['faq_categories'], 'name', "(catID = {$A['catID']})");
            break;

        case 'answer':
            $retval = $fieldValue;
            break;

        case 'question':
        default:
            $retval = FAQMAN_esc($fieldValue);
            break;
    }

    return $retval;
}

/**
 * List FAQ Topics
 *
 * @return string
 */
function FAQMAN_listTopics() {
    global $_CONF, $_TABLES, $LANG01, $LANG_FAQ;

    $headerArray = array(
        array(
            'text'  => $LANG01[4],
            'field' => 'edit',
            'sort'  => false,
        ),
        array(
            'text'  => $LANG_FAQ['admin_topic_id'],
            'field' => 'topicID',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_cat_name'],
            'field' => 'catID',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_topic_question'],
            'field' => 'question',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_topic_answer'],
            'field' => 'answer',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_topic_hits'],
            'field' => 'hits',
            'sort'  => true,
        ),
    );

    $defaultSortArray = array('field' => 'topicID', 'direction' => 'asc');
    $textArray = array(
        'has_extras' => true,
        'title'      => $LANG_FAQ['admin_list_topics'],
        'form_url'   => $_CONF['site_admin_url'] . '/plugins/faqman/index.php',
    );

    $queryArray = array(
        'table'          => 'faq_topics',
        'sql'            => "SELECT * FROM " . $_TABLES['faq_topics'] . " WHERE (1 = 1) ",
        'query_fields'   => array('topicID', 'catID', 'question', 'answer'),
        'default_filter' => COM_getPermSql('AND'),
    );

    $catList = ADMIN_list(
        'cats', 'FAQMAN_getListField_topics', $headerArray, $textArray,
        $queryArray, $defaultSortArray
    );

    return $catList;
}

/**
 * Field function for category list
 *
 * @param  string $fieldName
 * @param  string $fieldValue
 * @param  array  $A
 * @param  array  $iconArray
 * @param  string $suffix
 * @return string
 * @throws Exception
 */
function FAQMAN_getListField_cats($fieldName, $fieldValue, $A, $iconArray) {
    global $_CONF, $LANG01;

    switch ($fieldName) {
        case 'edit':
            $link = $_CONF['site_admin_url']
                . '/plugins/faqman/index.php?what=cat&amp;do=edit&amp;catID='. $A['catID'];
            $retval = '<a href="' . $link . '" title="' . $LANG01[4] . '">' . $iconArray['edit'] . '</a>';
            break;

        default:
            $retval = FAQMAN_esc($fieldValue);
            break;
    }

    return $retval;
}

/**
 * List FAQ Categories
 *
 * @return string
 */
function FAQMAN_listCats() {
    global $_CONF, $_TABLES, $LANG01, $LANG_FAQ;

    $headerArray = array(
        array(
            'text'  => $LANG01[4],
            'field' => 'edit',
            'sort'  => false,
        ),
        array(
            'text'  => $LANG_FAQ['admin_cat_id'],
            'field' => 'catID',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_cat_name'],
            'field' => 'name',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_cat_desc'],
            'field' => 'description',
            'sort'  => true,
        ),
        array(
            'text'  => $LANG_FAQ['admin_cat_num_topics'],
            'field' => 'total',
            'sort'  => true,
        ),
    );

    $defaultSortArray = array('field' => 'catID', 'direction' => 'asc');
    $textArray = array(
        'has_extras' => true,
        'title'      => $LANG_FAQ['admin_list_cats'],
        'form_url'   => $_CONF['site_admin_url'] . '/plugins/faqman/index.php',
    );

    $queryArray = array(
        'table'          => 'faq_categories',
        'sql'            => "SELECT * FROM " . $_TABLES['faq_categories'] . " WHERE (1 = 1) ",
        'query_fields'   => array('catID', 'name', 'description', 'total'),
        'default_filter' => COM_getPermSql('AND'),
    );

    $catList = ADMIN_list(
        'cats', 'FAQMAN_getListField_cats', $headerArray, $textArray,
        $queryArray, $defaultSortArray
    );

    return $catList;
}

/**
 * Build the FAQ admin header
 *
 * @return string
 */
function FAQMAN_header() {
    global $_CONF, $_SCRIPTS, $LANG_ADMIN, $LANG_FAQ;

    // Create a security token to be used in both lists
    $securityToken = SEC_createToken();

    // Writing the menu on top
    $menu_arr = array(
        array(
            'url'  => $_CONF['site_admin_url'],
            'text' => $LANG_ADMIN['admin_home'],
        ),
        array(
            'url'  => $_CONF['site_admin_url'] . '/plugins/faqman/index.php?what=cat&amp;do=edit&amp;catID=0',
            'text' => $LANG_FAQ['admin_create_cat'],
        ),
        array(
            'url'  => $_CONF['site_admin_url'] . '/plugins/faqman/index.php?what=topic&amp;do=edit&amp;topicID=0',
            'text' => $LANG_FAQ['admin_create_topic'],
        ),
    );
    $retval = COM_startBlock(
            $LANG_FAQ['admin_menu'], '',
            COM_getBlockTemplate('_admin_block', 'header')
        )
        . ADMIN_createMenu(
            $menu_arr,
            $LANG_FAQ['admin_menu_desc'],
            $_CONF['site_url'] . '/faqman/images/faqman.gif'
        )
        . COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));

    $m = (int) Geeklog\Input::fGet('m', 0);
    if (($m > 0) && isset($LANG_FAQ['message_' . $m])) {
        $retval .= COM_showMessageText($LANG_FAQ['message_' . $m]);
    }

    $e = (int) Geeklog\Input::fGet('e', 0);
    if (($e > 0) && isset($LANG_FAQ['error_' . $e])) {
        $retval .= COM_showMessageText($LANG_FAQ['error_' . $e]);
    }

    return $retval;

}

// Main

// Get target
$what = Geeklog\Input::fGet('what', 'cat');
if (($what !== 'cat') && ($what !== 'topic')) {
    $what = 'topic';
}

// Get action
$do = Geeklog\Input::fGet('do', Geeklog\Input::fPost('do', ''));
if (!in_array($do, array('edit', 'delete', 'update', 'list'))) {
    $mode = Geeklog\Input::post('mode', '');

    switch ($mode) {
        case $LANG21[54]:
            $do = 'update';
            break;

        case $LANG21[56]:
            $do = 'delete';
            break;

        case $LANG21[55]:   // Cancel
        default:
            $do = 'list';
            break;
    }
}

// Get IDs if any
$catID = (int) Geeklog\Input::fGet('catID', Geeklog\Input::fPost('catID', 0));
$topicID = (int) Geeklog\Input::fGet('topicID', Geeklog\Input::fPost('topicID', 0));

// Dispatch
switch ($do) {
    case 'edit':
        $content = ($what === 'cat')
            ? FAQMAN_editCat($catID)
            : FAQMAN_editTopic($topicID);
        break;

    case 'delete':
        $content = ($what === 'cat')
            ? FAQMAN_deleteCat($catID)
            : FAQMAN_deleteTopic($topicID);
        break;

    case 'update':
        $content = ($what === 'cat')
            ? FAQMAN_updateCat($catID)
            : FAQMAN_updateTopic($topicID);
        break;

    case 'list':
    default:
        $content = FAQMAN_listCats() . FAQMAN_listTopics();
        break;
}

$content = FAQMAN_header() . $content;
$output = COM_createHTMLDocument($content, array('what' => 'menu'));
COM_output($output);
