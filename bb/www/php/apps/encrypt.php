<?php

	// Id loading
	$id = uniqid($_POST['credential']);
	
	// Credential loading
	$credential = APPS_DIR . '/' . $id . '.credential';
	file_put_contents($credential, $_POST['credential']);

	// Ballot loading
	$ballots = array();
	$questions = count($this->election->questions);
	for ($question = 0; $question < $questions; $question++) {
		$ballot = array();
		$answers = count($this->election->questions[$question]->answers);
		for ($answer = 0; $answer < $answers; $answer++) {
			$ballot[] = intval($_POST['question' . $question . 'Answer' . $answer]);
		}
		$ballots[] = $ballot;
	}
	$ballot = APPS_DIR . '/' . $id . '.ballot';
	file_put_contents($ballot, json_encode($ballots));

	// Ballot encrypting
	echo shell_exec(APPS_TOOL . ' vote --privcred ' . $credential . ' --ballot ' . $ballot . ' --dir ' . APPS_DIR);
	unlink($credential);
	unlink($ballot);

?>