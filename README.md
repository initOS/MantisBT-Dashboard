MantisBT-Dashboard
==================

**Dashboard Plugin for Mantis Bug Tracker**

Individually per user customizable dashboard for MantisBT bug boxes and custom views.

This plugin gives you a nice overview over everything what goes on in Mantis. It is a must-have.

We have this features:

- complete jQuery Interface
- Classic Mode or Filter-Used Mode
- per user configurable
- drag & drop position
- add, edit, remove and hide boxes

Version
-------

0.4.3.

Dependencies
------------

- MantisCore 1.2.0 
- jQuery 1.9.1 (project link: [https://github.com/initOS/jquery](https://github.com/initOS/jquery "jQuery Plugin for MantisBT"))

Features
----------

There are two possibilities to use the dashboard:
 
__A) with customizable default boxes:__
 
- customizable project filter via select box in each bug box  
- customizable visibility per bug box  
- customizable position per bug box (via jQueryUI sortable)  
		
__B) with custom boxes:__
 
- create own boxes by naming and choosing a custom filter  
- customizable visibility per bug box  
- customizable position per bug box (via jQueryUI sortable)  

Installation
------------

1. Download the files and place the folder `Dashboard` in the directory `plugins/` of your Mantis installation. 
2. With administrator privileges, go to the page "*Manage*" / "*Manage Plugins*"
3. In the list "*Available Plugins*", you should see "*Dashboard 0.4.3*": click the install link for the plugin.

Configuration
--------------

You can choose between using the **Classic Mode** and the **Custom Filter-Used Mode**. Administrator level access is needed to manage the configuration. 

1. After installation, the plugin should appear in the "*Installed Plugins*" list: click on the name to manage the configuration.
2. You see a simple switch between *Default Boxes* and *Custom Boxes*.
5. After submitting the settings, you can use the Dashboard page with the specified boxes.

As default configuration the plugin uses the *Custom Filter-Used Mode*.

Data tables
------------

_Default boxes:_

    mantis_plugin_dashboard_boxes_table

 
_Custom boxes:_

	mantis_plugin_dashboard_custom_boxes_table
	mantis_plugin_dashboard_custom_boxes_positions_table
 
References
-----------

*Icons by gentleface: http://gentleface.com/free_icon_set.html*

*This plugin idea comes from: https://github.com/rolfkleef/mantisbt-dashboard,  thanks for that, we hope you enjoy!*

