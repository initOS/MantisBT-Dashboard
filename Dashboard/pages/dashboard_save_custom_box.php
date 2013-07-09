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
$t_untitled = plugin_lang_get('untitled');

# params: title, filter_id, visibility
$f_title = gpc_get_string('title', $t_untitled);
$f_filter_id = gpc_get_int('filter_id', 0);
$f_box_id = gpc_get_int('box_id', 0);
$f_visible = gpc_get_int('visible', 1);


if(empty($f_title)){
	$f_title = $t_untitled;
}

# set filter id in data table
$t_result_array =  DashboardDbAPI::save_custom_box($f_title, $f_filter_id, $f_box_id, $f_visible);
$t_link_item = DashboardPrintAPI::get_visibility_list_item_html($f_box_id);

if($t_javascript_on){
	$return_data = array(
			"saved" => (boolean)($t_result_array['saved']),
			"box_id" => $f_box_id,
			"html" => $t_result_array['html'],
			"link_show_html" => $t_link_item,
			"visible" => $f_visible
			);
	
	# return the JSON
	$return_JASON = json_encode($return_data);
	echo $return_JASON;
} else {
	header("Location:" . plugin_page('dashboard'));
}

exit();
