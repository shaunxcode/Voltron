function tag() {
	var args = Array.prototype.slice.call(arguments); 
	var tagName = args.shift();
	var tag = $('<' + tagName +'></' + tagName + '>'); 
	$.each(args, function(i, content) { !content ? '' : (content.length && typeof(content) === 'object' ? $.each(content, function(x, y){tag.append(y)}) : tag.append(content)) });
	return tag;
}

function tags(block)
{
	return tag(block.shift(), $.map(block, function(t) {
		return $.isArray(t) ? tags(t) : t;
	}));
}

function orphanTag(tag) {
	return $('<' + tag + '/>');
}

Array.prototype.chunk = function(s) {
    for(var x, i = 0, c = -1, l = this.length, n = []; i < l; i++)
        (x = i % s) ? n[c][x] = this[i] : n[++c] = [this[i]];
    return n;
}

Array.prototype.reduceToField = function(field) {
	var obj = {};
	for(var x = 0, l = this.length; x < l; x++) {
		obj[this[x][field]] = this[x][field];
	}
	return obj;
}

Array.prototype.reduceTo = function(field) {
	return $.map(this, function(row) { return row[field] });
}

Array.prototype.castAsInt = function() {
	return $.map(this, function(item) { return parseInt(item) });
}


function objectKeys(obj) {
	var a = [];
	$.each(obj, function(k){ a.push(k) });
	return a;
};

function objectValues(obj) {
	var a = [];
	$.each(obj, function(k, v) { a.push(v) });
	return a;	
};
