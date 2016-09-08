// Popup constructing
var Popup = function (options) {
	this.element = document.createElement('div');
	for (var attribute in options['attributes']) {
		this.element.setAttribute(attribute, options['attributes'][attribute]);
	}
	if (typeof(options['content']) == 'string') {
		this.parent = (options['parent'] ? options['parent'] : document.body);
		this.element.innerHTML = options['content'];
	}
	else {
		this.parent = (options['parent'] ? options['parent'] : options['content'].parentNode);
		options['content'].parentNode.removeChild(options['content']);
		this.element.innerHTML = options['content'].innerHTML;
	}
	this.background = document.createElement('div');
	for (var attribute in options['backgroundAttributes']) {
		this.background.setAttribute(attribute, options['backgroundAttributes'][attribute]);
	}
	this.background.appendChild(this.element);
	this.parent.appendChild(this.background);
	this.background.style.display = 'none'
	app.popups.push(this);
};

// Popups clearing
Popup.prototype.clear = function () {
	for (var popup in app.popups) {
		app.popups[popup].close();
	}
};

// Popup closing
Popup.prototype.close = function () {
	this.background.style.display = 'none';
};

// Popup moving
Popup.prototype.move = function (x, y) {
	this.element.style.left = x + 'px';
	this.element.style.top = y + 'px';
};

// Popup opening
Popup.prototype.open = function (options) {
	options = (options ? options : new Object);
	var clear = ((options['clear'] != null) ? options['clear'] : true);
	if (clear) {
		Popup.prototype.clear();
	}
	this.background.style.display = 'block';
};