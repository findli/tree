/**
 * Created by ya on 09.01.14.
 */

// usage example
//"hello".ucFirst(); // --> Hello
String.prototype.ucFirst = function() {
    var str = this;
    if(str.length) {
        str = str.charAt(0).toUpperCase() + str.slice(1);
    }
    return str;
};

