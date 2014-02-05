/*
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

# @author InitOS GmbH & Co.KG
#  @author Paul Götze <paul.goetze@initos.com>
*/

jQuery( document ).ready(function() {
		
	// Adds box to available boxes fieldset
	addInitialBox = function (title, filterName, filterId) {
		if(filterExists(filterId)) {
			showError();
			return;
		} else {
			removeError();
		}
		
		box = buildBox(title, filterName, filterId);
		box.appendTo(jQuery('#available-boxes')).slideDown('fast');
		
		resetAddInputs();
		updateSerializationString();
	};
	
	// Checks wheter the given filter is already in use
	filterExists = function(filterId) {
		filters = getExistingFilters();
		exists = jQuery.inArray(parseInt(filterId), filters) != -1;
		
		return exists;
	}
	
	// Returns an array of the filters used in intial boxes
	getExistingFilters = function() {
		var filters = [];
		
		jQuery("#available-boxes > div").each(function() {
			filterId = parseInt(jQuery(this).data('filter-id'));
			filters.push(filterId);
		});
		
		return filters;
	}
	
	// Shows an error text
	showError = function() {
		jQuery("#add-error").text(pluginLangGet['filter_already_used']);
	}
	
	// Removes error text
	removeError = function() {
		jQuery("#add-error").text('');
	}
	
	// Returns the box with given parameters as jQuery object
	buildBox = function(title, filterName, filterId) {
		if(!title) {
			title = filterName;
		}
		
		boxCount = jQuery("#available-boxes > div").size() + 1;
		boxId = "box-" + boxCount;
		
		removeButton = "<span class='remove-inital-box-btn delete-icon'" +
						" data-box-id='#"+ boxId +
						"' value='-' />";
		
		box = jQuery("<div class='intial-box-item cf' style='display: none;' id='"+ boxId + "' " +
				"data-title='" + title + "' " +
				"data-filter-name='" + filterName + "' " +
				"data-filter-id='" + filterId + "'>" +
				"<span class='title'>" + title + "</span>" + 
				" (" + pluginLangGet['filter'] + ": " +  
				"<span class='filter'>" + filterName + ")</span>" +
				removeButton +
				"</div>");
		
		return box;
	}
	
	// Removes box from available boxes fieldset
	removeInitialBox = function (removeId) {
		jQuery(removeId).slideUp('fast', function() {
			jQuery(this).remove();
			updateSerializationString();			
		});
	};
	
	// Reset the inputs for adding a new initial box  
	resetAddInputs = function() {
		jQuery("#custom-filter-select option:eq(0)").attr("selected", "selected")
		jQuery("#create-box-title").val("").focus();
	};
	
	// On click 'Add' => add box to availables
	jQuery("#add-initial-box-btn").on('click', function(){
		boxTitle = jQuery("#create-box-title").val();
		filterName = jQuery("#custom-filter-select option:selected").text();
		filterId = jQuery("#custom-filter-select option:selected").val();
		addInitialBox(boxTitle, filterName, filterId);
	});
	
	// On click 'Remove' => remove box from availables
	jQuery(document).on("click", ".remove-inital-box-btn", function(){
		removeInitialBox(jQuery(this).data('box-id'));
	});
	
	// Creates the serialization string needed for saving the available intial boxes.
	// The serialization string is available as param when saving the config.
	updateSerializationString = function() {
		var boxes = [];
		
		jQuery("#available-boxes > div").each(function(index) {
			div = jQuery(this);
			title = div.data("title");
			
			filterName = div.data("filter-name");
			filterId = div.data("filter-id");
			
			boxes.push([title, filterName, filterId].join(":"));
		});
		
		var serialized = "";
		
		if(boxes.length > 0) {
			serialized = boxes.join(',');
		}
		
		jQuery("#boxes-serialization").val(serialized);
	};
	
	// Parses the  string to the html elements.
	createBoxesFromString = function(string) {
		if(string != "") {
			boxes = string.split(',');
			
			jQuery.each(boxes, function(index, box){
				values = box.split(':');
				addInitialBox(values[0], values[1], values[2]);
			});
		}
		
		updateSerializationString();
	}
	
	jQuery("#create-box-title").attr("placeholder", pluginLangGet["box_title_placeholder"]);
	createBoxesFromString(jQuery("#boxes-serialization").val());
});