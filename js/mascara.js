function calcula_mascara(val, mas) {
	var r = "";
	for (var i = 0, j = 0; i < mas.length && j < val.length; i++) {
		var cm = mas.charAt(i);
		var cv = val.charAt(j);
		var nv = val.charCodeAt(j);
		switch (cm) {
			case "0":
			{
				if(nv >= 48 && nv <= 57) {
					r += cv;
					j++;
				} else {
					j++;
					i--;
				}
				break;
			}
			case "!":
			{
				var nmas = "";
				var nval = "";
				for (var n = i + 1; n < mas.length; n++) nmas = mas.charAt(n) + nmas;
				for (var n = j; n < val.length; n++) nval = val.charAt(n) + nval;
				var re = calcula_mascara(nval, nmas);
				for (var n = re.length; n--; ) r += re.charAt(n);
				return r;
			}
			default:
			{
				if(cm == cv) {
					r += cv;
					j++;
				} else r += cm;
			}
		}
	}
	return r;
}	
function mascara(el, mas) {
	var v = calcula_mascara(el.value, mas);
	if(el.value != v) el.value = v;
}