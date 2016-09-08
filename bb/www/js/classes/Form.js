// Form constructing
var Form = function (callback) {
	this.fields = new Object;
	this.callback = callback;
};

// Checked values getting
Form.prototype.getCheckedValues = function (form, name) {
	var values = new Array;
	for (var element = 0; element < form[name].length; element++) {
		if (form[name][element].checked) {
			values.push(form[name][element].value);
		}
	}
	return values;
};

// Form getting
Form.prototype.getForm = function (element) {
	if (element == null) {
		return null;
	}
	else if (element.tagName == 'FORM') {
		return element;
	}
	return Form.prototype.getForm(element.parentNode);
};

// Form listening
Form.prototype.listen = function (id, events, callback) {
	this.fields[id] = {
		element: document.getElementById(id),
		events: events.slice()
	};
	events.push('validate');
	for (var event = 0; event <  events.length; event++) {
		this.fields[id].element.addEventListener(events[event], function (event) {
			return callback.call(this, event);
		}.bind(this));
	}
	App.prototype.dispatchEvent('init', this.fields[id].element);
	this.element = Form.prototype.getForm(this.fields[id].element);
};

// Form resetting
Form.prototype.reset = function () {
	this.element.removeAttribute('data-validity');
	for (var field in this.fields) {
		this.fields[field].element.removeAttribute('data-validity');
	}
	this.element.reset();
};

// Form submitting
Form.prototype.submit = function (event) {
	for (var field in this.fields) {
		if (!this.fields[field].element.getAttribute('data-validity')) {
			App.prototype.dispatchEvent('validate', this.fields[field].element);
		}
	}
	if (this.element.getAttribute('data-validity') == 'invalid') {
		return (this.callback ? this.callback.call(this, event, 'invalid') : false);
	}
	for (var field in this.fields) {
		if (this.fields[field].element.getAttribute('data-validity') == 'invalid') {
			return (this.callback ? this.callback.call(this, event, 'invalid') : false);
		}
	}
	if (this.element.getAttribute('data-validity') == 'pending') {
		return (this.callback ? this.callback.call(this, event, 'pending') : false);
	}
	for (var field in this.fields) {
		if (this.fields[field].element.getAttribute('data-validity') == 'pending') {
			this.fields[field].element.setAttribute = function (name, value) {
				Element.prototype.setAttribute.call(this.fields[field].element, name, value);
				if ((name == 'data-validity') && (value != 'pending')) {
					this.fields[field].element.setAttribute = Element.prototype.setAttribute;
					this.submit(event);
				}
			}.bind(this);
			return (this.callback ? this.callback.call(this, event, 'pending') : false);
		}
	}
	return (this.callback ? this.callback.call(this, event, 'valid') : false);
};