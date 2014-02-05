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

auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$f_boxes_view = gpc_get_string( 'boxes_view', 'custom');
$f_initial_boxes = gpc_get_string('available_boxes_string', '');

if($f_boxes_view == 'default'){
	$t_default = ON;
	$t_custom = OFF;	
} else {
	$t_default = OFF;
	$t_custom = ON;
}

# save parameters
plugin_config_set('allow_custom_boxes_view', $t_custom);
plugin_config_set('allow_default_boxes_view', $t_default);
plugin_config_set('initial_custom_boxes', $f_initial_boxes);

print_successful_redirect( plugin_page( 'config_page',TRUE ) );
