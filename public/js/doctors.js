function refreshDoctors() {
	var doctorsPerPage = 0;
	if ($(document).width() < 992) doctorsPerPage = 6;
	else doctorsPerPage = 12;

	document.getElementById('tabs').innerHTML = '';
	document.getElementById('doctors').innerHTML = '';

	for (var i = 0, page = 0; i < doctors.length; i++, page++) {
		document.getElementById('tabs').innerHTML += '\t\t\t<li class="page-item" id="doctorPage' + (i+1) + 'Button"><a class="page-link">' + (page+1) + '</a></li>\n';

		var string = '\t\t<div class="row" id="doctorPage' + (i+1) + '">\n';

		for (var j = 0; j < doctorsPerPage && (j + i) < doctors.length; j++) {
			string +=
				'\t\t\t<a href="#" class="col-lg-4 col-md-6 col-12 doctor">\n' +
				'\t\t\t\t<div class="card h-100">\n' +
				'\t\t\t\t\t<img class="card-img-top" src="' + rootURL + '/img/cities/' + doctors[j + i].photo + '" alt="">\n' +
				'\t\t\t\t\t<div class="card-body text-justify">\n' +
				'\t\t\t\t\t\t<h3 class="card-title">' + capitalize(doctors[j + i].doctor) + '</h3>\n' +
				'\t\t\t\t\t\t<h5>Category: ' + capitalize(doctors[j + i].category) + '</h5>\n' +
				'\t\t\t\t\t\t<h5>City: ' + capitalize(doctors[j + i].city) + '</h5>\n' +
				'\t\t\t\t\t\t<p class="card-text">\n' +
				'\t\t\t\t\t\t\t' + sentenceCase(doctors[j + i].description) + '\n' +
				'\t\t\t\t\t\t</p>\n' +
				'\t\t\t\t\t</div>\n' +
				'\t\t\t\t</div>\n' +
				'\t\t\t</a>\n';
		}
		i += doctorsPerPage-1;

		string += '\t\t</div>\n';

		document.getElementById('doctors').innerHTML += string;
	}

	// hiding all doctor pages except first
	$('body #doctors .container:not(#doctorPage1)').hide('fast');

	// making the first tab active
	$('#doctorPage1Button').toggleClass('active');

	// adding functionality to pagers
	$('body #tabs li').click(function() {
		var tab = $(this).attr('id').replace('doctorPage', '').replace('Button', '');
		var prev = $('body #tabs .active').attr('id').replace('doctorPage', '').replace('Button', '');
		if (prev !== tab) {
			$('#doctorPage' + prev + 'Button').toggleClass('active');
			$('#doctorPage' + tab + 'Button').toggleClass('active');
			$('#doctorPage' + prev).hide('fast', function() {
				$('#doctorPage' + tab).show('fast');
			});
		}
	});
}

$(function() {
	// loading the doctors
	refreshDoctors();

	var windowWidth = $(window).width();
	$(window).resize(function() {
		if (windowWidth !== $(window).width()) {
			windowWidth = $(window).width();
			refreshDoctors();
		}
	});
});