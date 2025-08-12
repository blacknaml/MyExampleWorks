function json_handler(data){
	var alert = 'alert';
	if(data.action == 0){
		alert = alert + ' alert-error';
	} else if(data.action == 2){
		alert = alert + ' alert-info';
	}

	if(data.type == 'failed'){
		alert = alert + ' alert-warning';
		$('<div></div>').attr('class', alert).html(data.content).hide().appendTo('#error_b').slideDown('slow').delay(10000).fadeOut();
	}
	if(data.type == 'redirect'){
		alert = alert + ' alert-warning';
		$('<div></div>').attr('class', alert).html(data.content).hide().appendTo('#error_b').slideDown('slow').delay(10000).fadeOut();
		setTimeout( function(){ window.location.href = data.url; }, 3000);
	}
	if(data.type == 'error'){
		alert = alert + ' alert-error';
		$('<div></div>').attr('class', alert).html(data.content).hide().appendTo('#error_b').slideDown('slow').delay(10000).fadeOut();
	}
	if(data.type == 'success'){
		alert = 'alert alert-success';
		$('<div></div>').attr('class', alert).html(data.content).hide().appendTo('#error_b').slideDown('slow').delay(7000).fadeOut();
	}
}

function getNumber(e) {
	return isNaN(e) ? 1 * e.replace(/,/g, "") : e * 1
}

function formatMoney(e) {
	if (!e) return e;
	e = e.toString().replace(/\$|\,/g, "");
	if (isNaN(e)) e = "0";
	sign = e == (e = Math.abs(e));
	e = Math.floor(e * 100 + .50000000001);
	cents = e % 100;
	e = Math.floor(e / 100).toString();
	if (cents < 10) cents = "0" + cents;
	for (var t = 0; t < Math.floor((e.length - (1 + t)) / 3); t++) e = e.substring(0, e.length - (4 * t + 3)) + "," + e.substring(e.length - (4 * t + 3));
	return (sign ? "" : "-") + e
}