function refreshCities() {
	var citiesPerPage = 0;
	if ($(document).width() < 992) citiesPerPage = 12;
	else citiesPerPage = 24;

	document.getElementById('tabs').innerHTML = '';
	document.getElementById('cities').innerHTML = '';

	for (var i = 0, page = 0; i < cities.length; i++, page++) {
		document.getElementById('tabs').innerHTML += '\t\t\t<li class="page-item" id="cityPage' + (i+1) + 'Button"><a class="page-link">' + (page+1) + '</a></li>\n';

		var string =
			'\t\t<div class="container" id="cityPage'+ (i+1) +'">\n' +
				'\t\t\t<div class="row">\n';

		for (var j = 0; j < citiesPerPage && (j + i) < cities.length; j++) {
			string +=
				'\t\t\t\t<button class="col-6 col-md-3">\n' +
					'\t\t\t\t\t<a href="' + (rootURL + '/categories/' + cities[i + j].sno) + '">\n' +
						'\t\t\t\t\t\t<img class="img-thumbnail" src="' + rootURL + '/img/cities/' + (cities[j + i].photo) + '" alt="' + (cities[j + i].city) + '"><br>\n' +
						'\t\t\t\t\t\t<span class="">' + capitalize(cities[j + i].city) + '</span>\n' +
					'\t\t\t\t\t</a>\n' +
				'\t\t\t\t</button>\n';
		}
		i += citiesPerPage-1;

		document.getElementById('cities').innerHTML += string +
				'\t\t\t</div>\n' +
			'\t\t</div>\n';
	}

	// hiding all city pages except first
	$('body #cities .container:not(#cityPage1)').hide('fast');

	// making the first tab active
	$('#cityPage1Button').toggleClass('active');

	// adding animations to cities
	$('body #cities button > a').hover(function() {
		$('body #cities button > a').css({
			'opacity': .1,
			'transitionDuration': '500ms'
		});
		$(this).css({
			'opacity': 1,
			'transitionDuration': '500ms'
		});
	}, function() {
		$('body #cities button > a').css({
			'opacity': 1,
			'transitionDuration': '500ms'
		});
	});

	// adding functionality to pagers
	$('body #tabs li').click(function() {
		var tab = $(this).attr('id').replace('cityPage', '').replace('Button', '');
		var prev = $('body #tabs .active').attr('id').replace('cityPage', '').replace('Button', '');
		if (prev !== tab) {
			$('#cityPage' + prev + 'Button').toggleClass('active');
			$('#cityPage' + tab + 'Button').toggleClass('active');
			$('#cityPage' + prev).hide('fast', function() {
				$('#cityPage' + tab).show('fast');
			});
		}
	});
}

function selectCities() {
	cities = Array();
	var search = $('body #search #searchInput').val().toLowerCase().trim();
	if (search.length === 0) {
		cities = allCities;
	} else {
		var exp = new RegExp('[A-z0-9]*' + search + '[A-z0-9\.]*');
		for (var i = 0, index = 0; i < allCities.length; i++) {
			if (exp.test(allCities[i].city)) cities[index++] = allCities[i];
		}
	}
	refreshCities();
}

$(function() {
	// loading the cities
	refreshCities(allCities);

	var windowWidth = $(window).width();
	$(window).resize(function() {
		if (windowWidth !== $(window).width()) {
			windowWidth = $(window).width();
			refreshCities();
		}
	});
});