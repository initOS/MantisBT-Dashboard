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
 * MantisBT Dashoard Plugin - Customize each bug boxes filter project, visibility & position.
 * @author InitOS/Paul Götze <paul.goetze@initos.com>
 */
class DashboardPlugin extends MantisPlugin
{
	/*
	 * implementation of abstract plugin base class function
	 */
	public function register()
	{
		$this->name = "Dashboard";				 				#itle of plugin
		$this->description = plugin_lang_get('description');	#description text
		$this->page = 'config_page';							#configure page file

		$this->version = '0.4.2';     	# Plugin version string
		$this->requires = array(    	# Plugin dependencies, array of basename => version pairs
            'MantisCore' => '1.2.0',  	# Should always depend on an appropriate version of MantisBT
            'jQuery' => '1.6.2',		# jQuery for AJAX calls (including jQueryUI 1.8.16)
            							# plugin-link: https://github.com/tkalbitz/jquery
		);

		$this->author = 'InitOS GmbH & Co. KG <Paul Götze,Markus Schneider>';         # Author/team name
		$this->contact = 'info@initos.com';     # Author/team e-mail address
		$this->url = '';            # Support webpage
	}
	
	/**
	 * creates table for plugin data (plugin_table_dashboard_boxes)
	 * @return array (table create SQL String)
	 */
	public function schema()
	{
		return array(
			array('CreateTableSQL', array(plugin_table('boxes'), " 
					id					I 		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
					user_id				I 		NOTNULL UNSIGNED,
					show_box_1			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_1_filter_id		C(250)	NOTNULL DEFAULT \" '0' \",
					show_box_2			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_2_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_3			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_3_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_4			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_4_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_5			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_5_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_6			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_6_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_7			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_7_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_8			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_8_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					show_box_9			L 		NOTNULL UNSIGNED DEFAULT 1,
					box_9_filter_id		C(250) 	NOTNULL DEFAULT \" '0' \",
					positions			C(250) 	NOTNULL DEFAULT \"\"
		 		")
			),
		 		array('CreateTableSQL', array(plugin_table('custom_boxes'), "
		 			id					I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
		 			title				C(100)	NOTNULL DEFAULT \" 'untitled' \",
		 			user_id				I		NOTNULL UNSIGNED, 
		 			filter_id			I		NOTNULL UNSIGNED,
		 			visible				L 		NOTNULL UNSIGNED DEFAULT 1
		 		")
			), 
				array('CreateTableSQL', array(plugin_table('custom_boxes_positions'), "
		 			id					I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
		 			user_id				I		NOTNULL UNSIGNED, 
		 			positions			C(1000)  NOTNULL DEFAULT \"\"
		 		")
			)
		);
	}

	/**
	 * plugin config
	 */
	function config() {
		return array (
			'allow_custom_boxes_view' => ON,
			'allow_default_boxes_view' => OFF,
		);
	}
	
	/**
	 * hooks events
	 */
	public function hooks() {
        return array(
            'EVENT_MENU_MAIN_FRONT' => 'add_to_main_menu',
            'EVENT_LAYOUT_RESOURCES' => 'resources'
        );
    }
	
	/**
	 * adds dashboard link to the main menu
	 * @param $p_event
	 */
	function add_to_main_menu( $p_event ) {
        return array (
        	'<a href="' . plugin_page( 'dashboard' ) . '">' . plugin_lang_get( 'menuname' ) .'</a>'
        );
    }
	
	/**
	 * init - requires the api files
	 */
	public function init()
	{
		#require_once 'api/dashboard_print_api.php';
		require_once 'api/dashboard_db_api.php';
	}
	
	/**
	 * loads js and css resources
	 */
	public function resources( $p_event ) {
		
		$resources = '<script type="text/javascript" src="' . plugin_page('dashboard-js.php') . '"></script> '.
					 '<link rel="stylesheet" type="text/css" href="' . plugin_file("dashboard.css") . '" />';
					 
		return $resources;
	}
}


