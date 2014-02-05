<?php
# MantisBT - a php based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @author InitOS GmbH & Co.KG
 * @author Paul Götze <paul.goetze@initos.com>
 */

	/**
	 * MantisBT Core API's
	 */
	require_once( 'core.php' );
	/**
	 *  requires compress_api
	 */
	require_once( 'compress_api.php' );
	/**
	 * requires last visited api
	 */
	require_once( 'last_visited_api.php' );
	/**
	 * require dashboard print api
	 */
	require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "dashboard_print_api.php");
	

	auth_ensure_user_authenticated();
	$t_current_user_id = auth_get_current_user_id();

	# Improve performance by caching category data in one pass
	category_get_all_rows( helper_get_current_project() );
	compress_enable();

	# don't index my view page
	html_robots_noindex();

	html_page_top1( plugin_lang_get( 'menuname' ) );

	if ( current_user_get_pref( 'refresh_delay' ) > 0 ) {
		$t_redirect_path = 'plugin.php?page=Dashboard/dashboard';
		html_meta_redirect( $t_redirect_path, current_user_get_pref( 'refresh_delay' ) * 60, false);
	}
	
	
	#config_set('show_project_menu_bar', OFF );
	html_page_top2();

	print_recently_visited();	

	$f_page_number = gpc_get_int( 'page_number', 1 );

	$t_per_page = config_get( 'my_view_bug_count' );
	$t_bug_count = null;
	$t_page_count = null;
?>

<div id="dashboard-container" align="center">
<?php
	$t_status_legend_position = config_get( 'status_legend_position' );

	if ( $t_status_legend_position == STATUS_LEGEND_POSITION_TOP || $t_status_legend_position == STATUS_LEGEND_POSITION_BOTH ) {
		html_status_legend();
		echo '<br />';
	}
?>
<ul id="dashboard-sortable" class="cf" border="0" cellspacing="3" cellpadding="0">
<?php
	if(ON == plugin_config_get('allow_default_boxes_view')) {
		DashboardPrintAPI::print_positioned_default_boxes();
	} else if(ON == plugin_config_get('allow_custom_boxes_view')){
		if(!DashboardDbAPI::user_has_custom_boxes()) {
			DashboardDbAPI::create_initial_custom_boxes();	
		}
		
		DashboardPrintAPI::print_positioned_custom_boxes();
	}
?>
</ul>
<?php
	# print show box links
	DashboardPrintAPI::print_visibility_link_list();
	
	# print info dialog
	echo DashboardPrintAPI::get_info_dialog_html();
	echo DashboardPrintAPI::get_info_dialog_html("", "error-dialog", plugin_lang_get('error'));

	if ( $t_status_legend_position == STATUS_LEGEND_POSITION_BOTTOM || $t_status_legend_position == STATUS_LEGEND_POSITION_BOTH ) {
		html_status_legend();
	}
?>
</div>

<?php
	html_page_bottom();
