function isArray(obj) {
    if (typeof obj != "undefined" && obj != null) {
        return obj.constructor == Array;
    }
    return false;
}

function countProps(obj) {
    var l = 0;
    for (p in obj) l++;
    return l;
}


