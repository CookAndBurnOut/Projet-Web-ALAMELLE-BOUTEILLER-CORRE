/*
	    |\___/|
	    )     (
	   =\     /=
	     )===(      ___________.__
	    /     \     \__    ___/|  |__   ____  ____
	    |     |  	    |   |   |  |  \_/ __ \/  _ \
	   /       \	   |   |   |   Y  \  ___(  <_> )
	   \       /	  |____|   |___|  /\___  >____/
	    \_____/ 	                \/     \/
*/

function isUnAlterate (json){
	var crc32Ariver = json.crc32;
	if (crc32Ariver == crc32(JSON.stringify(json.return)) + 42){
		return true;
	}
	return false;
}

function createJsonForRequest(str, controlleur){
    var crc32value = crc32(str + controlleur);
    jsonStr = '{"post":' + str + ',"controlleur":"' + controlleur + '","crc32":"' + crc32value + '"}';
    return jsonStr
}

//-----------------------------------------------------ajout au string--------------------------------------------------------------
String.prototype.isNumber = function(){
  return /^[0-9]+$/.test(this);
}

String.prototype.escapeLink = function(){
	return this.replace(/[/]/g, "\\$&");
}

String.prototype.escape = function(){
	return this.replace(/[!@#$%^&*()+=\-[\]\\';,./{}|":<>?~_]/g, "\\$&");
}

//----------------------------------------------------Crc 32 bit------------------------------------------------------------

//trouver sur https://stackoverflow.com/questions/18638900/javascript-crc32

var makeCRCTable = function(){
    var c;
    var crcTable = [];
    for(var n =0; n < 256; n++){
        c = n;
        for(var k =0; k < 8; k++){
            c = ((c&1) ? (0xEDB88320 ^ (c >>> 1)) : (c >>> 1));
        }
        crcTable[n] = c;
    }
    return crcTable;
}

function crc32(str) {
    var crcTable = window.crcTable || (window.crcTable = makeCRCTable());
    var crc = 0 ^ (-1);

    for (var i = 0; i < str.length; i++ ) {
        crc = (crc >>> 8) ^ crcTable[(crc ^ str.charCodeAt(i)) & 0xFF];
    }

    return (crc ^ (-1)) >>> 0;
};
