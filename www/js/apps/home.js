// App loading
addEventListener('load', function (event) {
	if (app.election.opened) {

		// Form loading
		var form = new Form(function (event, validity) {
			if (validity == 'valid') {
				event.target.setAttribute('data-validity', 'pending');
				encryptingPopup.open();
				var content = { credential: document.getElementById('appCredential').value };
				for (var question in app.election.questions) {
					for (var answer in app.election.questions[question].answers) {
						var checked = document.getElementById('appQuestion' + question + 'Answer' + answer).checked;
						content['question' + question + 'Answer' + answer] = (checked ? '1' : '0');
					}
				}
				new Request('/', {
					method: 'POST',
					query: { app: 'encrypt' },
					content: content,
					delay: 1000,
					success: function (responseText) {
						postingPopup.open();
						new Request('/', {
							method: 'POST',
							query: { app: 'post' },
							content: { ballot: responseText },
							delay: 1000,
							success: function (responseText) {
								confirmationPopup.open(responseText);
								this.reset();
							}.bind(this),
							error: function (error) {
								errorPopup.open(error.responseText);
								event.target.removeAttribute('data-validity');
							}
						});
					}.bind(this),
					error: function (error) {
						errorPopup.open(error.responseText);
						event.target.removeAttribute('data-validity');
					}
				});
			}
			return false;
		});
		form.listen('appCredential', ['change', 'keyup'], function (event) {
			var validity = (event.target.value.match(/[^\s]+/) ? 'valid' : 'invalid');
			event.target.setAttribute('data-validity', validity);
		});
		for (var question in app.election.questions) {
			for (var answer in app.election.questions[question].answers) {
				App.prototype.pass(question, function (question) {
					form.listen(('appQuestion' + question + 'Answer' + answer), ['change'], function (event) {
						var values = Form.prototype.getCheckedValues(event.target.form, event.target.name).length;
						var validity = (((values >= app.election.questions[question].min) && (values <= app.election.questions[question].max)) ? 'valid' : 'invalid');
						for (var element = 0; element < event.target.form[event.target.name].length; element++) {
							event.target.form[event.target.name][element].setAttribute('data-validity', validity);
						}
					});
				});
			}
		}
		form.element.onsubmit = Form.prototype.submit.bind(form);

		// Error popup loading
		var errorPopup = new Popup({
			backgroundAttributes: { class: 'background' },
			attributes: { class: 'popup' },
			content: document.getElementById('appError')
		});
		errorPopup.open = function (message) {
			document.getElementById('appErrorMessage').innerHTML = message;
			Popup.prototype.open.call(this);
		};
		document.getElementById('appErrorClose').onclick = Popup.prototype.close.bind(errorPopup);

		// Encrypting popup loading
		var encryptingPopup = new Popup({
			backgroundAttributes: { class: 'background' },
			attributes: { class: 'popup' },
			content: document.getElementById('appEncrypting')
		});

		// Posting popup loading
		var postingPopup = new Popup({
			backgroundAttributes: { class: 'background' },
			attributes: { class: 'popup' },
			content: document.getElementById('appPosting')
		});

		// Confirmation popup loading
		var confirmationPopup = new Popup({
			backgroundAttributes: { class: 'background' },
			attributes: { class: 'popup' },
			content: document.getElementById('appConfirmation')
		});
		confirmationPopup.open = function (key) {
			document.getElementById('appConfirmationKey').innerHTML = key;
			Popup.prototype.open.call(this);
		};
		document.getElementById('appConfirmationClose').onclick = Popup.prototype.close.bind(confirmationPopup);

	}
});