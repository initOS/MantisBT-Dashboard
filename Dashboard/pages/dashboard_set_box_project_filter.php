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
 
# checks whether request is post and sets dashboard filters for requesting box and project

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header("Location: " . $_SERVER['DOCUMENT_ROOT']);
   	exit();
}

$t_mantis_core_path = dirname(dirname(dirname( __DIR__ ))) . DIRECTORY_SEPARATOR;
$t_mantis_api_path = $t_mantis_core_path . DIRECTORY_SEPARATOR . "core" .DIRECTORY_SEPARATOR;
require_once($t_mantis_core_path . "core.php");
require_once($t_mantis_api_path . "gpc_api.php");
require_once($t_mantis_api_path ."project_api.php");
require_once($t_mantis_api_path . "plugin_api.php");
require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "dashboard_print_api.php");

$t_javascript_on = (ON == config_get('use_javascript'));

//get post, save filter, get new filtered projects html
$f_project_id = (int)gpc_get_string('project_id');
$f_box_id = gpc_get_string('box_id');

# set filter in data table
$t_changed =  DashboardDbAPI::set_box_filter_project($f_box_id, $f_project_id);

$t_splitted_project_id = split(';', $f_project_id);
$t_project_id = $t_splitted_project_id[0]; 

if($t_javascript_on){
	
	$t_html = DashboardPrintAPI::get_projects_html($f_box_id, $t_project_id);
	$t_projects_html = $t_html['projects'];
	$t_projects_counter_html = $t_html['counter'];
	
	$return_data = array(
				"box_id" => $f_box_id,
				"project_id" => $f_project_id,
				"html" => array(
					"projects" => $t_projects_html,
					"counter" => $t_projects_counter_html
					),
				"saved" => $t_changed
				);
	
	# return the JSON
	$return_JSON = json_encode($return_data);
	echo $return_JSON;
} else {
	header("Location:" . plugin_page('dashboard'));
}

exit();

