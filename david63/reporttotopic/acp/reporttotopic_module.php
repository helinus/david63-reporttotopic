<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\reporttotopic\acp;

class reporttotopic_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $user;

		$this->tpl_name		= 'report_to_topic';
		$this->page_title	= $user->lang('ACP_REPORTTOTOPIC');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('david63.reporttotopic.admin.controller');

		$admin_controller->display_options();
	}
}
