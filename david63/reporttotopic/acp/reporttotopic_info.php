<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\reporttotopic\acp;

class reporttotopic_info
{
	function module()
	{
		return array(
			'filename'	=> '\david63\reporttotopic\acp\reporttotopic_module',
			'title'		=> 'ACP_REPORTTOTOPIC_CONFIG',
			'modes'		=> array(
				'manage'	=> array('title' => 'ACP_REPORTTOTOPIC_CONFIG', 'auth' => 'ext_david63/reporttotopic && acl_a_board', 'cat' => array('ACP_REPORTTOTOPIC')),
			),
		);
	}
}
