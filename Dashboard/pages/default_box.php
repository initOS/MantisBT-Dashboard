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

# checks whether request is post
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header("Location: " . $_SERVER['DOCUMENT_ROOT']);
   	exit();
}

$t_mantis_core_path = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
$t_mantis_api_path = $t_mantis_core_path . DIRECTORY_SEPARATOR . "core" .DIRECTORY_SEPARATOR;
require_once($t_mantis_core_path . "core.php");
require_once($t_mantis_api_path . "gpc_api.php");
require_once($t_mantis_api_path ."project_api.php");
require_once($t_mantis_api_path . "plugin_api.php");
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "dashboard_print_api.php");

const POSITION = 'position';
const VISIBILITY = 'visibility';
const FILTER = 'filter';

# Sets the box's project filter with the given parameters
# and returns the JSON encoded response.
function set_filter() {
	$f_project_id = gpc_get_int('project_id');
	$f_box_id = gpc_get_string('box_id');

	$t_changed =  DashboardDbAPI::set_box_filter_project($f_box_id, $f_project_id);

	$t_splitted_project_id = split(';', $f_project_id);
	$t_project_id = $t_splitted_project_id[0];

	$t_html = DashboardPrintAPI::get_projects_html($f_box_id, $t_project_id);
	$t_projects_html = $t_html['projects'];
	$t_projects_counter_html = $t_html['counter'];

	$t_return_data = array(
		"box_id" => $f_box_id,
		"project_id" => $f_project_id,
		"html" => array(
			"projects" => $t_projects_html,
			"counter" => $t_projects_counter_html
		),
		"saved" => $t_changed
	);

	return json_encode($t_return_data);
};

# Sets the boxes position with the given parameters
# and returns the JSON encoded response.
function set_position() {
	$f_position_string = gpc_get_string('box_positions', '');

	$t_changed = DashboardDbAPI::set_boxes_position($f_position_string);

	$t_return_data = array(
		"saved" => $t_changed,
	);

	return json_encode($t_return_data);
};

# Sets the boxes visibility with the given parameters
# and returns the JSON encoded response.
function set_visibility() {
	$f_box_id = gpc_get_int('box_id');
	$f_visible = gpc_get_int('visible');

	$t_changed =  DashboardDbAPI::set_box_visibility($f_box_id, $f_visible);
	$t_link_item = DashboardPrintAPI::get_visibility_list_item_html($f_box_id);

	$t_return_data = array(
		"box_id" => $f_box_id,
		"visible" => $f_visible,
		"saved" => $t_changed,
		"link_show_html" => $t_link_item
	);

	return json_encode($t_return_data);
};

# Runs the action by the given action parameter.
function run_action($p_action) {
	$t_json_response = "";

	switch ($p_action) {
		case POSITION:
			$t_json_response = set_position();
			break;
		case VISIBILITY:
			$t_json_response = set_visibility();
			break;
		case FILTER:
			$t_json_response = set_filter();
			break;
		default:
			$t_json_response = json_encode(array(
			"saved" => false,
			"error" => "No action defined!"
			));
	};

	$t_javascript_on = (ON == config_get('use_javascript'));

	if ($t_javascript_on) {
		echo $t_json_response;
	} else {
		header("Location:" . plugin_page('dashboard'));
	}

	exit();
};

# run action
$f_action = gpc_get_string('action');
run_action($f_action);
