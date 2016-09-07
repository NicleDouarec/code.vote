// App constructing
var App = Thing.prototype.extend(function (values) {
	Thing.call(this, values);
	this.popups = new Array;
	this.requests = new Object;
	this.timeouts = new Object;
});

// Event dispatching
App.prototype.dispatchEvent = function (type, element) {
	var event = document.createEvent('Event');
	event.initEvent(type, true, true);
	element.dispatchEvent(event);
};

// Text getting
App.prototype.getText = function (path, name, options) {
	options = (options ? options : new Object);
	for (var text = 0; text < this.texts[path][name].length; text++) {
		var equal = ((options['count'] == this.texts[path][name][text].minimum) && (options['count'] == this.texts[path][name][text].maximum));
		var between = ((this.texts[path][name][text].minimum != null) && (this.texts[path][name][text].maximum != null) && (options['count'] >= this.texts[path][name][text].minimum) && (options['count'] <= this.texts[path][name][text].maximum));
		var above = ((this.texts[path][name][text].minimum != null) && (this.texts[path][name][text].maximum == null) && (options['count'] >= this.texts[path][name][text].minimum));
		var below = ((this.texts[path][name][text].maximum != null) && (this.texts[path][name][text].minimum == null) && (options['count'] <= this.texts[path][name][text].maximum));
		var count = (equal || ((options['count'] != null) && (between || above || below)));
		var context = (options['context'] == this.texts[path][name][text].context);
		if (count && context) {
			return this.texts[path][name][text].value;
		}
	}
	return name;
};

// App passing
App.prototype.pass = function () {
	var parameters = Array.prototype.slice.call(arguments);
	var callback = parameters.pop();
	callback.apply(null, parameters);
};