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

require_once( 'core.php' );
/**
 * requires current_user_api
 */
require_once( 'current_user_api.php' );
/**
 * requires bug_api
 */
 require_once( 'bug_api.php' );
/**
 * requires string_api
 */
require_once( 'string_api.php' );
/**
 * requires date_api
 */
require_once( 'date_api.php' );
/**
 * requires icon_api
 */
require_once( 'icon_api.php' );
/**
 * requires helper_api
 */
require_once( 'helper_api.php' );
/**
 * require plugin_api
 */
require_once( 'plugin_api.php' );
/**
 * require filter_api
 */
require_once( 'filter_api.php' );


/**
 * Provides functions as API for printing dashboard items.
 */
class DashboardPrintAPI
{
	/**
	 * private variables 
	 * used for performance reasons while getting filter and urls
	 */
	private static $v_filter = array();
	private static $v_url_link_parameters = array();	
	
	/**
	 * Constants for icon links
	 */
	 const ICON_LINK_DRAG = "drag";
	 const ICON_LINK_HIDE = "hide";
	 const ICON_LINK_SHOW = "show";
	 const ICON_LINK_EDIT = "edit";
	 const ICON_LINK_DELETE = "delete";
	 const ICON_LINK_PLUS = "add";
	 
	 const ICON_SHOW = "show.png";
	 const ICON_HIDE = "hide.png";
	 const ICON_DRAG = "drag.png";
	 const ICON_EDIT = "edit.png";
	 const ICON_DELETE = "delete.png";	 
	 const ICON_ADD = "plus.png";
	
