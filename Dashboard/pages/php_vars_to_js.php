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

require_once( dirname(__DIR__) . DIRECTORY_SEPARATOR . "api" . DIRECTORY_SEPARATOR . "dashboard_print_api.php");
header ("Content-Type: text/javascript");
?>

var pluginLangGet = {
	'menuname': '<?php echo plugin_lang_get('menuname'); ?>',
	'title': '<?php echo plugin_lang_get('title'); ?>',
	'description': '<?php echo plugin_lang_get('description'); ?>',
	'config_title': '<?php echo plugin_lang_get('config_title'); ?>',
	'boxes_view': '<?php echo plugin_lang_get('boxes_view'); ?>',
	'boxes_view_default': '<?php echo plugin_lang_get('boxes_view_default'); ?>',
	'boxes_view_custom': '<?php echo plugin_lang_get('boxes_view_custom'); ?>',
	'filter_already_used': '<?php echo plugin_lang_get('filter_already_used'); ?>',
	'box_title_placeholder': '<?php echo plugin_lang_get('box_title_placeholder'); ?>',
	'column_placeholder': '<?php echo plugin_lang_get('column_placeholder'); ?>',
	'cancel': '<?php echo plugin_lang_get('cancel'); ?>',
	'hide_box': '<?php echo plugin_lang_get('hide_box'); ?>',
	'show_box': '<?php echo plugin_lang_get('show_box'); ?>',
	'drag_box': '<?php echo plugin_lang_get('drag_box'); ?>',
	'edit_box': '<?php echo plugin_lang_get('edit_box'); ?>',
	'save_box': '<?php echo plugin_lang_get('save_box'); ?>',
	'delete_box': '<?php echo plugin_lang_get('delete_box'); ?>',
	'display_box': '<?php echo plugin_lang_get('display_box'); ?>',
	'all_visible': '<?php echo plugin_lang_get('all_visible'); ?>',
	'has_been_deleted': '<?php echo plugin_lang_get('has_been_deleted'); ?>',
	'create_new_box': '<?php echo plugin_lang_get('create_new_box'); ?>',
	'confirm_delete_box': '<?php echo plugin_lang_get('confirm_delete_box'); ?>',
	'filter': '<?php echo plugin_lang_get('filter'); ?>',
	'box_title': '<?php echo plugin_lang_get('box_title'); ?>',
	'create_new_filter': '<?php echo plugin_lang_get('create_new_filter'); ?>',
	'no_filters_available': '<?php echo plugin_lang_get('no_filters_available'); ?>',
	'untitled': '<?php echo plugin_lang_get('untitled'); ?>',
	'error_already_exists': '<?php echo plugin_lang_get('error_already_exists'); ?>',
	'error_the_box_with_title': '<?php echo plugin_lang_get('error_the_box_with_title'); ?>',
	'error_and_filter_name': '<?php echo plugin_lang_get('error_and_filter_name'); ?>',
	'error_filter_deleted': '<?php echo plugin_lang_get('error_filter_deleted'); ?>',
	'error': '<?php echo plugin_lang_get('error'); ?>'
};

var emptyVisibilityListText = "<?php echo DashboardPrintAPI::get_visibility_list_item_html(null); ?>";