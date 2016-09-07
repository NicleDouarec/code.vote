<?php
	class CodeVote extends App {

		// App initializing
		public function initialize() {
			parent::initialize();

			// Private loading
			include APPS_PATH . '/php/private.php';

		}

		// App loading
		public function load($path, $options) {

			// Metadata loading
			$this->metadata = json_decode(file_get_contents(APPS_DIR . '/metadata.json'));

			// Election loading
			$election = file_get_contents(APPS_DIR . '/election.json');
			$this->election = json_decode($election);
			$this->fingerprint = base64_encode(hash('sha256', $election, true));
			$this->election->start = DateTime::createFromFormat('Y-m-d H:i:s', $this->metadata->start);
			$this->election->end = DateTime::createFromFormat('Y-m-d H:i:s', $this->metadata->end);
			$this->election->opened = ($_SERVER['REQUEST_TIME'] >= $this->election->start->getTimestamp()) && ($_SERVER['REQUEST_TIME'] <= $this->election->end->getTimestamp());

			return parent::load($path, $options);
		}

		// App rendering
		public function render() {

			// App loading
			$this->headers[] = '<meta content="width=device-width, initial-scale=1" name="viewport">';
			$this->headers[] = '<link href="/css/classes/CodeVote.css" rel="stylesheet" type="text/css" />';

			parent::render();
		}

	}
?>