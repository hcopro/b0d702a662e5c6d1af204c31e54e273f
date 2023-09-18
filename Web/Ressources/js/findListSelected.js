// Vernon's function to set table data-object based on value of select[id]
function selectChange(id1) {
  const OBJECTSELECT 	= document.getElementById(id1);
  var selectedValue		= OBJECTSELECT.value;
  const OBJECTSGRID 	= document.getElementById("objectsGrid");
  OBJECTSGRID.setAttribute("data-object", selectedValue);
}
function checkboxChange(id2, mannual = false) {
  	const theCheckbox	= document.getElementById(id2);
  	var checkboxStatus 	= theCheckbox.checked;
  	// Find which row this element is in.
  	var tableRow 		= theCheckbox.closest(".checkbox");
  	var hrefAttr 		= '';
	var getClass 		= '';
  	// Add a class to the row if theCheckbox is checked
  	if (mannual) { // archive, read, restaure
    	if ($('input#'+id2).parent().attr('class').indexOf('archive') >= 0) {
    		getClass = '.archive';
    	} else if ($('input#'+id2).parent().attr('class').indexOf('read') >= 0) {
    		getClass = '.read';
    	} else if ($('input#'+id2).parent().attr('class').indexOf('restaure') >= 0) {
    		getClass = '.restaure';
    	}
    	hrefAttr = $('#multi-select__select-all').parent().find('a' + getClass).attr('href');
    }
  	if (checkboxStatus) {
	    tableRow.classList.toggle("selected"); // Tout sans exception
	    if (mannual) {
	    	hrefAttr += ',' +id2;
		    $('#multi-select__select-all').parent().find('a' + getClass).attr('href', hrefAttr);
		    $('#multi-select__select-all').parent().find('a' + getClass).removeClass('disabled');
	    } else {
		    $.each($(theCheckbox).parent().find('a'), function() {
		    	let valueHref = '';
			    if ($(this).attr('class').indexOf('archive') >= 0) {
			    	valueHref = $(this).attr('href') + getIdsArchived ;
			    } else if ($(this).attr('class').indexOf('read') >= 0) {
			    	valueHref = $(this).attr('href') + getIdsRead ;
			    } else if ($(this).attr('class').indexOf('restaure') >= 0) {
			    	valueHref = $(this).attr('href') + getIdsRestaure ;
			    }
			    $(this).attr('href', valueHref);
			    if (valueHref.substring(valueHref.indexOf('=') + 1).trim()) {
	    			$(this).removeClass('disabled');
			    }
			});
	    }
  	} else {
	    tableRow.classList.remove("selected");
	    $(theCheckbox).parent().find('a').addClass('disabled');
	    if (mannual) {
			hrefAttr = hrefAttr.replace(','+id2, '');
		    $('#multi-select__select-all').parent().find('a' + getClass).attr('href', hrefAttr); // Enlevez l'ID unchecked
	    	if(!hrefAttr.split(idModules).pop().trim())  {
	    		$("#multi-select__select-all").parent().find('a' + getClass).addClass('disabled');
	    	}
	    } else {
	    	$.each($("#multi-select__select-all").parent().find('a'), function() {
	    		$(this).attr('href', $(this).attr('href').substring(0,$(this).attr('href').indexOf('=')+1));
	    	});
	    }
  	}
}
document.onreadystatechange = function () {
  if (document.readyState === "complete") {
    // Set data-object on page load completed
    initApplication();
  }
};
function initApplication() {
  const MATCHES = document.querySelectorAll("select[id]");
  MATCHES.forEach(function (match) {
    // Trigger the initial change
    selectChange(match.id);
    // Add an event listener so we don't need to explicitly add an onchange to each <select> in the HTML
    match.addEventListener("change", function (e) {
      selectChange(match.id);
    });
  });
  const MATCHINGCHECKBOXES = document.querySelectorAll(
    'tbody input[type="checkbox"]'
  );
  MATCHINGCHECKBOXES.forEach(function (aCheckbox) {
    // Trigger initial change
    checkboxChange(aCheckbox.id);
    // Add an event listener
    aCheckbox.addEventListener("change", function (e) {
      checkboxChange(aCheckbox.id);
    });
  });
}
// Check / Uncheck all checkboxes in the <table>
if ($('#multi-select__select-all').length > 0) {
	document.getElementById("multi-select__select-all").onclick = function () {
	  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
	  for (var checkbox of checkboxes) {
	    checkbox.checked = this.checked;
	    checkboxChange(checkbox.id);
	  }
	};
}