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
	'R2T_NOTIFICATION'	=> 'R2Topic',

	'r2t_tokens'	=> array(
		'REPORTER'		=> 'Username of the reporter.',
		'REPORTER_FULL'	=> 'Full profile link to the profile of the reporter',
		'REPORT_LINK'	=> 'Link to the report page in the MCP.',
		'REPORT_POST'	=> 'Link to the reported post. <strong>Not available in PM reports!</strong>',
		'REPORT_REASON'	=> 'Reason for the report.',
		'REPORT_TEXT'	=> 'Description submitted by the user in the report.',
		'REPORT_TIME'	=> 'Time of the report.',
		'TITLE'			=> 'The subject of the reported post or PM',
	),
));