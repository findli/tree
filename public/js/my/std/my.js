if (typeof (console) == 'undefined') {console = {log: function(msg) {}};}

/*
replace with jQuery.inArray(value, array, index_from_search);
http://api.jquery.com/category/utilities/
 */
function in_array(needle, param){
    if(typeof param !== "string"){
        var res = null;
        $.each(param,function(index,value){
            if(value == needle){
                //console.log(value+' - '+needle);
                res = true;
            }
        });
    }else{
        if(needle == param){
            res = true
        }
    }
    return res;
}

// repeat string like 'a' *2 = 'aa'
String.prototype.repeat = function(count) {
    if (count < 1) return '';
    var result = '', pattern = this.valueOf();
    while (count > 0) {
        if (count & 1) result += pattern;
        count >>= 1, pattern += pattern;
    }
    return result;
};

// removeKey(arrayName,key);
function removeKey(arrayName,key)
{
    var x;
    var tmpArray = new Array();
    for(x in arrayName)
    {
        if(x!=key) { tmpArray[x] = arrayName[x]; }
    }
    return tmpArray;
}

// http://javascript.ru/php/explode
function explode( delimiter, string ) {	// Split a string by string
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: kenneth
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    var emptyArray = { 0: '' };

    if ( arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined' )
    {
        return null;
    }

    if ( delimiter === ''
        || delimiter === false
        || delimiter === null )
    {
        return false;
    }

    if ( typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object' )
    {
        return emptyArray;
    }

    if ( delimiter === true ) {
        delimiter = '1';
    }

    return string.toString().split ( delimiter.toString() );
}
