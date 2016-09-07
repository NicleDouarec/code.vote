<?php
	class Database extends Thing {

		// Properties protecting
		protected $host;
		protected $login;
		protected $name;
		protected $password;

		// Database querying
		public function query($query, $options) {

			// Database connecting
			if (!$this->mysqli) {
				$this->mysqli = new Mysqli($this->host, $this->login, $this->password, $this->name);
				if ($this->mysqli->connect_errno) {
					$error = new Thing(array(
						'type' => 'databaseConnection',
						'message' => $this->mysqli->connect_error
					));
					return ($options['error'] ? $options['error']($error) : false);
				}
				$this->mysqli->set_charset('utf8');
			}

			// Query executing
			$result = $this->mysqli->query($query->get());
			if ($this->mysqli->errno) {
				$error = new Thing(array(
					'type' => 'queryRequest',
					'message' => $this->mysqli->error
				));
				return ($options['error'] ? $options['error']($error) : false);
			}
			return ($options['success'] ? $options['success']($result) : $result);

		}

	}
?>