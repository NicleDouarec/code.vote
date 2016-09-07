// Thing constructing
var Thing = function (values) {
	for (var key in values) {
		this[key] = values[key];
	}
};

// Thing extending
Thing.prototype.extend = function (constructor) {
	var Child = (constructor ? constructor : new Function);
	Child.prototype = Object.create(this);
	Child.prototype.constructor = Child;
	return Child;
};