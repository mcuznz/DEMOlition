chosenCostume = 0;

function getCostumeColors(costumeData) {
	var colorsRaw = costumeData.match(/\b[0-9a-fA-F]{8}\b/gi);
	var skinColor = $.trim(colorsRaw[0]);
	colorsRaw.shift();

	newColors = new Array();
	
	for (i=0; i<colorsRaw.length; i++) {
		if ($.inArray($.trim(colorsRaw[i]), newColors) == -1) {
			newColors[newColors.length] = $.trim(colorsRaw[i]);
		}
	}

	colorHtml = "<div class='cp skin' style='background:" + abgr2rgba(skinColor) + ";'></div>";
	
	//alert (colorsRaw);
	for (i=0; i<newColors.length; i++) {
		colorHtml = colorHtml + "<div class='cp' style='background:" + abgr2rgba(newColors[i]) + ";'><!-- " + newColors[i] + " --></div>";
	}
	
	return colorHtml;
}

function abgr2rgba (abgr) {
	abgr = $.trim(abgr);
	if (abgr.length == 6) {
		// just bgr
		return '#' + abgr.substring(4,6) + abgr.substring(2,4) + abgr.substring(0,2);
	} else if (abgr.length == 8) {
		// abgr
		return '#' + abgr.substring(6,8) + abgr.substring(4,6) + abgr.substring(2,4);
	} else {
		return '#000000';
	}
}

function chooseSelector() {
	$('div.cs_child').removeClass('selected');
}

