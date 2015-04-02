<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\reporttotopic\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $container;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\request\request				$request	Request object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\template\template			$template	Template object
	* @param \phpbb\user						$user		User object
	* @param ContainerInterface					$container	Service container interface
	*
	* @return \phpbb\boardrules\controller\admin_controller
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, ContainerInterface $container)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->db			= $db;
		$this->template		= $template;
		$this->user			= $user;
		$this->container	= $container;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Create a form key for preventing CSRF attacks
		$form_key = 'report2topic';
		add_form_key($form_key);

		// Submit

		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form is valid?
			if (!check_form_key($form_key))
			{
				trigger_error($this->user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Get the vars
			$dest_forum		= $this->request->variable('report2topic_post_forum', 0);
			$pm_dest_forum	= $this->request->variable('report2topic_pm_forum', 0);
			$pm_title		= $this->request->variable('report2topic_pm_title', '', true);
			$post_title		= $this->request->variable('report2topic_post_title', '', true);
			$pm_template	= $this->request->variable('report2topic_pm_template', '', true);
			$post_template	= $this->request->variable('report2topic_post_template', '', true);

			// Validate the forum IDs
			// If valid save the settings.
			$sql = 'SELECT forum_id
				FROM ' . FORUMS_TABLE . '
				WHERE ' . $this->db->sql_in_set('forum_id', array($dest_forum, $pm_dest_forum));
			$result	= $this->db->sql_query($sql);
			while ($forum = $this->db->sql_fetchrow($result))
			{
				if ($forum['forum_id'] == $dest_forum)
				{
					$this->config->set('r2t_dest_forum', $dest_forum);
				}

				if ($forum['forum_id'] == $pm_dest_forum)
				{
					$this->config->set('r2t_pm_dest_forum', $pm_dest_forum);
				}
			}
			$this->db->sql_freeresult($result);

			// Save topic title
			$this->config->set('r2t_pm_title', $pm_title);
			$this->config->set('r2t_post_title', $post_title);

			// Save the templates
			$this->config->set('r2t_pm_template', $pm_template);
			$this->config->set('r2t_post_template', $post_template);

			// Add option settings change action to the admin log
			$phpbb_log = $this->container->get('log');
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'REPORTTOTOPIC_LOG');
			trigger_error($this->user->lang('ACP_REPORTTOTOPIC_CONFIG_SUCCESS') . adm_back_link($this->u_action));
		}

		$dest_forum_id		= (isset($this->config['r2t_dest_forum'])) ? $this->config['r2t_dest_forum'] : 0;
		$pm_dest_forum_id	= (isset($this->config['r2t_pm_dest_forum'])) ? $this->config['r2t_pm_dest_forum'] : 0;

		// Output the page
		$this->template->assign_vars(array(
			'S_DEST_OPTIONS'					=> make_forum_select($dest_forum_id, false, true, true),
			'S_PM_DEST_OPTIONS'					=> make_forum_select($pm_dest_forum_id, false, true, true),
			'S_PM_TEMPLATE'						=> isset($this->config['r2t_pm_template']) ? $this->config['r2t_pm_template'] : '',
			'S_PM_TITLE'						=> isset($this->config['r2t_pm_title']) ? $this->config['r2t_pm_title'] : '',
			'S_POST_TEMPLATE'					=> isset($this->config['r2t_post_template']) ? $this->config['r2t_post_template'] : '',
			'S_PM_TEMPLATE_BBCODE_CHECKED'		=> isset($this->config['r2t_pm_template_bbcode']) ? $this->config['r2t_pm_template_bbcode'] : false,
			'S_PM_TEMPLATE_SMILIES_CHECKED'		=> isset($this->config['r2t_pm_template_smilies']) ? $this->config['r2t_pm_template_smilies'] : false,
			'S_PM_TEMPLATE_URLS_CHECKED'		=> isset($this->config['r2t_pm_template_urls']) ? $this->config['r2t_pm_template_urls'] : false,
			'S_PM_TEMPLATE_SIG_CHECKED'			=> isset($this->config['r2t_pm_template_sig']) ? $this->config['r2t_pm_template_sig'] : false,
			'S_POST_TEMPLATE_BBCODE_CHECKED'	=> isset($this->config['r2t_post_template_bbcode']) ? $this->config['r2t_post_template_bbcode'] : false,
			'S_POST_TEMPLATE_SMILIES_CHECKED'	=> isset($this->config['r2t_post_template_smilies']) ? $this->config['r2t_post_template_smilies'] : false,
			'S_POST_TEMPLATE_URLS_CHECKED'		=> isset($this->config['r2t_post_template_urls']) ? $this->config['r2t_post_template_urls'] : false,
			'S_POST_TEMPLATE_SIG_CHECKED'		=> isset($this->config['r2t_post_template_sig']) ? $this->config['r2t_post_template_sig'] : false,
			'S_POST_TITLE'						=> isset($this->config['r2t_post_title']) ? $this->config['r2t_post_title'] : '',

			'U_ACTION'							=> $this->u_action,
		));

		// Add tokens
		$this->user->add_lang_ext('david63/reporttotopic', 'reporttotopic_common');
		foreach ($this->user->lang['r2t_tokens'] as $token => $explain)
		{
			$this->template->assign_block_vars('token', array(
				'TOKEN'		=> '{' . $token . '}',
				'EXPLAIN'	=> $explain,
			));
		}
	}

	protected function set_options()
	{
		$this->config->set('r2t_pm_template_bbcode', $this->request->variable('report2topic_pm_template_parse_bbcode', '0'));
		$this->config->set('r2t_pm_template_smilies', $this->request->variable('report2topic_pm_template_parse_smilies', '0'));
		$this->config->set('r2t_pm_template_urls', $this->request->variable('report2topic_pm_template_parse_urls', '0'));
		$this->config->set('r2t_pm_template_sig', $this->request->variable('report2topic_pm_template_parse_sig', '0'));
		$this->config->set('r2t_post_template_bbcode', $this->request->variable('report2topic_post_template_parse_bbcode', ''));
		$this->config->set('r2t_post_template_smilies', $this->request->variable('report2topic_post_template_parse_smilies', '0'));
		$this->config->set('r2t_post_template_urls', $this->request->variable('report2topic_post_template_parse_urls', '0'));
		$this->config->set('r2t_post_template_sig', $this->request->variable('report2topic_post_template_parse_sig', '0'));
	}
}
