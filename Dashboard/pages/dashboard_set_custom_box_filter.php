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

$t_mantis_core_path = dirname(dirname(dirname( __DIR__ ))) . DIRECTORY_SEPARATOR;
$t_mantis_api_path = $t_mantis_core_path . DIRECTORY_SEPARATOR . "core" .DIRECTORY_SEPARATOR;
require_once($t_mantis_core_path . "core.php");
require_once($t_mantis_api_path . "gpc_api.php");
require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "dashboard_print_api.php");

$t_javascript_on = (ON == config_get('use_javascript'));
$f_box_id = gpc_get_int('box_id');
$f_filter_id = gpc_get_int('filter_id');

# set filter id in data table
$t_changed =  DashboardDbAPI::set_custom_box_filter($f_box_id, $f_filter_id);

if($t_javascript_on){
	$return_data = array(
			"saved" => $t_changed,
			);
	
	# return the JSON
	$return_JSON = json_encode($return_data);
	echo $return_JSON;
} else {
	header("Location:" . plugin_page('dashboard'));
}

exit();