function populateSelectors() {
	data = $('#costume_data');
	if (data.children().length > 1) {
		$('#costume_selectors').html('');
		data.children().each( function(index) {
			
			colorSelector = "<div class='cs_child' id='cs_child" + index + "'>" +
				getCostumeColors($(this).html()) + "</div>";
				$('#costume_selectors').html($('#costume_selectors').html() + colorSelector);
			
		} );
		
		$('div.cs_child:first-child').addClass('selected');
		$('div.cs_child').click( function() {
			$('div.cs_child').removeClass('selected');
			$(this).addClass('selected');
		})
		
		
	} else {
		// only one costume
		$('#costume_selectors').html("<em>Only 1 costume found in source file, no selection necessary</em>");
	}
	
	// Other startup tasks
	$('#show_advanced').click( function() {
		$(this).css('display', 'none');
		$('#advanced_wrapper').css('display', 'block');
	});
	$('#step2_submit').click( function() {
		createCohDemo();
	});
	$("#output").focus(function() {
		var $this = $(this);
		$this.select();
	
		// Work around Chrome's little problem
		$this.mouseup(function() {
			// Prevent further mouseup intervention
			$this.unbind("mouseup");
			return false;
		});
	});
	$('#step3_tweak').click( function() {
		$('#output').html('');
		$('#step2_wrapper').css('display', 'block');
		$('#step3_wrapper').css('display', 'none');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	$('#step3_import').click( function() {
		window.location.href = "/";	
	});
}

function createCohDemo() {
	if ($('div.cs_child.selected').length == 0) {
		which = 0;
	} else {
		which = $('div.cs_child.selected').attr('id').replace('cs_child', '');
	}
	costume = $('#costume_data_'+which).html();
	
	// Entity seems to just be a random number
	entity = 33;
	if ($('#entityid').val()) entity = parseInt($('#entityid').val());

	costume = costume.replace(/\!EN\!/g, entity.toString());
	
	// 0 = map, 1 = player pos, 2 = player YPR
	playerloc = $('#location').val().split('||');
	playerxyz = playerloc[1].split(' ');
	playerpyr = playerloc[2].split(' ');
	
	// clean these up
	playerxyz[0] = parseFloat(playerxyz[0]);
	playerxyz[1] = parseFloat(playerxyz[1]);
	playerxyz[2] = parseFloat(playerxyz[2]);
	playerpyr[0] = parseFloat(playerpyr[0]);
	playerpyr[1] = parseFloat(playerpyr[1]);
	playerpyr[2] = parseFloat(playerpyr[2]);
	
	// get costume scale - ranges fro -33.00 to 25.00 (from what I've seen)
	scale = parseFloat(costume.match(/\b[0-9]{1,2}.[0-9]{6}\b/));

	// a character with a scale of 0.0 is approx 6 feet high.
	// place the camera slightly below that.
	charheight = (scale+100) / 100 * 6;
	camheight = charheight * 0.7;
	
	camfocus = [playerxyz[0], playerxyz[1] + camheight, playerxyz[2]];

	// sensible default of 14? Try this out with a few differently-sized characters
	camdist = 14
	if ($('#camdist').val()) camdist = parseInt($('#camdist').val());
	
	spindir = parseInt($('#spindir').val());
	
	camtype = $('#camtype').val();
	campitchoffset = 0;
	camheightoffset = 0;
	if (camtype.indexOf('down') != -1) {
		// we have a downward looking camera - create offsets accordingly
		camheightoffset = 5;
	} else if (camtype.indexOf('up') != -1) {
		camheightoffset = -3;
	}
	
	if (camheightoffset != 0) {
		// length of hypotenuse
		hypo = Math.sqrt(camdist*camdist + camheightoffset*camheightoffset);
		campitchoffset = Math.asin(camheightoffset / hypo);
	}

	camstartxyz = [playerxyz[0] + (camdist * Math.sin(playerpyr[1])),
				   playerxyz[1] + camheight + camheightoffset,
				   playerxyz[2] + (camdist * Math.cos(playerpyr[1]))];
	camstartpyr = [playerpyr[0] + campitchoffset, playerpyr[1], playerpyr[2]];
	
	// Begin output
	data = "1   0   Version 2\n0   0   Map " + playerloc[0] + "\n0   0   Time "+ $('#timeofday').val() +"\n";
	
	// set our initial cam position
	data = data + "0 CAM POS "+camstartxyz[0].toFixed(6)+" "+camstartxyz[1].toFixed(6)+" "+camstartxyz[2].toFixed(6)+
		"\n0 CAM PYR "+camstartpyr[0].toFixed(6)+" "+camstartpyr[1].toFixed(6)+" "+camstartpyr[2].toFixed(6)+"\n";
	
	// Now add the Character
	data = data + "0   "+entity+" Player\n0   "+entity+" NEW \"" + $('#charname').val() + "\"\n";
	data = data + costume;
	
	// Now a few things to set the character up
	data = data + "0 "+entity+" MOV "+ $('#stance').val() +" 0\n0 "+entity+" HP 100.00\n0 "+entity+" HPMAX 100.00\n"+
		"0 "+entity+" POS "+playerxyz[0].toFixed(6)+" "+playerxyz[1].toFixed(6)+" "+playerxyz[2].toFixed(6) + "\n"+
		"0 "+entity+" PYR "+playerpyr[0].toFixed(6)+" "+playerpyr[1].toFixed(6)+" "+playerpyr[2].toFixed(6) + "\n";

	// SKY command wastes time, let's give everything 5 seconds to settle in
	data = data + "5000 SKYFILE SKY 1 0 1.000000\n";
	
	// Ok, we're ready to start moving the camera around!
	if (camtype.indexOf('circle') != -1) {
		// value of 2 makes this take twice as long, etc
		speedlimiter = 4;
		
		step = Math.PI / (180 * speedlimiter) * spindir;
		
		for (i = 0; i <= (360 * speedlimiter); i++) {
			// camdist is radius
			newx = playerxyz[0] + (camdist * Math.sin(playerpyr[1]+(step*i)));
			newz = playerxyz[2] + (camdist * Math.cos(playerpyr[1]+(step*i)));
			data = data + "33 CAM POS "+newx.toFixed(6)+" "+camstartxyz[1].toFixed(6)+" "+newz.toFixed(6)+
				"\n0 CAM PYR "+camstartpyr[0].toFixed(6)+" "+(camstartpyr[1] + (step*i)).toFixed(6)+" "+camstartpyr[2].toFixed(6)+"\n";
			
		}
	}

	data = data + "5000 SKYFILE SKY 1 0 1.000000\n";
	
	$('#output').html(data);
	$.post('/updateDemos');
	$('#step2_wrapper').css('display', 'none');
	$('#step3_wrapper').css('display', 'block');

}
