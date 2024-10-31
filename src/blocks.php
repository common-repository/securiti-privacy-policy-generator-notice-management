<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://securiti.ai
 * @since      1.0.0
 *
 * @package    securiti
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    securiti
 * @author     SECURITI.ai <wordpress-support@securiti.ai>
 */

namespace securiti;

class Blocks {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private $slug;
	private $folder;
	private $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct($slug) {
		$this->version = '1.0.0';
		$this->slug = $slug;
		$this->folder = pathinfo(dirname(__DIR__), PATHINFO_BASENAME);

		//Translate Plugin
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));

		//register settings
		add_action('admin_init', array($this, 'register_setting'));

		//register styles
		add_action('enqueue_block_assets', array($this, 'enqueue_block_assets'));

		//register editor assets
		add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));

		//admin notices
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		add_action('admin_notices', array($this, 'securiti_admin_notices'));
		add_action('wp_ajax_securiti_hide_review_notice', array($this, 'securiti_ajax_hide_review_notice'));
		add_action('wp_ajax_securiti_later_review_notice', array($this, 'securiti_ajax_later_review_notice'));
	}

	/**
	 * Admin styles
	 *
	 * @since  1.0.0
	 */
	function enqueue_block_assets() {
		wp_enqueue_style(
			$this->slug . '-style',
			plugins_url('dist/blocks.style.build.css', __DIR__),
			array('wp-editor')
		);
	}

	/**
	 * Admin scripts
	 *
	 * @since  1.0.0
	 */
	function enqueue_admin_scripts() {
		wp_enqueue_script(
			$this->slug . '-admin-js',
			plugins_url('dist/admin.js', __DIR__),
			array('jquery'),
			false
		);

		// Localize admin script
		wp_localize_script($this->slug . '-admin-js', 'securiti_policy_notice_admin_js', array(
			'nonce' => wp_create_nonce('securiti_policy_notice_admin_js')
		));
	}

	/**
	 * Editor access
	 *
	 * @since  1.0.0
	 */
	function enqueue_block_editor_assets() {
		wp_enqueue_script(
			$this->slug,
			plugins_url('dist/blocks.build.js', __DIR__),
			array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor')
		);

		wp_enqueue_style(
			$this->slug . '-editor',
			plugins_url('dist/blocks.editor.build.css', __DIR__),
			array('wp-edit-blocks')
		);

		wp_set_script_translations(
			$this->slug,
			$this->slug,
			plugin_dir_path(__DIR__) . 'languages'
		);
	}

	/**
	 * Load Language
	 *
	 * @since  1.0.0
	 */
	function load_plugin_textdomain() {
		load_plugin_textdomain(
			$this->slug,
			false,
			$this->folder . '/languages'
		);
	}

	/**
	 * Register settings for plugin
	 *
	 * @since  1.0.0
	 */
	function register_setting() {
		// Setup default Cookie installation date
		$securiti_install_date = ($exists = get_option('securiti_install_date')) ? $exists : '';

		if (empty($securiti_install_date)) {
			update_option('securiti_install_date', date('Y-m-d H:i:s'));
		}
	}

	/**
	 * Admin notice
	 *
	 * @since  1.0.0
	 */
	function securiti_admin_notices() {
		// Bail if current user is not a site administrator
		if (!current_user_can('update_plugins')) {
			return;
		}

		// Check if user checked already hide the review notice
		$hide_review_notice = ($exists = get_option('securiti_hide_review_notice')) ? $exists : '';


		if ($hide_review_notice !== 'yes') {

			// Get the securiti installation date
			$securiti_notice_update_date = ($exists = get_option('securiti_notice_update_date')) ? $exists : date('Y-m-d H:i:s');

			$now = date('Y-m-d h:i:s');
			$datetime1 = new \DateTime($securiti_notice_update_date);
			$datetime2 = new \DateTime($now);


			// Difference in days between installation date and now
			$diff_interval = round(($datetime2->format('U') - $datetime1->format('U')) / (60 * 60 * 24));

			if ($diff_interval >= 5 || $diff_interval < 0) { ?>

				<div class="notice securiti-review-notice">
					<p>
						<?php _e('Hello!', 'securiti-policy-notice'); ?><br>
						<?php _e('We are very pleased that by now you have been using the <strong>Privacy Policy Generator & Notice Management | Securiti</strong> plugin for afew days. Please rate the plugin. It will help us a lot.', 'securiti-policy-notice'); ?><br>
					</p>
					<ul style="display: flex; align-items: center;">
						<li style="margin-right: 10px;"><a href="https://wordpress.org/support/plugin/securiti-privacy-policy-generator-notice-management/reviews/?rate=5#new-post" class="button button-primary" target="_blank" title="<?php _e('Rate the plugin', 'securiti-policy-notice'); ?>"><?php _e('Rate the plugin', 'securiti-policy-notice'); ?></a></li>
						<li style="margin-right: 10px;"><a href="javascript:void(0);" class="securiti-later-review-notice button" title="<?php _e('Remind later', 'securiti-policy-notice'); ?>"><?php _e('Remind later', 'securiti-policy-notice'); ?></a></li>
						<li><a href="javascript:void(0);" class="securiti-hide-review-notice" title="<?php _e('Don’t show again', 'securiti-policy-notice'); ?>"><small><?php _e('Don’t show again', 'securiti-policy-notice'); ?></small></a></li>
					</ul>
					<p>
						<?php _e('Thank you,', 'securiti-policy-notice'); ?><br>
						<?php _e('Team Securiti', 'securiti-policy-notice'); ?><br>
					</p>
				</div>

			<?php
			}
		}
	}


	/**
	 * Ajax handler for hide review notice action
	 *
	 * @since  1.0.0
	 */
	function securiti_ajax_hide_review_notice() {
		// Security check, forces to die if not security passed
		check_ajax_referer('securiti_policy_notice_admin_js', 'nonce');

		update_option('securiti_hide_review_notice', 'yes');

		wp_send_json_success(array('success'));
		exit;
	}


	/**
	 * Ajax handler for hide review notice action
	 *
	 * @since  1.0.0
	 */
	function securiti_ajax_later_review_notice() {
		// Security check, forces to die if not security passed
		check_ajax_referer('securiti_policy_notice_admin_js', 'nonce');

		update_option('securiti_notice_update_date', date('Y-m-d H:i:s'));

		wp_send_json_success(array('success'));
		exit;
	}
}
