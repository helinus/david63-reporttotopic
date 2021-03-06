<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\reporttotopic\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $php_ext;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/**
	* Constructor
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\user						$user		User object
	* @param \phpbb\request\request				$request	Request object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param string 							$root_path
	* @param string 							$php_ext
	* @param \phpbb\template\template			$template	Template object
	* @param ContainerInterface					$container	Service container interface
	* @param \phpbb\notification\manager		$notification_manager
	*
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, $root_path, $php_ext, \phpbb\template\template $template, $container, \phpbb\notification\manager $notification_manager)
// Fix Container +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	{
		$this->config				= $config;
		$this->user					= $user;
		$this->request				= $request;
		$this->db					= $db;
		$this->root_path			= $root_path;
		$this->php_ext				= $php_ext;
		$this->template				= $template;
		$this->container			= $container;
		$this->notification_manager = $notification_manager;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'								=> 'load_language_on_setup',
			'core.acp_manage_forums_validate_data'			=> 'acp_alter_forum_data',
			'core.acp_manage_forums_display_form'			=> 'acp_display_forum_form',
			'core.report_post_submit'						=> 'submit_report_post',
			'core.submit_post_end'							=> 'submit_post_end',
			'core.mcp_reports_close_after'					=> 'lock_report_topic',
			'core.viewtopic_modify_post_row'				=> 'hide_report_link',
			'core.viewforum_modify_topicrow'				=> 'hide_topic_icon',
			'core.notification_manager_add_notifications'	=> 'fix_notification',
			'core.report_post_test'	=> 'tester',
		);
	}

	public function tester($event)
	{
		$report_data = $event['report_data'];
		$row = $event['row'];
		$forum_data = $event['forum_data'];
		$report_text = $event['report_text'];
/****
		debug('report data', $report_data, false);
		debug('row', $row, false);
		debug('forum data', $forum_data, false);
		debug('report text', $report_text, true);
****/
	}

	public function fix_notification($event)
	{
		$notification_type_name	= $event['notification_type_name'];
		$data					= $event['data'];
		$notify_users			= $event['notify_users'];
		$options				= $event['options'];
		$item_id				= $event['item_id'];
/****
		debug('Type', $notification_type_name, false);
		debug('Data', $data, false);
		debug('Users', $notify_users, false);
		debug('Item', $item_id, false);
		debug('Options', $options, false);
****/
	}


	/**
	* Load common login redirect language files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= array(
			'ext_name' => 'david63/reporttotopic',
			'lang_set' => 'reporttotopic_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Adjust the forum data array so that the report2topic destination forum is saved correctly
	 *
	 * @param	array	$forum_data	The forum data array passed from the manage event
	 * @param	array	$errors		Array containing any encountered error messages
	 * @return	void
	 */
	public function acp_alter_forum_data($event)
	{
		$forum_data = $event['forum_data'];
		$errors		= $event['errors'];

		$dest_forum = $this->request->variable('report2topic_dest_forum', 0);

		// Make sure that this forum exists
		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . $dest_forum;

		$result	= $this->db->sql_query($sql);
		$fid	= $this->db->sql_fetchfield('forum_id', false, $result);

		$this->db->sql_freeresult($result);

		if ($dest_forum > 0 && !$fid)
		{
			$errors[] = $this->user->lang('FORUM_NOT_EXISTS', $dest_forum);
			return;
		}

		// Merge in the $forum_data array
		$forum_data = array_merge($forum_data, array(
			'r2t_report_forum'	=> $dest_forum,
		));

		$event->offsetSet('forum_data', $forum_data);
	}

	/**
	 * Modify the manage forums pages in the ACP by adding some additional template data
	 *
	 * @return	void
	 */
	public function acp_display_forum_form($event)
	{
		$fid = $this->request->variable('f', 0);

		// Is the forum that is being edited the current default report destination forum?
		$can_set_dest = ($fid == $this->config['r2t_dest_forum']) ? false : true;

		if ($can_set_dest)
		{
			// Need to grab some data from the db here
			$default_name	= '';
			$selected_id	= 0;

			$sql = 'SELECT forum_id, forum_name, r2t_report_forum
				FROM ' . FORUMS_TABLE . '
				WHERE ' . $this->db->sql_in_set('forum_id', array($fid, $this->config['r2t_dest_forum']));

			$result	= $this->db->sql_query($sql);

			while ($forum = $this->db->sql_fetchrow($result))
			{
				if ($forum['forum_id'] == $fid)
				{
					$selected_id = $forum['r2t_report_forum'];
				}
				else
				{
					$default_name = $forum['forum_name'];
				}
			}
			$this->db->sql_freeresult($result);

			// Assign our additional vars
			$this->template->assign_vars(array(
				'S_R2T_DEFAULT_DEST'			=> $default_name,	// Default forum as set in the ACP page
				'S_R2T_DEFAULT_DEST_OPTIONS'	=> make_forum_select($selected_id, false, true, true),
			));
		}

		$this->template->assign_var('S_CAN_SET_R2T_DEST_FORUM', $can_set_dest);
	}

	/**
	 * Hide the report message in viewtopic
	 *
	 * @return	void
	 */
	public function hide_report_link($event)
	{
		if ($this->config['r2t_hide_topic_link'])
		{
			$post_row = $event['post_row'];

			$post_row = array_merge($post_row, array(
				'S_POST_REPORTED' => false,
			));
			$event->offsetSet('post_row', $post_row);
		}
	}

	/**
	 * Hide the report icon in viewforum
	 *
	 * @return	void
	 */
	public function hide_topic_icon($event)
	{
		if ($this->config['r2t_hide_topic_link'])
		{
			$topic_row = $event['topic_row'];

			$topic_row = array_merge($topic_row, array(
				'S_TOPIC_REPORTED' => false,
			));
			$event->offsetSet('topic_row', $topic_row);
		}
	}

	/**
	 * @var array The post data array
	 */
	private $post_data = array(
		'topic_id'	=> 0,
		'forum_id'	=> 0,
		'icon_id'	=> false,

		// Defining Post Options
		'enable_bbcode'		=> true,
		'enable_smilies'	=> true,
		'enable_urls'       => true,
		'enable_sig'        => false,

		// Message Body
		'message'		=> '',
		'message_md5'	=> '',

		// Values from generate_text_for_storage()
		'bbcode_bitfield'	=> '',
		'bbcode_uid'		=> '',

		// Other Options
		'post_edit_locked'	=> 1,
		'topic_title'		=> '',

		// Email Notification Settings
		'notify_set'	=> false,
		'notify'		=> false,
		'post_time'		=> 0,
		'forum_name'	=> '',

		// Indexing
		'enable_indexing'		=> true,
		'force_approved_state'	=> true,
	);

	/**
	 * A new report is created, create the report topic
	 * @param	Integer	$pm_id		ID of the reported PM
	 * @param	Integer	$post_id	ID of the reported post
	 *
	 * @return	void
	 */
	public function submit_report_post($event)
	{
		$forum_data		= $event['forum_data'];
		$report_data	= $event['report_data'];
		$pm_id			= $event['pm_id'];
		$post_id		= $event['post_id'];
		$report_id		= $event['report_id'];

		// Some mode specific data
		if ($pm_id > 0)
		{
			$subject	= 'r2t_pm_title';
			$template	= 'r2t_pm_template';

			// Cannot use {REPORT_POST} here!
			unset($this->user->lang['r2t_tokens']['REPORT_POST']);

			// Destination forum
			$this->post_data['forum_id'] = $this->config['r2t_pm_dest_forum'];

			// Post options
			$this->post_data['enable_bbcode']	= ($this->config['r2t_pm_template_bbcode']) ? true : false;
			$this->post_data['enable_smilies']	= ($this->config['r2t_pm_template_smilies']) ? true : false;
			$this->post_data['enable_urls']		= ($this->config['r2t_pm_template_urls']) ? true : false;
			$this->post_data['enable_sig']		= ($this->config['r2t_pm_template_sig']) ? true : false;

			$this->build_topic('pm');
		}
		else if ($post_id > 0)
		{
			$subject			= 'r2t_post_title';
			$template			= 'r2t_post_template';
			$report_forum_id	= ($forum_data['r2t_report_forum'] > 0) ? $forum_data['r2t_report_forum'] : $this->config['r2t_dest_forum'];

			// Let's make sure that the destination forum exists
			$sql = 'SELECT forum_id
				FROM ' . FORUMS_TABLE . "
				WHERE forum_id = $report_forum_id";
			$result	= $this->db->sql_query($sql);

			if (!$row = $this->db->sql_fetchrow($result))
			{
				$phpbb_log = $this->container->get('log');
				$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'NO_FORUM_LOG');
				return;
			}
			$this->db->sql_freeresult($result);

			// Destination forum
			$this->post_data['forum_id'] = $report_forum_id;

			// Post options
			$this->post_data['enable_bbcode']	= ($this->config['r2t_post_template_bbcode']) ? true : false;
			$this->post_data['enable_smilies']	= ($this->config['r2t_post_template_smilies']) ? true : false;
			$this->post_data['enable_urls']		= ($this->config['r2t_post_template_urls']) ? true : false;
			$this->post_data['enable_sig']		= ($this->config['r2t_post_template_sig']) ? true : false;

			$this->build_topic('post');
		}

		// Fetch the report data
		$report_data = $this->get_report_data($pm_id, $post_id);

		// Prepare token replacements
		$replacing = $tokens = $tokens_replacement = array();
		$this->prepare_tokens($tokens_replacement, $report_data);

		foreach (array_keys($this->user->lang['r2t_tokens']) as $token)
		{
			$tokens[]		= '{' . $token . '}';
			$replacing[]	= $tokens_replacement[$token];
		}

		// Prepare the post
		$post = str_replace($tokens, $replacing, $this->config[$template]);
		$this->config->set('r2t_report_id', $report_id);

		// Get the message parser
		if (!class_exists('parse_message'))
		{
			include($this->root_path . 'includes/message_parser.' . $this->php_ext);
		}

		// Load the message parser
		$report_parser = new \parse_message($post);

		// Parse the post
		$report_parser->parse($this->post_data['enable_bbcode'], $this->post_data['enable_urls'], $this->post_data['enable_smilies']);

		// Set the message
		$this->post_data['bbcode_bitfield']	= $report_parser->bbcode_bitfield;
        $this->post_data['bbcode_uid']		= $report_parser->bbcode_uid;
		$this->post_data['message']			= $report_parser->message;
		$this->post_data['message_md5']		= md5($report_parser->message);
		$this->post_data['topic_title'] 	= censor_text(str_replace($tokens, $replacing, $this->config[$subject]));

		// Only here to not break "submit_post()"
		$poll_data = array();

		// And finally submit
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}
		submit_post('post', $this->post_data['topic_title'], '', POST_NORMAL, $poll_data, $this->post_data);

		// Add the notification
		$this->send_notification($report_data);
	}

	/**
	* Update the reports table with the topic ID
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function submit_post_end($event)
	{
		$data		= $event['data'];
		$topic_id	= $data['topic_id'];
		$report_id	= $this->config['r2t_report_id'];

		$sql = 'UPDATE ' . REPORTS_TABLE . "
			SET r2t_report_topic = $topic_id
			WHERE report_id = $report_id";
		$this->db->sql_query($sql);

		// Make sure the variable is empty for next report
		$this->config->set('r2t_report_id', '0');
	}

	public function build_topic($mode)
	{
		switch ($mode)
		{
			case 'post':
			break;

			case 'pm':
			break;

			case 'close':
			break;
		}
	}

	/**
	* Lock the post report topic when the report is closed
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function lock_report_topic($event)
	{
		if ($this->config['r2t_lock_topic'])
		{
			$report_id_list = $event['report_id_list'];

			foreach($report_id_list as $report_id)
			{
				// Get the topic id for the report and lock the topic
				$sql = 'SELECT r2t_report_topic
					FROM ' . REPORTS_TABLE . "
					WHERE report_id = $report_id";

				$result		= $this->db->sql_query($sql);
				$topic_id	= $this->db->sql_fetchfield('r2t_report_topic', false, $result);
				$this->db->sql_freeresult($result);

				if ($topic_id > 0)
				{
					$this->build_topic('close');

					$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_status = ' . ITEM_LOCKED . "
						WHERE topic_id = $topic_id";

					$this->db->sql_query($sql);
				}
			}
		}
	}

	/**
	 * Get the report data of the reported post or PM
	 * @param	Integer	$pm_id		ID of the reported PM
	 * @param	Integer	$post_id	ID of the reported post
	 *
	 * @return	Array	The report data
	 */
	private function get_report_data($pm_id = 0, $post_id = 0)
	{
		// The global query
		$sql_ary = array(
			'SELECT'	=> 'r.user_id, r.report_id, r.report_closed, r.report_time, r.report_text, rr.reason_title, rr.reason_description, u.username, u.username_clean, u.user_colour',
			'FROM'		=> array(
				REPORTS_TABLE			=> 'r',
				REPORTS_REASONS_TABLE	=> 'rr',
				USERS_TABLE				=> 'u',
			),
			'ORDER_BY'	=> 'report_closed ASC',
		);

		// Type specific
		if ($post_id > 0)
		{
			$sql_ary['SELECT']	.= ', p.post_subject, r.post_id';
			$sql_ary['FROM']	+= array(POSTS_TABLE => 'p');
			$sql_ary['WHERE']	= "r.post_id = {$post_id}
				AND rr.reason_id	= r.reason_id
				AND r.user_id		= u.user_id
				AND r.pm_id			= 0
				AND p.post_id		= r.post_id";
		}
		else
		{
			$sql_ary['SELECT']	.= ', pm.message_subject, r.pm_id';
			$sql_ary['FROM']	+= array(PRIVMSGS_TABLE => 'pm');
			$sql_ary['WHERE']	= "r.pm_id = {$pm_id}
				AND rr.reason_id	= r.reason_id
				AND r.user_id		= u.user_id
				AND r.post_id		= 0
				AND pm.msg_id		= r.pm_id";
		}

		// Build and run the query
		$sql	= $this->db->sql_build_query('SELECT', $sql_ary);
		$result	= $this->db->sql_query($sql);
		$report	= $this->db->sql_fetchrow($result);

		$this->db->sql_freeresult($result);

		return $report;
	}

	/**
	 * Create an array containing all data that might be used in the report post. The tokens will be replaced later on
	 *
	 * @param	Array	$tokens	An array that will be filled with the token replacements for this report
	 * @param	Array	$report	An array containing the report data
	 * @return	void
	 */
	public function prepare_tokens(&$tokens, $report)
	{
		if (!function_exists('get_username_string'))
		{
			require $this->root_path . 'includes/functions_content.' . $this->php_ext;
		}

		// Build the data
		$reporter		= get_username_string('username', $report['user_id'], $report['username'], $report['user_colour']);
		$reporter_full	= get_username_string('full', $report['user_id'], $report['username'], $report['user_colour']);
		$report_reason	= censor_text($report['reason_title']);
		$report_text	= censor_text($report['report_text']);
		$report_time	= $this->user->format_date($report['report_time']);
		$title			= (isset($report['post_id'])) ? censor_text($report['post_subject']) : censor_text($report['message_subject']);

		$report_link_params = array(
			'i'		=> (isset($report['post_id'])) ? 'reports' : 'pm_reports',
			'mode'	=> (isset($report['post_id'])) ? 'report_details' : 'pm_report_details',
			'r'		=> $report['report_id'],
		);
		$report_link = append_sid(generate_board_url() . '/mcp.' . $this->php_ext, $report_link_params);

		// Fill the array
		$tokens = array(
			'REPORTER'		=> $reporter,
			'REPORTER_FULL'	=> $reporter_full,
			'REPORT_LINK'	=> $report_link,
			'REPORT_REASON'	=> $report_reason,
			'REPORT_TEXT'	=> $report_text,
			'REPORT_TIME'	=> $report_time,
			'TITLE'			=> $title,
		);

		if (isset($report['post_id']))
		{
			$report_post_link_params = array(
				'p'	=> $report['post_id'],
				'#'	=> 'p' . $report['post_id'],
			);
			$report_post_link = append_sid(generate_board_url() . '/viewtopic.' . $this->php_ext, $report_post_link_params);

			$tokens['REPORT_POST'] = $report_post_link;
		}
	}

	/**
	* Send notification to users
	*
	* @param int $topic_id The topic identifier
	* @return null
	* @access public
	*/
	public function send_notification($report_data)
	{
		// Increment our notifications sent counter
		$this->config->increment('reporttotopic_notification', 1);

		// Store the notification data we will use in an array
		$notification_data = array_merge($report_data, array(
			'report_id'			=> $report_data['report_id'],
			'notification_id'	=> $this->config['reporttotopic_notification'],
		));

		// Create the notification
		$phpbb_notifications = $this->container->get('notification_manager');
		$phpbb_notifications->add_notifications('david63.reporttotopic.notification.type.reporttotopic', $notification_data);
	}
}
