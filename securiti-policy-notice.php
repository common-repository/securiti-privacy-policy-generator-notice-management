<?php

/*
Plugin Name:       Privacy Policy Generator & Notice Management | Securiti
Plugin URI:        https://securiti.ai/privaci/privacy-notice-management/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin
Description:       Generate GDPR & CCPA Compliant Privacy Policies and Notices onto your website in minutes. Update legal pages for blogs, ecommerce and marketing websites.
Version:           1.0.0
Author:            Securiti
Author URI:        https://securiti.ai/?utm_source=wordpress&utm_medium=referral&utm_campaign=plugin
License:           GPL-3
License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
Text Domain:       securiti-policy-notice
Domain Path:       /languages/
*/

/*
 * Init plugin
 */

namespace securiti;

require_once('src/blocks.php');
$block = new Blocks('securiti-policy-notice');
