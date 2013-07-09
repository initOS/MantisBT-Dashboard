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

html_page_top1( lang_get( 'plugin_Dashboard_title' ) );
html_page_top2();

print_manage_menu();
?>

<br/>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">
<table align="center" class="width50" cellspacing="1">

<tr>
	<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_Dashboard_config_title' ) ?>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php
			echo lang_get( 'plugin_Dashboard_boxes_view' );
		?>
	</td>
	<td class="center" width="40%">
		<label><input type="radio" name="boxes_view" value="default" <?php echo (ON == plugin_config_get('allow_default_boxes_view')) ? 'checked="checked" ' : '' ?>/>
			<?php echo plugin_lang_get( 'boxes_view_default' ) ?></label>
		<br>	
		<label><input type="radio" name="boxes_view" value="custom" <?php echo (ON == plugin_config_get('allow_custom_boxes_view')) ? 'checked="checked" ' : '' ?>/>
			<?php echo plugin_lang_get( 'boxes_view_custom' ) ?></label>
	</td>
</tr>
<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="<?php echo lang_get( 'change_configuration' ) ?>" />
	</td>
</tr>

</table>
<form>

<?php
html_page_bottom1( __FILE__ );
