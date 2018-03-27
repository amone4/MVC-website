function refreshCategories() {
	var categoriesPerPage = 0;
	if ($(document).width() < 992) categoriesPerPage = 12;
	else categoriesPerPage = 24;

	document.getElementById('tabs').innerHTML = '';
	document.getElementById('categories').innerHTML = '';

	for (var i = 0, page = 0; i < categories.length; i++, page++) {
		document.getElementById('tabs').innerHTML += '\t\t\t<li class="page-item" id="categoryPage' + (i+1) + 'Button"><a class="page-link">' + (page+1) + '</a></li>\n';

		var string =
			'\t\t<div class="container" id="categoryPage'+ (i+1) +'">\n' +
			'\t\t\t<div class="row">\n';

		for (var j = 0; j < categoriesPerPage && j + i < categories.length; j++) {
			string +=
				'\t\t\t\t<button class="col-6 col-md-3">\n' +
				'\t\t\t\t\t<a href="' + (rootURL + '/doctors/' + city + '/' + (categories[i + j]).sno) + '">\n' +
				'\t\t\t\t\t\t<img class="img-thumbnail" src="' + rootURL + '/img/categories/' + (categories[j + i].photo) + '" alt="' + (categories[j + i].category) + '"><br>\n' +
				'\t\t\t\t\t\t<span class="">' + ((categories[j + i].category).charAt(0).toUpperCase() + (categories[j + i].category).substr(1)) + '</span>\n' +
				'\t\t\t\t\t</a>\n' +
				'\t\t\t\t</button>\n';
		}
		i += categoriesPerPage-1;

		document.getElementById('categories').innerHTML += string +
			'\t\t\t</div>\n' +
			'\t\t</div>\n';
	}

	// hiding all category pages except first
	$('body #categories .container:not(#categoryPage1)').hide('fast');

	// making the first tab active
	$('#categoryPage1Button').toggleClass('active');

	// adding animations to categories
	$('body #categories button > a').hover(function() {
		$('body #categories button > a').css({
			'opacity': .1,
			'transitionDuration': '500ms'
		});
		$(this).css({
			'opacity': 1,
			'transitionDuration': '500ms'
		});
	}, function() {
		$('body #categories button > a').css({
			'opacity': 1,
			'transitionDuration': '500ms'
		});
	});

	// adding functionality to pagers
	$('body #tabs li').click(function() {
		var tab = $(this).attr('id').replace('categoryPage', '').replace('Button', '');
		var prev = $('body #tabs .active').attr('id').replace('categoryPage', '').replace('Button', '');
		if (prev !== tab) {
			$('#categoryPage' + prev + 'Button').toggleClass('active');
			$('#categoryPage' + tab + 'Button').toggleClass('active');
			$('#categoryPage' + prev).hide('fast', function() {
				$('#categoryPage' + tab).show('fast');
			});
		}
	});
}

function selectCategories() {
	categories = Array();
	var search = $('body #search #searchInput').val().toLowerCase().trim();
	if (search.length === 0) {
		categories  = allCategories;
	} else {
		var exp = new RegExp('[A-z0-9]*' + search + '[A-z0-9\.]*');
		for (var i = 0, index = 0; i < allCategories.length; i++) {
			if (exp.test(allCategories[i].name)) categories[index++] = allCategories[i];
		}
	}
	refreshCategories();
}

$(function() {
	// loading the categories
	refreshCategories();

	var windowWidth = $(window).width();
	$(window).resize(function() {
		if (windowWidth !== $(window).width()) {
			windowWidth = $(window).width();
			refreshCategories();
		}
	});
});