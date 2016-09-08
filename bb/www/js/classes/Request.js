// Request constructing
var Request = function (url, options) {
	options = (options ? options : new Object);
	var request = (app.requests[options['id']] ? app.requests[options['id']] : new XMLHttpRequest);
	var method = (options['method'] ? options['method'] : 'GET');
	if (options['query']) {
		var queries = new Array;
		for (var query in options['query']) {
			queries.push(query + '=' + encodeURIComponent(options['query'][query]));
		}
		url += '?' + queries.join('&');
	}
	request.abort();
	request.open(method, url);
	if (method == 'POST') {
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		if (!(options['content'] instanceof File)) {
			var contents = new Array;
			for (var content in options['content']) {
				contents.push(content + '=' + encodeURIComponent(options['content'][content]));
			}
			options['content'] = contents.join('&');
		}
	}
	request.upload.onprogress = options['progress'];
	request.onabort = options['abort'];
	request.onreadystatechange = function () {
		if (this.status && (this.readyState == 4)) {
			if (this.status != 200) {
				var error = {
					type: 'urlRequest',
					responseText: this.responseText,
					message: 'URL request error: ' + this.statusText
				};
				return (options['error'] ? options['error'].call(this, error) : false);
			}
			return (options['success'] ? options['success'].call(this, this.responseText) : true);
		}
	};
	clearTimeout(app.timeouts[options['id']]);
	var timeout = setTimeout(function () {
		request.send(options['content']);
	}, options['delay']);
	if (options['id']) {
		app.requests[options['id']] = request;
		app.timeouts[options['id']] = timeout;
	}
}