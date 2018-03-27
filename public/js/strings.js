function capitalize(string) {
	return (string.charAt(0).toUpperCase() + string.substr(1));
}

function sentenceCase(string) {
	var newString = string.charAt(0).toUpperCase();

	if (1 < string.length) newString += string.charAt(1);
	else return newString;
	if (2 < string.length) newString += string.charAt(2);
	else return newString;

	for (var i = 3; i < string.length; i++) {
		if (string.charAt(i-1) === ' ' && string.charAt(i-2) === '.')
			newString += string.charAt(i).toUpperCase();
		else
			newString += string.charAt(i);
	}

	return newString;
}