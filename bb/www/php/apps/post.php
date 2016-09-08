<?php

	// Ballot posting
	$ballot = json_decode($_POST['ballot']);
	if ($ballot === null) {
		App::sendError('HTTP/1.1 500 Internal Server Error', 500, 'Le bulletin de vote est invalide.');
	}
	if (!$this->election->opened) {
		App::sendError('HTTP/1.1 500 Internal Server Error', 500, 'Le vote est fermé.');
	}
	if (strpos(file_get_contents(APPS_DIR . '/public_creds.txt'), ($ballot->signature->public_key . "\n")) === false) {
		App::sendError('HTTP/1.1 500 Internal Server Error', 500, 'La clé privée est invalide.');
	}
	if (strpos(file_get_contents(APPS_DIR . '/ballots.jsons'), ('"public_key":"' . $ballot->signature->public_key . '"')) !== false) {
		$ballots = file(APPS_DIR . '/ballots.jsons');
		$ballots = array_filter($ballots, function ($value) use ($ballot) {
			return (strpos($value, ('"public_key":"' . $ballot->signature->public_key . '"')) === false);
		});
		file_put_contents((APPS_DIR . '/ballots.jsons'), implode("\n", $ballots));
	}
	file_put_contents((APPS_DIR . '/ballots.jsons'), $_POST['ballot'], FILE_APPEND);
	echo $ballot->signature->public_key;

?>