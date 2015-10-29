<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPORTTOTOPIC'					=> 'Report to topic',
	'ACP_REPORTTOTOPIC_CONFIG'			=> 'Report to topic configuration',
	'ACP_REPORTTOTOPIC_CONFIG_EXPLAIN'	=> 'The main report to topic configuration.',
	'ACP_REPORTTOTOPIC_CONFIG_OPTIONS'	=> 'Options',
	'ACP_REPORTTOTOPIC_CONFIG_SUCCESS'	=> 'The main configuration has been updated successfully!',
	'ACP_REPORTTOTOPIC_PM_CONFIG'		=> 'PM report configuration',

	'FORUM_NOT_EXISTS'					=> 'The requested forum (%1$s) does not exist',

	'HIDE_TOPIC_LINK'					=> 'Hide links',
	'HIDE_TOPIC_LINK_EXPLAIN'			=> 'Hide the report link to MCP in viewtopic and the report icon in viewforum.',

	'LOCK_TOPIC'						=> 'Lock topic',
	'LOCK_TOPIC_EXPLAIN'				=> 'Lock the report topic when the report is closed.',

	'NO_FORUM_LOG'						=> '<strong>The report to topic destination forum does not exist</strong>',

	'PARSE_SIG'							=> 'Display signature',

	'R2T_DEST_FORUM'					=> 'Default destination forum',
	'R2T_DEST_FORUM_EXPLAIN'			=> 'Select the forum that will be used as the default destination forum for the report posts. You can select on a forum basis a different report forum.',
	'R2T_PM_DEST_FORUM'					=> 'PM report destination forum',
	'R2T_PM_DEST_FORUM_EXPLAIN'			=> 'Select the forum that will be used to post PM reports to.',
	'R2T_PM_TITLE'						=> 'PM report title',
	'R2T_PM_TITLE_EXPLAIN'				=> 'Here you can define the post title for PM report topics. You can use tokens in the topic title.',
	'R2T_PM_TEMPLATE'					=> 'PM template',
	'R2T_PM_TEMPLATE_EXPLAIN'			=> 'Here you can define how the PM report posts will be formatted. By using tokens you can specify which information will be displayed. You can use BBCodes in the post template.',
	'R2T_POST_TITLE'					=> 'Post report title',
	'R2T_POST_TITLE_EXPLAIN'			=> 'Here you can define the post title for post report topics. You can use tokens in the topic title.',
	'R2T_POST_TEMPLATE'					=> 'Post template',
	'R2T_POST_TEMPLATE_EXPLAIN'			=> 'Here you can define how the post report posts will be formatted. By using tokens you can specify which information will be displayed. You can use BBCodes in the post template.',
	'R2T_SELECT_DEST_FORUM'				=> 'Report destination forum',
	'R2T_SELECT_DEST_FORUM_EXPLAIN'		=> 'Select the forum into which reports that are made from this forum will be posted. If not selected the default forum will be used.',
	'REPORTTOTOPIC_LOG'					=> '<strong>Report to topic settings updated</strong>',

	'TOKEN'								=> 'Token',
	'TOKENS'							=> 'Tokens',
	'TOKENS_EXPLAIN'					=> 'Tokens are placeholders for various pieces of information that can be displayed in the report post.<br /><br /><strong>Please note that only tokens listed below can be used in the report post.</strong>',
	'TOKEN_DEFINITION'					=> 'What will it be replaced with?',
));