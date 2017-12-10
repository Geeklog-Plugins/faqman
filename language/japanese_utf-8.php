<?php

// +---------------------------------------------------------------------------+
// | FAQ Manager Plugin for Geeklog - The Ultimate Weblog                      |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/faqman/language/japanese_utf-8.php                        |
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
    die('This file can not be used on its own!');
}

$LANG_FAQ = array (
    'plugin'               => 'FAQ',
    'CATEGORY'             => 'カテゴリー',
    'QUESTION'             => '質問',
    'ANSWER'               => '回答',
    'headerlabel'          => 'FAQ',
    'searchlabel'          => 'FAQ',
    'searchresults'        => 'FAQの検索結果',
    'statslabel'           => 'FAQのトピック数',
    'no_cats'              => 'FAQのカテゴリーが登録されていません。',
    'no_topics'            => 'このカテゴリーにはFAQのトピックが登録されていません。',
    'admin'                => 'FAQプラグイン管理',
    'admin_menu'           => 'FAQ管理',
    'admin_menu_desc'      => 'FAQのカテゴリーとトピックを管理します。<span style="color: red;">カテゴリーを削除すると、所属しているトピックもすべて削除されます。</span>',
    'admin_create_cat'     => 'カテゴリーの新規作成',
    'admin_create_topic'   => 'トピックの新規作成',
    'admin_list_cats'      => 'カテゴリー一覧',
    'admin_cat_editor'     => 'FAQカテゴリー編集',
    'admin_cat_id'         => 'ID',
    'admin_cat_name'       => 'カテゴリー名',
    'admin_cat_desc'       => '説明',
    'admin_cat_num_topics' => 'トピック数',
    'admin_list_topics'    => 'トピック一覧',
    'admin_topic_editor'   => 'FAQトピック編集',
    'admin_topic_id'       => 'ID',
    'admin_topic_name'     => 'トピック名',
    'admin_topic_question' => '質問',
    'admin_topic_answer'   => '回答',
    'error'                => 'エラー',
    'error_page'           => 'エラー|FAQ',
    'error_1'              => 'カテゴリーIDが無効です。',
    'error_2'              => '編集中のカテゴリーを保存できませんでした。',
    'error_3'              => 'トピックIDが無効です。',
    'error_4'              => '編集中のトピックを保存できませんでした。',
    'error_5'              => '無効なカテゴリーを選択しました。',
    'error_6'              => 'このカテゴリーにはトピックがありません。',
    'error_7'              => 'トピックが見つかりません。',
    'message_1'            => 'カテゴリーを保存しました。',
    'message_2'            => 'カテゴリーを削除しました。',
    'message_3'            => 'トピックを保存しました。',
    'message_4'            => 'トピックを削除しました。',
);

// Localization of the Admin Configuration UI
$LANG_configsections['faqman'] = array(
    'label' => $LANG_FAQ['plugin'],
    'title' => $LANG_FAQ['plugin'] . 'の設定',
);

$LANG_confignames['faqman'] = array(
    'default_permissions' => 'パーミッション',
);

$LANG_configsubgroups['faqman'] = array(
    'sg_main' => '主要設定'
);

$LANG_fs['faqman'] = array(
    'fs_main'        => $LANG_FAQ['plugin'] . 'の主要設定',
    'fs_permissions' => 'パーミッション',
);

// Note: entries 0, 1, 9, and 12 are the same as in $LANG_configselects['Core']
$LANG_configselects['faqman'] = array(
    0 => array('はい' => 1, 'いいえ' => 0),
    1 => array('はい' => true, 'いいえ' => FALSE),
    9 => array('Forward to page' => 'item', 'Display List' => 'list', 'Display Home' => 'home', 'Display Admin' => 'admin'),
    12 => array('アクセス不可' => 0, '表示' => 2, '表示・編集' => 3),
);