	/**
	 * Gets the url links parameters for a current user
	 * 
	 * @return array url link parameters for all bug types 
	 */
	static function get_url_link_parameters()
	{
		if(count(self::$v_url_link_parameters) > 0) {
			return self::$v_url_link_parameters;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_bug_resolved_status_threshold = config_get('bug_resolved_status_threshold');
		$t_hide_status_default = config_get('hide_status_default');
		$t_bug_feedback_status = config_get('bug_feedback_status');
		$url_link_parameters = array();
		
		$url_link_parameters['assigned'] = 		FILTER_PROPERTY_HANDLER_ID . '=' . $t_current_user_id . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_bug_resolved_status_threshold;
												
		$url_link_parameters['recent_mod'] = 	FILTER_PROPERTY_HIDE_STATUS_ID . '=none';
		
		$url_link_parameters['reported'] = 		FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
												
		$url_link_parameters['resolved'] = 		FILTER_PROPERTY_STATUS_ID . '=' . $t_bug_resolved_status_threshold . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_bug_resolved_status_threshold;
												
		$url_link_parameters['unassigned'] = 	FILTER_PROPERTY_HANDLER_ID . '=[none]' . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
		
		$url_link_parameters['monitored'] = 	FILTER_PROPERTY_MONITOR_USER_ID . '=' . $t_current_user_id . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
												
		$url_link_parameters['feedback'] = 		FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . 
												FILTER_PROPERTY_STATUS_ID . '=' . $t_bug_feedback_status . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
		
		$url_link_parameters['verify'] = 		FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . 
												FILTER_PROPERTY_STATUS_ID . '=' . $t_bug_resolved_status_threshold;
												
		$url_link_parameters['my_comments'] = 	FILTER_PROPERTY_NOTE_USER_ID. '=' . META_FILTER_MYSELF . '&' . 
												FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
												
		return $url_link_parameters;
	}
	
	/**
	 * Gets standard filter for dashboard boxes.
	 * 
	 * @return array default filter for all boxes in dashboard
	 */
	static function get_default_filter()
	{
		if(count(self::$v_filter) > 0) {
			return self::$v_filter;
		}
		
		$t_bug_resolved_status_threshold = config_get( 'bug_resolved_status_threshold' );
		$t_default_show_changed = config_get( 'default_show_changed' );		
		$t_bug_feedback_status = config_get('bug_feedback_status');
		$t_current_user_id = auth_get_current_user_id();
		
		$c_filter = array();
		
		#assigned projects filter 
		$c_filter['assigned'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => $t_current_user_id,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_bug_resolved_status_threshold,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['recent_mod'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => META_FILTER_NONE,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['reported'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => $t_current_user_id,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SORT_FIELD_NAME => 'last_updated',
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['resolved'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => $t_bug_resolved_status_threshold,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['unassigned'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_NONE,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		# TODO: check. handler value looks wrong
		
		$c_filter['monitored'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => $t_current_user_id,
			),
		);
		
		$c_filter['feedback'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => $t_bug_feedback_status,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => $t_current_user_id,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['verify'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => $t_bug_resolved_status_threshold,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => $t_current_user_id,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
		);
		
		$c_filter['my_comments'] = array(
			FILTER_PROPERTY_CATEGORY => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_SEVERITY_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_STATUS_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
			FILTER_PROPERTY_REPORTER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HANDLER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_RESOLUTION_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_BUILD => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_PRODUCT_VERSION => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_HIDE_STATUS_ID => Array(
				'0' => $t_hide_status_default,
			),
			FILTER_PROPERTY_MONITOR_USER_ID => Array(
				'0' => META_FILTER_ANY,
			),
			FILTER_PROPERTY_NOTE_USER_ID=> Array(
				'0' => META_FILTER_MYSELF,
			),
		);
		
		self::$v_filter = $c_filter;
		return $c_filter;
	}
	
	/**
	 * Gets the filter for the box with given name.
	 * 
	 * @param $p_box_title Title/name of the box
	 * @return array
	 */
	static function get_box_filter($p_box_title)
	{
		$t_filter = self::get_default_filter();
		return $t_filter[$p_box_title];
	}
	
	/**
	 * print filter select box
	 * @param $p_box_title
	 * @param $p_box_id
	 */
	static function print_filter_select_box($p_box_title)
	{
		# project selector hidden if only one project visible to user
		$t_show_project_selector = true;
		$t_project_ids = current_user_get_accessible_projects();
		$t_box_id = $GLOBALS['g_my_view_boxes'][$p_box_title];
		
		$t_javascript_off = (OFF == config_get('use_javascript'));
		
		if(count($t_project_ids) == 1){
			$t_project_id = (int) $t_project_ids[0];
			
			if(count(current_user_get_accessible_subprojects($t_project_id)) == 0){
				$t_show_project_selector = false;
			}
		}
	
		#action path for setting project filter
		$t_action_path = "";
		
		#set action path if using JavaScript
		if($t_javascript_off){ # production: uncomment!
			$t_action_path = plugin_page('dashboard_set_box_project_filter.php');
		}
		
		if($t_show_project_selector){
			echo '<form method="post" name="form_set_project_'. $t_box_id .'" action="' . $t_action_path . '">';
			# CSRF protection not required here - form does not result in modifications
	
			echo lang_get('email_project'), ': ';
			if( ON == config_get('show_extended_project_browser')){
				print_extended_project_browser( helper_get_current_project_trace() );
			} else {
				
				echo '<select name="project_id" class="small">';		
				$current_project_id = DashboardDbAPI::get_box_filter_project($t_box_id);
				print_project_option_list( join(';', self::get_current_project_trace($t_box_id)), true, null, true);
				echo '</select> ';
			}
			
			#hidden input for posting current box id to set filter for a certain box 
			echo '<input type="hidden" name="box_id" value="'. $t_box_id .'">';
			
			if($t_javascript_off){	
				#submit -- [not nescessary with JavaScript usage]
				echo '<input type="submit" class="button-small button-dashboard" value="' . lang_get('switch') . '" />';
			}
			echo '</form>';
		} else {
			# User has only one project, set it as both current and default
			if( ALL_PROJECTS == helper_get_current_project()) {
				helper_set_current_project($t_project_id);
				current_user_set_default_project($t_project_id);
				# Force reload of current page
				$t_redirect_url = str_replace(config_get('short_path'), '', $_SERVER['REQUEST_URI']);
				html_meta_redirect( $t_redirect_url, 0, false );
			}
		}
	}

/**
	 * Return the current project id as stored in the data base, in an Array
	 * If the current project is a subproject, the return value will include
	 * any parent projects
	 * @return array
	 */
	static function get_current_project_trace($p_box_id) {
		$t_bottom = DashboardDbAPI::get_box_filter_project($p_box_id);
		
		$t_parent = $t_bottom;
		$t_project_id = Array(
			$t_bottom,
		);
		
		echo $t_bottom;
	
		while( true ) {
			$t_parent = project_hierarchy_get_parent( $t_parent );
			if( 0 == $t_parent ) {
				break;
			}
			array_unshift($t_project_id, $t_parent);
		}
	
		if( !project_exists( $t_bottom ) || ( 0 == project_get_field( $t_bottom, 'enabled' ) ) || !access_has_project_level( VIEWER, $t_bottom ) ) {
			$t_project_id = Array(
				ALL_PROJECTS,
			);
		}
	
		#echo "project_id: ", $t_project_id;
		return $t_project_id;
	}

	/**
	 * Prints the projects html for given filtered rows.
	 * 
	 * @param array $rows Filtered rows by project and boxname
	 */
	static function print_projects($p_rows, $p_filter)
	{
		$t_update_bug_threshold = config_get( 'update_bug_threshold' );
		$t_icon_path = config_get( 'icon_path' );
		
		# -- Loop over bug rows and create $v_* variables --
		$t_count = count( $p_rows );
		for( $i = 0;$i < $t_count; $i++ ) {
			$t_bug = $p_rows[$i];
		
			$t_summary = string_display_line_links( $t_bug->summary );
			$t_last_updated = date( config_get( 'normal_date_format' ), $t_bug->last_updated );
		
			# choose color based on status
			$status_color = get_status_color( $t_bug->status, auth_get_current_user_id(), $t_bug->project_id );
		
			# Check for attachments
			$t_attachment_count = 0;
			# TODO: factor in the allow_view_own_attachments configuration option
			# instead of just using a global check.
			if(( file_can_view_bug_attachments( $t_bug->id, null ) ) ) {
				$t_attachment_count = file_bug_attachment_count( $t_bug->id );
			}
		
		# grab the project name
		$project_name = project_get_field( $t_bug->project_id, 'name' );
		?>
		<tr bgcolor="<?php echo $status_color?>">
			<?php
			# -- Bug ID and details link + Pencil shortcut --?>
			<td class="center" valign="top" width ="0" nowrap="nowrap">
				<span class="small">
				<?php
					print_bug_link( $t_bug->id );
		
			echo '<br />';
		
			if( !bug_is_readonly( $t_bug->id ) && access_has_bug_level( $t_update_bug_threshold, $t_bug->id ) ) {
				echo '<a href="' . string_get_bug_update_url( $t_bug->id ) . '"><img border="0" src="' . $t_icon_path . 'update.png' . '" alt="' . lang_get( 'update_bug_button' ) . '" /></a>';
			}
		
			if( ON == config_get( 'show_priority_text' ) ) {
				print_formatted_priority_string( $t_bug );
			} else {
				print_status_icon( $t_bug->priority );
			}
		
			if ( $t_attachment_count > 0 ) {
				$t_href = string_get_bug_view_url( $t_bug->id ) . '#attachments';
				$t_href_title = sprintf( lang_get( 'view_attachments_for_issue' ), $t_attachment_count, $t_bug->id );
				$t_alt_text = $t_attachment_count . lang_get( 'word_separator' ) . lang_get( 'attachments' );
				echo "<a href=\"$t_href\" title=\"$t_href_title\"><img src=\"${t_icon_path}attachment.png\" alt=\"$t_alt_text\" title=\"$t_alt_text\" /></a>";
			}
		
			if( VS_PRIVATE == $t_bug->view_state ) {
				echo '<img src="' . $t_icon_path . 'protected.gif" width="8" height="15" alt="' . lang_get( 'private' ) . '" />';
			}
			?>
				</span>
			</td>
		
			<?php
			# -- Summary --?>
			<td class="left" valign="top" width="100%">
				<span class="small">
				<?php
				 	if( ON == config_get( 'show_bug_project_links' ) && helper_get_current_project() != $t_bug->project_id ) {
						echo '[', string_display_line( project_get_name( $t_bug->project_id ) ), '] ';
					}
					echo $t_summary;
			?>
				<br />
				<?php
			# type project name if viewing 'all projects' or bug is in subproject
			echo string_display_line( category_full_name( $t_bug->category_id, true, $t_bug->project_id ) );
		
			if( $t_bug->last_updated > strtotime( '-' . $p_filter[FILTER_PROPERTY_HIGHLIGHT_CHANGED] . ' hours' ) ) {
				echo ' - <b>' . $t_last_updated . '</b>';
			} else {
				echo ' - ' . $t_last_updated;
			}
			?>
				</span>
			</td>
		</tr>
		<?php
			// -- end of Repeating bug row --
		}
	}
	
	/**
	 * Prints the default boxes in their current position.
	 */
	static function print_positioned_default_boxes()
	{
		$t_boxes = DashboardDbAPI::get_positioned_default_boxes_data();
		
		# print boxes
		while (list ($t_box_title, $t_box_id) = each ($t_boxes)) {
			$t_hidden_string = "";
			if($t_box_id != 0) {
				if(!DashboardDbAPI::get_box_visibility($t_box_id)) {
					$t_hidden_string = "style='display: none;'";
				}
				
				echo "<li id='dashboard-list-item-". $t_box_id ."' " . $t_hidden_string . " class='cf'>";
				self::print_box($t_box_title);
				echo "</li>";
			}
		}
	}
	
	/**
	 * Prints the full dashboard box filtered by current project id.
	 * 
	 * @param $p_box_title Name of ox to print
	 */
	static function print_box($p_box_title, $p_hidden = False)
	{		
		$t_box_id = $GLOBALS['g_my_view_boxes'][$p_box_title];
		$t_filter_project_id = DashboardDbAPI::get_box_filter_project($t_box_id, true);
		
		$t_filter = filter_get_default();
		$t_sort = $t_filter['sort'];
		$t_dir = $t_filter['dir'];
		
		$t_current_user_id = auth_get_current_user_id();
		$f_page_number		= gpc_get_int( 'page_number', 1 );
		$t_per_page = config_get( 'my_view_bug_count' );
		$t_bug_count = null;
		$t_page_count = null;
		
		$url_link_parameters = self::get_url_link_parameters();
		$c_filter = self::get_default_filter();		
		$t_box_filter = self::get_box_filter($p_box_title);
		$t_box_title = lang_get( 'my_view_title_' . $p_box_title );
		
		#get rows
		
		$rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $t_box_filter, $t_filter_project_id, $t_current_user_id);
		
		# Improve performance by caching category data in one pass
		if( helper_get_current_project() == 0 ) {
			$t_categories = array();
			foreach( $rows as $t_row ) {
				$t_categories[] = $t_row->category_id;
			}
		
			category_cache_array_rows( array_unique( $t_categories ) );
		}
		
		$t_filter = array_merge($t_box_filter, $t_filter);
		
		# -- ====================== BUG LIST ========================= --
		?>
		<table id="dashboard-box-<?php echo $t_box_id; ?>" class="width100" cellspacing="1">
		<?php
		# -- Navigation header row --
		?>
		<tr>
		<?php
		# -- Viewing range info --?>
			<td class="form-title" colspan="2">
		<?php
		print_link( html_entity_decode( config_get( 'bug_count_hyperlink_prefix' ) ).'&' . $url_link_parameters[$p_box_title], $t_box_title, false, 'subtle' );
		echo '&#160;';
		print_bracket_link( html_entity_decode( config_get( 'bug_count_hyperlink_prefix' ) ).'&' . $url_link_parameters[$p_box_title], '^', true, 'subtle' );
		
		#print select box
		self::print_filter_select_box($p_box_title);
		
		if( count( $rows ) > 0 ) {
			$v_start = $t_filter[FILTER_PROPERTY_ISSUES_PER_PAGE] * ( $f_page_number - 1 ) + 1;
			$v_end = $v_start + count( $rows ) - 1;
		}
		else {
			$v_start = 0;
			$v_end = 0;
		}
		echo "<span id='description-box-" . $t_box_id . "'>($v_start - $v_end / $t_bug_count)</span>";
		
		//print hide icon 
		self::print_icon_link(self::ICON_LINK_DRAG, $t_box_id);
		self::print_icon_link(self::ICON_LINK_HIDE, $t_box_id);
		
		?>
			</td>
		</tr>
		<?php
		#print project table rows
		self::print_projects($rows, $t_filter);
		?>
		</table>
			
		<?php
		
		// Free the memory allocated for the rows in this box since it is not longer needed.
		unset($rows);
	}
	
	/**
	 * Prints all custom boxes attached to the current user. 
	 */
	static function print_positioned_custom_boxes()
	{
		echo self::get_positioned_custom_boxes_html();
	}
	
	/**
	 * Gets the html for all custom boxes attached to the current user. 
	 */
	static function get_positioned_custom_boxes_html()
	{
		$t_html = "";
		$t_boxes = DashboardDbAPI::get_positioned_custom_boxes_data();
		
		foreach ($t_boxes as $t_key => $t_value) {
			$t_hidden_string = "";
			$t_box = DashboardDbAPI::get_custom_box_data($t_value);
			
			if(((int) $t_box['visible']) === 0) {
				$t_hidden_string = "style='display: none;'";
			}

			$t_html .= "<li id='dashboard-list-item-". $t_box['id'] ."' " . $t_hidden_string . ">"
					. self::get_custom_box_html($t_box)
					. '</li>';
		}
		
		return $t_html;
	}
	
	/**
	 * Creates and returns the complete html string for a custom box data set.
	 * 
	 * @param $p_box
	 * @return String
	 */
	static function get_custom_box_html($p_box)
	{
		$t_html = "";
		$t_filter_id = $p_box['filter_id'];
		$t_box_title = $p_box['title'];
		$t_visible = $p_box['visible'];
		$t_box_id = $p_box['id'];
		
		$f_page_number = gpc_get_int( 'page_number', 1 );
		$t_per_page = config_get('my_view_bug_count');
		$t_page_count = null;
		$t_bug_count = null;
		
		$t_rows = array();
		
		# using filter_cache_row() without raising an error
		$t_filter = filter_cache_row($t_filter_id, false);		
		$t_filter = filter_ensure_valid_filter($t_filter);
		
		# get filter string
		$t_filter_setting_arr = explode('#', $t_filter['filter_string'], 2);
		$t_unserialized_filter = unserialize($t_filter_setting_arr[1]);
		
		$t_filter_link = filter_get_url($t_unserialized_filter);
		
		$t_html .= '<table id="dashboard-custom-box-' . $t_box_id . '" class="width100" cellspacing="1">';
		# -- Navigation header row --
		$t_html .= '<tr>';
		# -- Viewing range info --
		$t_html .= '<td class="form-title" colspan="2">';
		
		$t_html .= self::get_link_html($t_filter_link, $t_box_title, false, 'subtle')
				. '&#160;'
				. self::get_bracket_link_html($t_filter_link, '^', true, 'subtle');
		
		if($t_filter != false) {	
			# get filter string
			$t_filter_setting_arr = explode('#', $t_filter['filter_string'], 2);
			$t_unserialized_filter = unserialize($t_filter_setting_arr[1]);
			
			# get bug rows with unserialized filter string
			$t_rows = filter_get_bug_rows($f_page_number, $t_per_page, $t_page_count, $t_bug_count, 
					$t_unserialized_filter, helper_get_current_project());
		
			$t_array = self::get_custom_filtered_projects_html($t_filter, $t_rows);
			$t_projects_html = $t_array['projects'];
			
			if(count($t_rows) > 0) {
				$v_start = $t_filter[FILTER_PROPERTY_ISSUES_PER_PAGE] * ($f_page_number - 1) + 1;
				$v_end = $v_start + count($t_rows) - 1;
			}
			else {
				$v_start = 0;
				$v_end = 0;
			}
			
			$t_html .= "<span id='custom-description-box-" . $t_box_id . 
				"'>($v_start - $v_end / $t_bug_count)</span>";
		} else {
			$t_error_text = plugin_lang_get('error_filter_deleted');
			$t_projects_html = "<tr><td><span class='error'>$t_error_text</span></td></tr>";
		}
		
		//print hide icon 
		$t_html .= self::get_icon_link_html(self::ICON_LINK_DRAG, $t_box_id)
				#. self::get_icon_link_html(self::ICON_LINK_HIDE, $t_box_id)
				. self::get_icon_link_html(self::ICON_LINK_EDIT, $t_box_id)
				. '</td>'
				. '</tr>';
		
		$t_html .= $t_projects_html		
				. '</table>';
		
		return $t_html;
	}

	/**
	 * Return the custom project html in an Array with keys 'projects' and 'counter'
	 * 
	 * @param $p_filter
	 * 
	 * @return Array
	 */
	static function get_custom_filtered_projects_html($p_filter, $p_rows)
	{
		//print_r($p_filter); echo "<br><br>";
						
		$t_rows = $p_rows;
		$t_filter = $p_filter;
		
		$t_update_bug_threshold = config_get('update_bug_threshold');
		$t_icon_path = config_get( 'icon_path' );
		
		#print project table rows
		$t_html = "";
		
		# -- Loop over bug rows and create $v_* variables --
		$t_count = count( $t_rows );
		for( $i = 0;$i < $t_count; $i++ ) {
			$t_bug = $t_rows[$i];
		
			$t_summary = string_display_line_links( $t_bug->summary );
			$t_last_updated = date( config_get( 'normal_date_format' ), $t_bug->last_updated );
		
			# choose color based on status
			$status_color = get_status_color( $t_bug->status, auth_get_current_user_id(), $t_bug->project_id );
		
			# Check for attachments
			$t_attachment_count = 0;
			# TODO: factor in the allow_view_own_attachments configuration option
			# instead of just using a global check.
			if(( file_can_view_bug_attachments( $t_bug->id, null ) ) ) {
				$t_attachment_count = file_bug_attachment_count( $t_bug->id );
			}
		
			# grab the project name
			$project_name = project_get_field( $t_bug->project_id, 'name' );
			$t_html .= '<tr bgcolor="' . $status_color. '">';
	
			# -- Bug ID and details link + Pencil shortcut --
			$t_html .= '<td class="center" valign="top" width ="0" nowrap="nowrap">'
						.'<span class="small">';
						
			$t_html .= string_get_bug_view_link($t_bug->id);
		
			$t_html .= '<br />';
		
			if( !bug_is_readonly( $t_bug->id ) && access_has_bug_level( $t_update_bug_threshold, $t_bug->id ) ) {
				$t_html .= '<a href="' . string_get_bug_update_url( $t_bug->id ) . '"><img border="0" src="' . $t_icon_path . 'update.png' . '" alt="' . lang_get( 'update_bug_button' ) . '" /></a>';
			}
		
			if( ON == config_get( 'show_priority_text' ) ) {
				#print_formatted_priority_string( $t_bug ); # from print_api.php
				$t_pri_str = get_enum_element( 'priority', $t_bug->priority, auth_get_current_user_id(), $t_bug->project_id );
				$t_priority_threshold = config_get( 'priority_significant_threshold' );

				if( $t_priority_threshold >= 0 &&
					$t_bug->priority >= $t_priority_threshold &&
					$t_bug->status < config_get( 'bug_closed_status_threshold' ) ) {
					$t_hmtl .= "<span class=\"bold\">$t_pri_str</span>";
				} else {
					$t_html .= $t_pri_str;
				}
			} else {
				#print_status_icon( $t_bug->priority ); # from icon_api.php
				$t_html .= icon_get_status_icon($t_bug->priority);
			}
		
			if ( $t_attachment_count > 0 ) {
				$t_href = string_get_bug_view_url( $t_bug->id ) . '#attachments';
				$t_href_title = sprintf( lang_get( 'view_attachments_for_issue' ), $t_attachment_count, $t_bug->id );
				$t_alt_text = $t_attachment_count . lang_get( 'word_separator' ) . lang_get( 'attachments' );
				$t_html .= "<a href=\"$t_href\" title=\"$t_href_title\"><img src=\"${t_icon_path}attachment.png\" alt=\"$t_alt_text\" title=\"$t_alt_text\" /></a>";
			}
		
			if( VS_PRIVATE == $t_bug->view_state ) {
				$t_html .= '<img src="' . $t_icon_path . 'protected.gif" width="8" height="15" alt="' . lang_get( 'private' ) . '" />';
			}
			$t_html .=	'</span>'
						.'</td>';
		
			# -- Summary --
			$t_html .= '<td class="left" valign="top" width="100%">'
						.'<span class="small">';
						
				 	if( ON == config_get( 'show_bug_project_links' ) && helper_get_current_project() != $t_bug->project_id ) {
						$t_html .= '[' . string_display_line( project_get_name( $t_bug->project_id ) ). '] ';
					}
					$t_html .= $t_summary;
					
			$t_html .= '<br />';
			
			# type project name if viewing 'all projects' or bug is in subproject
			$t_html .= string_display_line( category_full_name( $t_bug->category_id, true, $t_bug->project_id ) );
		
			if( $t_bug->last_updated > strtotime( '-' . $t_filter[FILTER_PROPERTY_HIGHLIGHT_CHANGED] . ' hours' ) ) {
				$t_html .= ' - <b>' . $t_last_updated . '</b>';
			} else {
				$t_html .= ' - ' . $t_last_updated;
			}
			
			$t_html .=	'</span>'
						.'</td>'
						.'</tr>';
			# -- end of Repeating bug row --
		}

		# create projects count html
		if( count( $t_rows ) > 0 ) {
			$v_start = $t_filter[FILTER_PROPERTY_ISSUES_PER_PAGE] * ( $f_page_number - 1 ) + 1;
			$v_end = $v_start + count( $t_rows ) - 1;
		}
		else {
			$v_start = 0;
			$v_end = 0;
		}
		
		$t_counter_html = "<span id='description_box_" . $p_box_id . "'>($v_start - $v_end / $t_bug_count)</span>";

		return array( 'projects' => $t_html, 'counter' => $t_counter_html);
	}

	/**
	 * Returns the projects html to update via JavaScript.
	 * Return Array has keys 'projects' for project table rows 
	 * and 'counter' for project counter in top row, e.g. '(1 - 4 / 6)'.
	 * 
	 * @param $box_id
	 * @param $project_id
	 * 
	 * @return array html of filtered projects
	 */
	static function get_projects_html($p_box_id, $p_project_id)
	{
		$t_filter = filter_get_default();
		$t_current_user_id = auth_get_current_user_id();
		
		$t_array_keys = array_keys(config_get('my_view_boxes'), $p_box_id);
		$t_box_title_short = $t_array_keys[0];
		$t_box_title = lang_get( 'my_view_title_' . $t_box_title_short );
		
		$t_update_bug_threshold = config_get( 'update_bug_threshold' );
		$t_icon_path = config_get( 'icon_path' );
		
		$f_page_number = gpc_get_int( 'page_number', 1 );
		$t_per_page = config_get( 'my_view_bug_count' );
		$t_bug_count = null;
		$t_page_count = null;
		
		$url_link_parameters = self::get_url_link_parameters();
		$t_box_filter = self::get_box_filter($t_box_title_short);
		
		#get rows
		$t_rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $t_box_filter, $p_project_id, $t_current_user_id);	
		$t_filter = array_merge($t_box_filter, $t_filter);
		
		#print project table rows
		$t_html = "";
		
		# -- Loop over bug rows and create $v_* variables --
		$t_count = count( $t_rows );
		for( $i = 0;$i < $t_count; $i++ ) {
			$t_bug = $t_rows[$i];
		
			$t_summary = string_display_line_links( $t_bug->summary );
			$t_last_updated = date( config_get( 'normal_date_format' ), $t_bug->last_updated );
		
			# choose color based on status
			$status_color = get_status_color( $t_bug->status, auth_get_current_user_id(), $t_bug->project_id );
		
			# Check for attachments
			$t_attachment_count = 0;
			# TODO: factor in the allow_view_own_attachments configuration option
			# instead of just using a global check.
			if(( file_can_view_bug_attachments( $t_bug->id, null ) ) ) {
				$t_attachment_count = file_bug_attachment_count( $t_bug->id );
			}
		
			# grab the project name
			$project_name = project_get_field( $t_bug->project_id, 'name' );
			$t_html .= '<tr bgcolor="' . $status_color. '">';
	
			# -- Bug ID and details link + Pencil shortcut --
			$t_html .= '<td class="center" valign="top" width ="0" nowrap="nowrap">'
						.'<span class="small">';
						
			$t_html .= string_get_bug_view_link($t_bug->id);
		
			$t_html .= '<br />';
		
			if( !bug_is_readonly( $t_bug->id ) && access_has_bug_level( $t_update_bug_threshold, $t_bug->id ) ) {
				$t_html .= '<a href="' . string_get_bug_update_url( $t_bug->id ) . '"><img border="0" src="' . $t_icon_path . 'update.png' . '" alt="' . lang_get( 'update_bug_button' ) . '" /></a>';
			}
		
			if( ON == config_get( 'show_priority_text' ) ) {
				#print_formatted_priority_string( $t_bug ); # from print_api.php
				$t_pri_str = get_enum_element( 'priority', $t_bug->priority, auth_get_current_user_id(), $t_bug->project_id );
				$t_priority_threshold = config_get( 'priority_significant_threshold' );

				if( $t_priority_threshold >= 0 &&
					$t_bug->priority >= $t_priority_threshold &&
					$t_bug->status < config_get( 'bug_closed_status_threshold' ) ) {
					$t_hmtl .= "<span class=\"bold\">$t_pri_str</span>";
				} else {
					$t_html .= $t_pri_str;
				}
			} else {
				#print_status_icon( $t_bug->priority ); # from icon_api.php
				$t_html .= icon_get_status_icon($t_bug->priority);
			}
		
			if ( $t_attachment_count > 0 ) {
				$t_href = string_get_bug_view_url( $t_bug->id ) . '#attachments';
				$t_href_title = sprintf( lang_get( 'view_attachments_for_issue' ), $t_attachment_count, $t_bug->id );
				$t_alt_text = $t_attachment_count . lang_get( 'word_separator' ) . lang_get( 'attachments' );
				$t_html .= "<a href=\"$t_href\" title=\"$t_href_title\"><img src=\"${t_icon_path}attachment.png\" alt=\"$t_alt_text\" title=\"$t_alt_text\" /></a>";
			}
		
			if( VS_PRIVATE == $t_bug->view_state ) {
				$t_html .= '<img src="' . $t_icon_path . 'protected.gif" width="8" height="15" alt="' . lang_get( 'private' ) . '" />';
			}
			$t_html .=	'</span>'
						.'</td>';
		
			# -- Summary --
			$t_html .= '<td class="left" valign="top" width="100%">'
						.'<span class="small">';
						
				 	if( ON == config_get( 'show_bug_project_links' ) && helper_get_current_project() != $t_bug->project_id ) {
						$t_html .= '[' . string_display_line( project_get_name( $t_bug->project_id ) ). '] ';
					}
					$t_html .= $t_summary;
					
			$t_html .= '<br />';
			
			# type project name if viewing 'all projects' or bug is in subproject
			$t_html .= string_display_line( category_full_name( $t_bug->category_id, true, $t_bug->project_id ) );
		
			if( $t_bug->last_updated > strtotime( '-' . $t_filter[FILTER_PROPERTY_HIGHLIGHT_CHANGED] . ' hours' ) ) {
				$t_html .= ' - <b>' . $t_last_updated . '</b>';
			} else {
				$t_html .= ' - ' . $t_last_updated;
			}
			
			$t_html .=	'</span>'
						.'</td>'
						.'</tr>';
			# -- end of Repeating bug row --
		}

		# create projects count html
		if( count( $t_rows ) > 0 ) {
			$v_start = $t_filter[FILTER_PROPERTY_ISSUES_PER_PAGE] * ( $f_page_number - 1 ) + 1;
			$v_end = $v_start + count( $t_rows ) - 1;
		}
		else {
			$v_start = 0;
			$v_end = 0;
		}
		
		$t_counter_html = "<span id='description_box_" . $p_box_id . "'>($v_start - $v_end / $t_bug_count)</span>";

		return array( 'projects' => $t_html, 'counter' => $t_counter_html);
	}

	/**
	 * Prints the given icon in a span with class "dashboard-{$type}"
	 * Possible types: ICON_LINK_DRAG, ICON_LINK_SHOW, ICON_LINK_HIDE
	 * Text if set is standing on the right side of the icon.
	 * 
	 * @param $p_type
	 * @param $p_box_id = 0
	 * @param $p_text = ""
	 */
	static function print_icon_link($p_type, $p_box_id = 0, $p_text = "")
	{		
		echo self::get_icon_link_html($p_type, $p_box_id, $p_text);
	}
	
	/**
	 * Gets the html for given icon in a span with class "dashboard-{$type}"
	 * Possible types: ICON_LINK_DRAG, ICON_LINK_SHOW, ICON_LINK_HIDE
	 * Text if set is standing on the right side of the icon.
	 * 
	 * @param $p_type
	 * @param $p_box_id = 0
	 * @param $p_text = ""
	 * 
	 * @return String
	 */
	static function get_icon_link_html($p_type, $p_box_id = 0, $p_text = "")
	{		
		$t_title = plugin_lang_get($p_type . "_box");
		$t_html = "";
		
		switch($p_type) {
			case self::ICON_LINK_HIDE:
				$t_html .= self::get_form_html($p_type, $p_box_id, self::ICON_HIDE, $t_title, $p_text);
				break;
			case self::ICON_LINK_SHOW:
				$t_html .= self::get_form_html($p_type, $p_box_id, self::ICON_SHOW, $t_title, $p_text);
				break;
			case self::ICON_LINK_DRAG:
				$t_html .= self::get_form_html($p_type, $p_box_id, self::ICON_DRAG, $t_title, $p_text);
				break;
			case self::ICON_LINK_EDIT:
				$t_html .= self::get_form_html($p_type, $p_box_id, self::ICON_EDIT, $t_title, $p_text);
				break;
			case self::ICON_LINK_DELETE:
				$t_html .= self::get_form_html($p_type, $p_box_id, self::ICON_DELETE, $t_title, $p_text);
				break;
			default:
				$t_html .= "<span>No such link avaliable!</span>";
				break;
		}
		
		return $t_html ;
	}
	
	/**
	 * Print the visbility change form forhiding/showing a box.
	 * 
	 * @param $p_type (for setting visibility value)
	 * @param $p_box_id 
	 * @param $p_icon (image in files folder)
	 * @param $p_title (for tooltip)
	 */
	static function print_form($p_type, $p_box_id, $p_icon, $p_title, $p_text)
	{		
		echo self::get_form_html($p_type, $p_box_id, $p_icon, $p_title, $p_text);
	}
	
	/**
	 * Gets the visbility change form in html string for hiding/showing a box.
	 * 
	 * @param $p_type (for setting visibility value)
	 * @param $p_box_id 
	 * @param $p_icon (image in files folder)
	 * @param $p_title (for tooltip)
	 */
	static function get_form_html($p_type, $p_box_id, $p_icon, $p_title, $p_text)
	{
		$t_html = "";
		$t_custom_boxes_view = (ON == plugin_config_get('allow_custom_boxes_view'));
		
		# set show-custom-box class to custom box, if enabled
		if($p_type == self::ICON_LINK_SHOW && $t_custom_boxes_view){
			$t_form_class = $p_type . "-custom-box";
		} else {
			$t_form_class = $p_type . "-box";
		}
		$t_icon_class = $p_type . "-icon";
		
		$t_html .= "<form class=$t_form_class >";
		$t_html .= self::get_icon_html($p_type, $p_title);
		
		if(!empty($p_text)) {
			$t_html .= "<span class='dashboard-form-text'>" . $p_text . "</span>";
		}
		
		$t_html .= "</span>";
		#hidden inputs
		$t_html .= '<input type="hidden" name="box_id" value="'. $p_box_id .'">';

		if($p_type == self::ICON_LINK_HIDE || $p_type == self::ICON_LINK_SHOW){
			$t_visible = True;
			
			if($p_type == self::ICON_LINK_HIDE) {
				$t_visible = False;
			}
			
			$t_html .= '<input type="hidden" name="visible" value="'. (int)$t_visible.'">';
		}
		
		if($p_type == self::ICON_LINK_DRAG){
			if($t_custom_boxes_view){
				$t_box_data = DashboardDbAPI::get_custom_box_data($p_box_id);
				$t_box_name = $t_box_data['title'];				
			} else {
				$t_array_keys = array_keys(config_get('my_view_boxes'), $p_box_id);
				$t_box_name = $t_array_keys[0];
			}
			
			$t_html .= '<input type="hidden" name="box_name" value="'. $t_box_name .'">';
		}
		
		if($p_type == self::ICON_LINK_EDIT){
			$t_box = DashboardDbAPI::get_custom_box_data($p_box_id);
			$t_box_title = $t_box['title'];
			$t_box_filter_id = $t_box['filter_id'];
			
			$t_html .= '<input type="hidden" name="orig_box_title" value="'. $t_box_title.'">';
			$t_html .= '<input type="hidden" name="orig_box_filter_id" value="'. $t_box_filter_id.'">';
		}
		
		$t_html .= "</form>";
		
		return $t_html;
	}

	/**
	 * Prints the icon of given type with given title-tag.
	 */
	static function print_icon($p_type, $p_title = "")
	{
		echo self::get_icon_html($p_type, $p_title);
	}
	
	/**
	 * Gets the html for the icon of given type with given title-tag.
	 * 
	 * @param $p_type
	 * @param $p_title
	 * 
	 * @return String
	 */
	static function get_icon_html($p_type, $p_title = "")
	{
		$t_icon_class = $p_type . "-icon";
		$t_html .= "<span class='dashboard-". $p_type ."' title='". $p_title ."'><span class='$t_icon_class'></span></span>";
		
		return $t_html;
	}

	/**
	 * Prints visibility link list of given boxes.
	 */
	static function print_visibility_link_list()
	{
		echo "<div id='dashboard-visibility-list'>";
		echo "<div id='dashboard-visibility-list-container'><div class='visibility-list-item-raw'><b>" . plugin_lang_get('display_box') . "</b></div>";
		
		$t_count_visibles = 0;
		
		if(plugin_config_get('allow_default_boxes_view') == ON){
			$t_boxes = DashboardDbAPI::get_positioned_default_boxes_data();
			while (list ($t_box_title, $t_box_id) = each ($t_boxes)) {			
				
				if($t_box_id != 0 && !DashboardDbAPI::get_box_visibility($t_box_id)) {
					echo self::get_visibility_list_item_html($t_box_id);
				} else {
					$t_count_visibles++;
				}
			}
		} else if(plugin_config_get('allow_custom_boxes_view') == ON){
			$t_boxes = DashboardDbAPI::get_positioned_custom_boxes_data();
			foreach ($t_boxes as $t_key => $t_value) {
				$t_box_id = $t_value;						
				
				if($t_box_id != 0 && !DashboardDbAPI::get_custom_box_visibility($t_box_id)) {
					echo self::get_visibility_list_item_html($t_box_id);
				} else {
					$t_count_visibles++;
				}
			}
		}
		
		if($t_count_visibles == count($t_boxes)) {
			echo "<div class='visibility-list-item-raw visibility-list-item-filler'>" . plugin_lang_get('all_visible') . "</div>";
		}
		
		echo "</div>";
		if(plugin_config_get('allow_custom_boxes_view') == ON){
			self::print_create_new_box_dialog();
			self::print_create_new_box_button();
			
			self::print_create_edit_box_dialog();
		}
		echo "</div>";
	}	
	
	/**
	 * Gets the visibility link item html string for a certain box.
	 * 
	 * @param $p_box_id
	 */
	static function get_visibility_list_item_html($p_box_id)
	{
		if($p_box_id === null) {
			return "<div class='visibility-list-item-raw visibility-list-item-filler'>" . plugin_lang_get('all_visible') . "</div>";
		}
		
		$t_html = "";
	
		if(plugin_config_get('allow_default_boxes_view') == ON){
			if($p_box_id != 0 && !DashboardDbAPI::get_box_visibility($p_box_id)) {
				# get long box title
				$t_array_keys = array_keys(config_get('my_view_boxes'), $p_box_id);
				$t_box_title_short = $t_array_keys[0];
				$t_box_title_long = lang_get( 'my_view_title_' . $t_box_title_short);
				$t_title = plugin_lang_get('show_box');
				
				$t_html .= "<div id='visibility-list-item-". $p_box_id ."' class='visibility-list-item'>";
				
				$t_html .= self::get_form_html(self::ICON_LINK_SHOW, $p_box_id, self::ICON_SHOW, $t_title, $t_box_title_long);
				$t_html .= "</div>";
			}
		} else if(plugin_config_get('allow_custom_boxes_view') == ON){
			if($p_box_id != 0 && !DashboardDbAPI::get_custom_box_visibility($p_box_id)) {
				# get box title
				$t_box = DashboardDbAPI::get_custom_box_data($p_box_id);
				$t_box_title_long = $t_box['title'];
				$t_title = plugin_lang_get('show_box');
				
				$t_html .= "<div id='visibility-list-item-". $p_box_id ."' class='visibility-list-item'>";
				
				$t_html .= self::get_form_html(self::ICON_LINK_SHOW, $p_box_id, self::ICON_SHOW, $t_title, $t_box_title_long);
				$t_html .= "</div>";
			}
		}
		
		return $t_html;
	}
	
	/**
	 * Prints a filter select box of all custom filters.
	 * 
	 * @params $p_expanded
	 */
	static function print_custom_filter_select_box($p_id_suffix = "", $p_expanded = true)
	{
		$t_form_name_suffix = $p_expanded ? '_open' : '_closed';
		$t_stored_queries_arr = filter_db_get_available_queries();
		
		if(!empty($p_id_suffix)){
			$p_id_suffix .= "-";
		}
		
		echo '<div class="dialog-form-field">';
		
		if( count( $t_stored_queries_arr ) > 0 ) {
			$t_filter_text = plugin_lang_get('filter');
			
			# filter select
			echo "<label class='dashboard-label' for='custom-filter-select'>$t_filter_text</label>";
			echo "<select id='" . $p_id_suffix . "custom-filter-select' name='" . $p_id_suffix . "custom-filter-select' class='dashboard-input'>";
			?>
				<!--<option value="-1"><?php echo '[' . lang_get( 'reset_query' ) . ']'?></option> -->
				<!--<option value="-1"></option> -->
			<?php
			
			$t_source_query_id = isset( $t_filter['_source_query_id'] ) ? (int)$t_filter['_source_query_id'] : -1;
			foreach( $t_stored_queries_arr as $t_query_id => $t_query_name ) {
				echo '<option value="' . $t_query_id . '" ';
				check_selected( $t_query_id, $t_source_query_id );
				echo '>' . string_display_line( $t_query_name ) . '</option>';
			}
			
			echo "</select>";
		} else {
			echo "<span>" . plugin_lang_get('no_filters_available') . "</span>";
		}
		
		#print link to filter page
		self::print_create_new_filter_link();
		echo '</div>';
	}

	/**
	 * Prints the link 'view_all_bug_page.php' to create a new filter.
	 */
	static function print_create_new_filter_link()
	{
		$t_title = plugin_lang_get('create_new_filter');
		
		$t_html .= "<span class='dashboard-add' title='". $t_title ."'>"
				."<a href='view_all_bug_page.php' class='add-icon'></a></span>";
				
		echo $t_html;
	}

	/**
	 * Prints the button for creating a new box.
	 */
	static function print_create_new_box_button()
	{
		echo self::get_create_new_box_button_html();
	}
	
	/**
	 * Gets the button html for creating a new box.
	 */
	static function get_create_new_box_button_html()
	{
		$t_text = plugin_lang_get('create_new_box');
		$t_html = "<button id='create-box' title='" . $t_text . "'>" . $t_text . "</button>";
		
		return $t_html;
	}	

	/**
	 * Prints the dialog to create a new box.
	 */
	static function print_create_new_box_dialog()
	{
		echo '<div id="dashboard-new-box-dialog" title="' . plugin_lang_get('create_new_box') . '">';
		echo "<form>";
		
		# box title textfield
		echo '<div class="dialog-form-field">';
		$t_box_title = plugin_lang_get('box_title');
		echo "<label class='dashboard-label' for='box-title'>$t_box_title</label>";
		echo "<input id='create-box-title' name='box-title' type='text' title='$t_box_title' class='dashboard-input'>";		
		echo '</div>'; 
		
		# filter select
		self::print_custom_filter_select_box("create");
		
		echo "</form>";
		echo '</div>';
	}
	
	/**
	 * Prints the dialog to edit a box.
	 */
	static function print_create_edit_box_dialog()
	{
		echo '<div id="dashboard-edit-box-dialog" title="' . plugin_lang_get('edit_box') . '">';
		echo "<form>";
		
		# box title textfield
		echo '<div class="dialog-form-field">';
		$t_box_title = plugin_lang_get('box_title');
		echo "<label class='dashboard-label' for='box-title'>$t_box_title</label>";
		echo "<input id='edit-box-title' name='box-title' type='text' title='$t_box_title' class='dashboard-input'>";		
		echo '</div>'; 
		
		# filter select
		self::print_custom_filter_select_box("edit");
		
		# visibility checkbox
		echo '<div class="dialog-form-field">';
		$t_text_show_box = plugin_lang_get('show_box');
		echo "<label class='dashboard-label' for='box-visible'>$t_text_show_box</label>";
		#self::print_icon(self::ICON_LINK_SHOW);
		echo "<input id='edit-box-visible-checkbox' name='box-visible' type='checkbox' title='$t_text_show_box' class='dashboard-input' checked>";
		echo '</div>';
		
		# visibility checkbox
		echo '<div id="delete-box-link" class="dialog-form-field">';
		self::print_icon_link(self::ICON_LINK_DELETE);
		echo '</div>';
		
		echo "</form>";
		echo '</div>';
		
		self::print_confirm_delete_dialog();
	}
	
	/**
	 * Prints the confirm delete dialog
	 */
	static function print_confirm_delete_dialog()
	{
		echo '<div id="dialog-confirm" title="' . plugin_lang_get('delete_box') . '">'
  			. '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>'
  			. plugin_lang_get('confirm_delete_box')
			. '</p></div>';
	}
	
	
	/**
	 * Corresponding function to print_api.php's print_link only with returning html instead of printing directly.
	 * 
	 * @param $p_link
	 * @param $p_url_text
	 * @param $p_new_window
	 * @param $p_class
	 * 
	 * @return String
	 */
	static function get_link_html($p_link, $p_url_text, $p_new_window = false, $p_class = '')
	{
		$t_html = "";
		if( is_blank( $p_link ) ) {
			$t_html .= $p_url_text;
		} else {
			$t_link = htmlspecialchars( $p_link );
			if( $p_new_window === true ) {
				if( $p_class !== '') {
					$t_html .= "<a class=\"$p_class\" href=\"$t_link\" target=\"_blank\">$p_url_text</a>";
				} else {
					$t_html .= "<a href=\"$t_link\" target=\"_blank\">$p_url_text</a>";
				}
			} else {
				if( $p_class !== '') {
					$t_html .= "<a class=\"$p_class\" href=\"$t_link\">$p_url_text</a>";
				} else {
					$t_html .= "<a href=\"$t_link\">$p_url_text</a>";
				}
			}
		}
		
		return $t_html;
	}
	
	/**
	 * Correspoding function to print_api.php's nly with returning html instead of printing directly.
	 *
	 * creates the bracketed links used near the top
	 * if the $p_link is blank then the text is built but no link is created
	 * if $p_new_window is true, link will open in a new window, default false.
	 * 
	 * @param
	 * @param
	 * @param
	 * @param
	 * 
	 * @return String
	 */
	static function get_bracket_link_html($p_link, $p_url_text, $p_new_window = false, $p_class = '') 
	{
		$t_html .= '<span class="bracket-link">[&#160;';
		$t_html .= self::get_link_html($p_link, $p_url_text, $p_new_window, $p_class);
		$t_html .= '&#160;]</span> ';
		
		return $t_html;
	}

	/**
	 * Gets the html for a alredy available box messagewith specified params.
	 * 
	 * @param $p_title
	 * @param $p_filter_id
	 * 
	 * @return String
	 */
	static function get_already_available_box_message_html($p_title, $p_filter_id)
	{
		$t_html = "";
		
		$t_filter_name = filter_db_get_name($p_filter_id);		
		$t_html .= '<span class="error">'
				. plugin_lang_get('error_the_box_with_title')
				. $p_title
				. plugin_lang_get('error_and_filter_name')
				. $t_filter_name
				. plugin_lang_get('error_already_exists')
				. '</span>';
		
		return $t_html;
	}
	
	/**
	 * Get the html for an info-dialog.
	 * 
	 * @param String $p_id
	 * @param String $p_title
	 * 
	 * @return String
	 */
	static function get_info_dialog_html($p_text="", $p_id = "info-dialog", $p_title = "Info")
	{
		return "<div id='$p_id' title='$p_title'>$p_text</div>";	
	}
	
	//TODO: create sudoku box ;)
}


