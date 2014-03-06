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
class DashboardDbAPI
{
	const TABLE_CUSTOM_BOXES = 'custom_boxes';
	const TABLE_CUSTOM_BOXES_POSITIONS = 'custom_boxes_positions';
	const TABLE_BOXES = 'boxes';
	
	/**
	 * Checks whether a dataset of the current user exists in the dashboard boxes plugin table
	 * @return Boolean
	 */
	static function dashoard_data_exists()
	{
		$t_current_user_id = auth_get_current_user_id();
		
		$t_default_boxes_view = (ON == plugin_config_get('allow_default_boxes_view'));
		$t_custom_boxes_view = (ON == plugin_config_get('allow_custom_boxes_view'));
		
		$t_dashboard_table = '';
		if($t_default_boxes_view){
			$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		} else if($t_custom_boxes_view){
			$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);
		}
		$t_query = "SELECT id FROM $t_dashboard_table 
					WHERE user_id=".db_param();
	
		$t_result = db_query_bound($t_query, array($t_current_user_id));
	
		return (db_num_rows($t_result) > 0);
	}
	
	/**
	 * Checks whether the box with given id exists in the dashboard custom_boxes plugin table.
	 * 
	 * @param $p_box_id
	 * @return Boolean
	 */
	static function custom_box_exists_with_id($p_box_id = 0)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_query = "SELECT id FROM $t_dashboard_table 
					WHERE id=".db_param();
		$t_result = db_query_bound($t_query, array($p_box_id));
	
		return (db_num_rows($t_result) > 0);
	}
	
	/**
	 * Checks whether the box with given parameters exists in the dashboard custom_boxes plugin table
	 * @return Boolean
	 */
	static function custom_box_exists($p_title = "", $p_filter_id = 0)
	{
		if(empty($p_title) || $p_filter_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		
		$t_column_filter_id = 'filter_id';
		$t_column_user_id = 'user_id';
		$t_column_title = 'title';
		
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_query = "SELECT id FROM $t_dashboard_table 
					WHERE $t_column_user_id=" . db_param() . 
					" AND $t_column_title=".db_param() . 
					" AND $t_column_filter_id=" . db_param();
					
		$t_result = db_query_bound($t_query, array($t_current_user_id, $p_title, $p_filter_id));
	
		return (db_num_rows($t_result) > 0);
	}
	
	/**
	* Checks whether a position dataset for the current user exists 
	* in the dashboard custom boxes positions plugin table.
	* 
	* @return Boolean
	*/
	static function custom_boxes_position_data_exists()
	{
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);
	
		$t_query = "SELECT id FROM $t_dashboard_table 
					WHERE user_id=".db_param();
	
		$t_result = db_query_bound($t_query, array($t_current_user_id));
	
		return (db_num_rows($t_result) > 0);
	}
	
	/**
	 * Checks whether the current user has custom boxes.
	 * @return Boolean
	 */
	static function user_has_custom_boxes()
	{
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
	
		$t_query = "SELECT id FROM $t_dashboard_table 
					WHERE user_id=".db_param();
	
		$t_result = db_query_bound($t_query, array($t_current_user_id));
	
		return (db_num_rows($t_result) > 0);
	}
	
	
	/**
	 * Gets the filter project of a box of id $t_box_id.
	 * Box id must be grater than 0! 
	 * The method therefore returns false if box id parameter equals 0.
	 *  
	 * @param int $p_box_id
	 * @param Boolean $base - if true => if project id is of subproject (e.g. 1;6) return only parent project id (1)
	 * @return string bool
	 */
	static function get_box_filter_project($p_box_id, $p_base = false)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		$t_column = "box_".$p_box_id."_filter_id";

		$t_query = "SELECT $t_column FROM $t_dashboard_table 
					WHERE user_id = ".db_param();
					
		$t_result = db_query_bound($t_query, array($t_current_user_id));
		
		$t_result_array = db_fetch_array($t_result);
		$t_project_id = $t_result_array[$t_column];
		
		if($p_base) {
			$t_splitted_project_id = split(';', $t_project_id);
			$t_project_id = $t_splitted_project_id[0];
		}
		
		return $t_project_id;
	}
	
	/**
	 * sets the filter project of a box of id $t_box_id
	 * @param int $t_box_id
	 * @param bool $t_filter_project_id
	 * @return bool 
	 */
	static function set_box_filter_project($p_box_id, $p_filter_project_id)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		
		#if not already in db ==> insert
		if(!self::dashoard_data_exists()){
			#insert
			#echo " - insert - ";
			$t_query = "INSERT INTO $t_dashboard_table 
						(user_id, box_".$p_box_id."_filter_id) 
						VALUES (".db_param().','.db_param().")";
					
			$t_result = db_query_bound($t_query, array($t_current_user_id, $p_filter_project_id));
		} else {
			#update
			#echo " - update - ";
			$t_query = "UPDATE $t_dashboard_table 
						SET box_".$p_box_id."_filter_id = ".db_param()." 
				  		WHERE user_id = ".db_param();
			
			$t_result = db_query_bound($t_query, array($p_filter_project_id, $t_current_user_id));
		}
		
		return $t_result != false;
	}
	
	/**
	 * gets the visibility of a box of id $t_box_id
	 * @param int $t_box_id
	 * @return bool 
	 */
	static function get_box_visibility($p_box_id)
	{	
		if($p_box_id == 0){
			return false;
		}
		 
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		$t_column = "show_box_".$p_box_id;
		
		$t_query = "SELECT $t_column FROM $t_dashboard_table 
					WHERE user_id = ".db_param();
					
		$t_result = db_query_bound($t_query, array($t_current_user_id));
		$t_result_array = db_fetch_array($t_result);
		
		$t_visible = true;
		
		if(!empty($t_result_array)) {
			$t_visible = $t_result_array[$t_column];
		}
				
		return $t_visible;
	}
	
	/**
	 * sets the visibility of a box of id $t_box_id
	 * @param int $t_box_id
	 * @param bool $t_is_visible
	 * @return bool 
	 */
	static function set_box_visibility($p_box_id, $p_is_visible)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		$t_column = "show_box_".$p_box_id;
		
		#if not already in db ==> insert
		if(!DashboardDbAPI::dashoard_data_exists()){
			#insert
			$t_query = "INSERT INTO $t_dashboard_table 
						(user_id, $t_column) 
						VALUES (".db_param().','.db_param().")";
					
			$t_result = db_query_bound($t_query, array($t_current_user_id, $p_is_visible));
		} else {
			#update
			$t_query = "UPDATE $t_dashboard_table
						SET $t_column = ".db_param()." 
				  		WHERE user_id=".db_param();
			
			$t_result = db_query_bound($t_query, array($p_is_visible, $t_current_user_id));
		}
		
		return $t_result != false;
	}
	
	/**
	 * Gets the dashboard boxes ordered in position as saved in db.
	 * 
	 * @return array boxes 
	 */
	static function get_positioned_default_boxes_data()
	{
		$t_boxes = array();

		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		$t_column_positions = 'positions';
		$t_column_user_id = 'user_id';

		$t_query = "SELECT $t_column_positions FROM $t_dashboard_table 
					WHERE $t_column_user_id = ".db_param();

		$t_result = db_query_bound($t_query, array($t_current_user_id));
		
		if($t_result != false) {
			$t_result_array = db_fetch_array($t_result);
			
			if($t_result_array) {
				$t_position_string = $t_result_array[$t_column_positions];
			
				if(!empty($t_position_string)) {
					$t_boxes = self::_boxes_data_from_position_string($t_position_string);
				}
			} else {
				$t_configured_boxes = config_get('my_view_boxes');
				$t_count = 0;
				
				foreach ($t_configured_boxes as $t_title => $t_id) {
					if ((int) $t_id != 0) {
						$t_count++;
						$t_column = (($t_count % 3) == 0) ? 3 : ($t_count % 3);
						array_push($t_boxes, array('id' => $t_id, 'title' => $t_title, 'column' => $t_column));
					}
				}
			}
		}
		
		return $t_boxes;
	}
	
	/**
	 * Sets a boxes position string of form "box=1&box=4&box=2"... etc.
	 * received from jqueryUI sortable serialize.
	 * 
	 * @param $p_position_string
	 * @return Boolean whether position has been set successfully
	 */
	static function set_boxes_position($p_position_string)
	{
		if(empty($p_position_string)){
			return false;
		}
		
		$t_position_string = str_replace("&box=", ",", $p_position_string);
		$t_position_string = str_replace("box=", "", $t_position_string);
		
		$t_current_user_id = auth_get_current_user_id();
		$t_result = false;
		$t_default_boxes_view = (ON == plugin_config_get('allow_default_boxes_view'));
		$t_custom_boxes_view = (ON == plugin_config_get('allow_custom_boxes_view'));
		
		$t_dashboard_table = '';
		
		if($t_default_boxes_view){
			$t_dashboard_table = plugin_table(self::TABLE_BOXES);
		} else {
			$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);
		}
		
		$t_column_positions = 'positions';
		$t_column_user_id = 'user_id';
		
		#if not already in db ==> insert		
		if(!DashboardDbAPI::dashoard_data_exists()){
			#insert
			$t_query = "INSERT INTO $t_dashboard_table 
						($t_column_user_id, $t_column_positions) 
						VALUES (".db_param().','.db_param().")";
					
			$t_result = db_query_bound($t_query, array($t_current_user_id, $t_position_string));
		} else {
			#update
			$t_query = "UPDATE $t_dashboard_table
						SET $t_column_positions = ".db_param()." 
				  		WHERE $t_column_user_id=".db_param();
			
			$t_result = db_query_bound($t_query, array($t_position_string, $t_current_user_id));
		}
		
		return $t_result != false;
	}
	
	/**
	 * Gets the custom box's visibility.
	 * 
	 * @param $p_box_id 
	 * @return Boolean
	 */
	static function get_custom_box_visibility($p_box_id)
	{
		if($p_box_id == 0){
			return false;
		}
		 
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_column = 'visible';
		
		$t_query = "SELECT $t_column FROM $t_dashboard_table 
					WHERE id = ".db_param();
					
		$t_result = db_query_bound($t_query, array($p_box_id));
		$t_result_array = db_fetch_array($t_result);
		
		$t_visible = true;
		
		if(!empty($t_result_array)) {
			$t_visible = (int) $t_result_array[$t_column];
		}
				
		return $t_visible;
	}
	
	/**
	 * Sets the custom box's visibility.
	 * 
	 * @param $p_box_id int
	 * @param $p_visible Boolean
	 * @return Boolean whether visibility has been set successfully
	 */
	static function set_custom_box_visibility($p_box_id, $p_visible)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_column = 'visible';
		
		#if not already in db ==> insert
		if(!DashboardDbAPI::custom_box_exists_with_id($p_box_id)){
			#insert
			$t_query = "INSERT INTO $t_dashboard_table 
						(user_id, $t_column) 
						VALUES (".db_param().','.db_param().")";
					
			$t_result = db_query_bound($t_query, array($t_current_user_id, $p_visible));
		} else {
			#update
			$t_query = "UPDATE $t_dashboard_table
						SET $t_column = ".db_param()." 
				  		WHERE id=".db_param();
			
			$t_result = db_query_bound($t_query, array($p_visible, $p_box_id));
		}
		
		return $t_result != false;
	}
	
	/**
	 * Gets the custom box's filter id (default) or filter string 
	 * (if $p_get_as_string == true).
	 * 
	 * @param $p_box_id int
	 * @param $p_get_as_name Boolean
	 * @return int
	 * @return string
	 */
	static function get_custom_box_filter($p_box_id, $p_get_as_name = false)
	{
		if($p_box_id == 0){
			return false;
		}
		 
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_column = 'filter_id';
		
		$t_query = "SELECT $t_column FROM $t_dashboard_table 
					WHERE id = ".db_param();
					
		$t_result = db_query_bound($t_query, array($p_box_id));
		$t_result_array = db_fetch_array($t_result);
		
		$t_filter_id = -1;
		
		if(!empty($t_result_array)) {
			$t_filter_id = $t_result_array[$t_column];
			$t_return_value = $t_filter_id;
			
			if($p_get_as_name){
				$t_stored_queries_arr = filter_db_get_available_queries();
				$t_return_value = $t_stored_queries_arr[$t_filter_id];
				print_r($t_return_value);
			}
		}
		
		return $t_return_value;
	}

	/**
	 * Gets the box id of the box with given parameters.
	 * 
	 * @param $p_user_id
	 * @param $p_title
	 * @param $p_filter_id
	 * 
	 * @return int Boolean
	 */
	static function get_custom_box_id($p_user_id, $p_title, $p_filter_id)
	{
		if(empty($p_title) || $p_filter_id == 0 || $p_user_id == 0){
			return false;
		}
		
		$t_id = false;
		
		$t_column_filter_id = 'filter_id';
		$t_column_user_id = 'user_id';
		$t_column_title = 'title';
		$t_column_id = 'id';
		
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_query = "SELECT $t_column_id FROM $t_dashboard_table 
					WHERE $t_column_user_id=" . db_param() . 
					" AND $t_column_title=".db_param() . 
					" AND $t_column_filter_id=" . db_param();
					
		$t_result = db_query_bound($t_query, array($p_user_id, $p_title, $p_filter_id));
		
		if($t_result != false){
			$t_result_array = db_fetch_array($t_result);
			$t_id = $t_result_array[$t_column_id];
		}
		
		return $t_id;
	}
	
		/**
	 * Gets the data of the custom box with given id.
	 * 
	 * @param $p_box_id
	 * 
	 * @return Array
	 */
	static function get_custom_box_data($p_box_id)
	{
		if($p_box_id == 0){
			return false;
		}
		
		$t_current_user_id = auth_get_current_user_id();
		$t_column_all = '*';
		$t_column_user_id = 'user_id';
		$t_column_id = 'id';
		
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
		$t_query = "SELECT $t_column_all FROM $t_dashboard_table 
					WHERE $t_column_user_id=" . db_param() . 
					" AND $t_column_id=".db_param();
					
		$t_result = db_query_bound($t_query, array($t_current_user_id, $p_box_id));
		$t_result_array = db_fetch_array($t_result);
		
		return $t_result_array;
	}

	/**
	 * Gets the alredy positioned custom boxes of the current user.
	 * 
	 * @return array
	 */
	static function get_positioned_custom_boxes_data()
	{		
		$t_boxes = array();
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);
		$t_column_positions = 'positions';
		$t_column_user_id = 'user_id';
		
		if(self::user_has_custom_boxes()){
			$t_query = "SELECT $t_column_positions FROM $t_dashboard_table 
						WHERE $t_column_user_id = ".db_param();
						
			$t_result = db_query_bound($t_query, array($t_current_user_id));
			
			if($t_result != false) {
				$t_result_array = db_fetch_array($t_result);
				$t_position_string = $t_result_array[$t_column_positions];
				
				if(!empty($t_position_string)) { # position string set => use it!
					$t_boxes = self::_boxes_data_from_position_string($t_position_string);
				} else { # string not set => return boxes as written in creation order from custom boxes table
					$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
					$t_column_title = 'title';
					$t_column_id = 'id';
					
					$t_query = "SELECT $t_column_title, $t_column_id FROM $t_dashboard_table 
								WHERE $t_column_user_id = ".db_param();
						
					$t_result = db_query_bound($t_query, array($t_current_user_id));
					
					if($t_result != false) {
						$t_count = 0;
						
						foreach ($t_result as $t_box) {
							$t_count++;
							$t_column = (($t_count % 3) == 0) ? 3 : ($t_count % 3);
							$t_title = $t_box['title'];
							$t_id = $t_box['id'];
							array_push($t_boxes, array('id' => $t_id, 'title' => $t_title, 'column' => $t_column));
						}		
					}
				}
			}
		}
		
		return $t_boxes;
	}
	
	/**
	 * Returns a boxes data array 
	 * with one item is {id => ..., name => ..., column => ...}
	 * 
	 * @param String $p_string
	 */
	static function _boxes_data_from_position_string($p_string, $p_default_column = 1)
	{
		$t_boxes_position_array = explode(',', $p_string);
		$t_boxes = array();
		
		foreach ($t_boxes_position_array as $t_data) {
			$t_box_data_array = explode(':', $t_data);
			$t_box_name = $t_box_data_array[0];
			$t_box_id = $t_box_data_array[1];
			$t_column = isset($t_box_data_array[2]) ? $t_box_data_array[2] : $p_default_column;
			
			if ((int) $t_box_id != 0) {
				array_push($t_boxes, array('id' => $t_box_id, 'title' => $t_box_name, 'column' => $t_column));
			}
		}
		
		return $t_boxes;
	}
	
	/**
	 * Creates a custom box or updates it if already existent and the box id != 0.
	 * If box id not specified and box exists alredy an error message is provided, because
	 * the user tries to create an alredy existing box again. 
	 * Returns false if no success. 
	 * Returns the html of the box if created successfully
	 * Returns true if successfully updated 
	 * 
	 * @param $p_title
	 * @param $p_filter_id 
	 * @param $p_box_id = 0
	 * @param $p_visible = 1
	 * 
	 * @return Array
	 */
	static function save_custom_box($p_title, $p_filter_id, $p_box_id = 0, $p_visible = 1)
	{		
		$t_result = "";
		$t_saved = false;
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
			
		$t_column_id = 'id';
		$t_column_user_id = 'user_id';
		$t_column_filter_id = 'filter_id';
		$t_column_title = 'title';
		$t_column_visible= 'visible';
		
		# box does not exist so far for current user
		if($p_box_id == 0 && $p_filter_id != null && !self::custom_box_exists($p_title, $p_filter_id)) {	
			# save box
			$t_query = "INSERT INTO $t_dashboard_table 
						($t_column_user_id, $t_column_filter_id, $t_column_title) 
						VALUES (" . db_param() . ',' . db_param() . ','. db_param() . ")";
					
			$t_query_result = db_query_bound($t_query, array($t_current_user_id, $p_filter_id, $p_title));
			
			if($t_query_result != false){
				$t_saved = true;
				$t_box_id = self::get_custom_box_id($t_current_user_id, $p_title, $p_filter_id);
				
				# --------- add box data to position table -------------------------------------
				$t_dashboard_table_positions = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);
				$t_column_positions = 'positions';
				$t_query = "SELECT $t_column_positions FROM $t_dashboard_table_positions 
						WHERE $t_column_user_id = ".db_param();
						
				$t_result = db_query_bound($t_query, array($t_current_user_id));
			
				if($t_result != false) {
					$t_result_array = db_fetch_array($t_result);
					$t_position_string = $t_result_array[$t_column_positions];
				
					if(!empty($t_position_string)) { # position string set => use it!
						$t_position_string .= ",$p_title:$t_box_id";
						
						# update positions string in table
						$t_query = "UPDATE $t_dashboard_table_positions
						SET $t_column_positions = ".db_param()." 
				  		WHERE $t_column_user_id=".db_param();
			
						$t_result = db_query_bound($t_query, array($t_position_string, $t_current_user_id));
					}
				}
				#--------------------------------------------------------------------------------
				
				
				if($t_box_id != false){
					# create box html to return and insert box via JavaScript
					$t_box = self::get_custom_box_data($t_box_id);
					$t_result = DashboardPrintAPI::get_custom_box_html($t_box);
				}
			}
		} else if($p_box_id != 0 && $p_filter_id != null && self::custom_box_exists_with_id($p_box_id)){
			#box exists for current user and should be changed
			# update box
			$t_query = "UPDATE $t_dashboard_table
						SET $t_column_filter_id=".db_param().", $t_column_title=" . db_param() . ", $t_column_visible=" . db_param() .
				  		" WHERE $t_column_user_id=".db_param() . " AND $t_column_id=". db_param();
			
			$t_query_result = db_query_bound($t_query, array($p_filter_id, $p_title, $p_visible, $t_current_user_id, $p_box_id));
			$t_updated = (db_num_rows($t_query_result) > 0 || $t_query_result != false);
			
			# create return html array
			if($t_updated){
				$t_saved = true;
				$t_box_id = self::get_custom_box_id($t_current_user_id, $p_title, $p_filter_id);
				
				if($t_box_id != false){
					$t_box = self::get_custom_box_data($p_box_id);
					if($t_box != false){
						$t_result = DashboardPrintAPI::get_custom_box_html($t_box);
					}
				}
			}
						
		} else {
			# user tries to create existing box again => already available error provided
			# or user wats to edit a box so that it is the same as another box which is already existent
			$t_result = DashboardPrintAPI::get_already_available_box_message_html($p_title, $p_filter_id);			
		}
		
		return array('saved' => $t_saved, 'html' => $t_result);
	}
	
	/**
	 * Deletes the box with given id if available for current user.
	 * 
	 * @param $p_box_id
	 * 
	 * @return boolean deleted
	 */
	static function delete_custom_box($p_box_id)
	{
		$t_deleted = false;
		
		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES);
			
		$t_column_id = 'id';
		$t_column_user_id = 'user_id';
		
		if(self::custom_box_exists_with_id($p_box_id)){
			
			$t_query = "DELETE FROM $t_dashboard_table 
						WHERE $t_column_id = " . db_param() . 
						" AND $t_column_user_id = " . db_param();
			
			$t_query_result = db_query_bound($t_query, array($p_box_id, $t_current_user_id));
			$t_deleted = (db_num_rows($t_query_result) > 0 || $t_query_result != false);
		}
		
		if (!self::user_has_custom_boxes()) {
			self::delete_custom_boxes_positions();
		}
		
		return $t_deleted;
	}
	
	/**
	 * Deletes the custom boxes positions record for the current user.
	 * Should be called when all boxes are deleted to ensure the right position 
	 * of initial custom boxes.
	 * 
	 * @return boolean deleted
	 */
	static function delete_custom_boxes_positions()
	{
		$t_deleted = false;

		$t_current_user_id = auth_get_current_user_id();
		$t_dashboard_table = plugin_table(self::TABLE_CUSTOM_BOXES_POSITIONS);

		$t_column_user_id = 'user_id';

		$t_query = "DELETE FROM $t_dashboard_table 
					WHERE $t_column_user_id = " . db_param();

		$t_query_result = db_query_bound($t_query, array($t_current_user_id));
		$t_deleted = (db_num_rows($t_query_result) > 0 || $t_query_result != false);

		return $t_deleted;
	}
	
	/**
	 * Saves the configured initial custom boxes for the current user
	 */
	static function create_initial_custom_boxes()
	{
		if (!self::inital_custom_boxes_available()) {
			return;
		}
		
		$t_initial_custom_boxes_string = plugin_config_get('initial_custom_boxes');
		$t_boxes = explode(',', $t_initial_custom_boxes_string);
		
		foreach ($t_boxes as $t_box) {
			
			$t_data = explode(':', $t_box);
			$t_title = $t_data[0];
			$t_filter_id = $t_data[2];
			
			self::save_custom_box($t_title, $t_filter_id);
		}
	}
	
	/**
	 * Returns whether there are initial custom boxes configured.
	 * 
	 * @return boolean
	 */
	static function inital_custom_boxes_available()
	{
		$t_initial_custom_boxes_string = plugin_config_get('initial_custom_boxes');
		return $t_initial_custom_boxes_string != "";
	}  
}
