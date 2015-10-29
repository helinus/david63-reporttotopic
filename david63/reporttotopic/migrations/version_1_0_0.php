<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\reporttotopic\migrations;

class version_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('config.add', array('r2t_dest_forum', '1')),
			array('config.add', array('r2t_lock_topic', '1')),
			array('config.add', array('r2t_hide_topic_link', '0')),
			array('config.add', array('r2t_pm_dest_forum', '1')),
			array('config.add', array('r2t_pm_template', 'Title: {TITLE}
Reporter: {REPORTER_FULL}
Reported for: {REPORT_REASON}
Notes: {REPORT_TEXT}
Link to report in MCP: {REPORT_LINK}')),
			array('config.add', array('r2t_pm_template_bbcode', '1')),
			array('config.add', array('r2t_pm_template_sig', '1')),
			array('config.add', array('r2t_pm_template_smilies', '1')),
			array('config.add', array('r2t_pm_template_urls', '1')),
			array('config.add', array('r2t_pm_title', 'PM report: {TITLE}')),
			array('config.add', array('r2t_post_template', 'Title: {TITLE}
Reporter: {REPORTER_FULL}
Reported post: {REPORT_POST}
Reported for: {REPORT_REASON}
Notes: {REPORT_TEXT}
Link to report in MCP: {REPORT_LINK}')),
			array('config.add', array('r2t_post_template_bbcode', '1')),
			array('config.add', array('r2t_post_template_sig', '1')),
			array('config.add', array('r2t_post_template_smilies', '1')),
			array('config.add', array('r2t_post_template_urls', '1')),
			array('config.add', array('r2t_post_title', 'Topic/Post report: {TITLE}')),
			array('config.add', array('r2t_report_id', '0')),
			array('config.add', array('version_reporttotopic', '1.0.0')),

			// Add the ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REPORTTOTOPIC')),

			array('module.add', array(
				'acp', 'ACP_REPORTTOTOPIC', array(
					'module_basename'	=> '\david63\reporttotopic\acp\reporttotopic_module',
					'modes'				=> array('manage'),
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'forums'	=> array(
					'r2t_report_forum'	=> array('MTEXT_UNI', ''),
				),

				$this->table_prefix . 'reports'	=> array(
					'r2t_report_topic'	=> array('INT:8', 0),
				),
			),
		);
	}

	/**
	* Drop the columns schema from the tables
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'forums'	=> array(
					'r2t_report_forum',
				),

				$this->table_prefix . 'reports'	=> array(
					'r2t_report_topic',
				),
			),
		);
	}
}
