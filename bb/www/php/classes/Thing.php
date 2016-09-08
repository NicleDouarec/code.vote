<?php
	class Thing {

		// Thing constructing
		public function __construct($values) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}

		// Thing loading
		public function load($object) {
			foreach ($object as $key => $value) {
				$this->$key = $object->$key;
			}
		}

		// Thing saving
		public function save($fields, $table, $options) {
			$invalids = array_diff($fields, array(true));
			if (count($invalids)) {
				$error = new Thing(array(
					'type' => 'fieldsValidation',
					'fields' => $invalids,
					'message' => 'Fields validation error: ' . implode(', ', array_keys($invalids))
				));
				return ($options['error'] ? $options['error']($error) : false);
			}
			foreach ($fields as $key => $value) {
				$values[Query::escape($key)] = Query::value($this->$key);
			}
			$query = new Query;
			if (!$this->id) {
				$query->insert($values)->into($table);
				return $this->database->query($query, array(
					'success' => function ($result) use ($options) {
						$this->id = $this->database->mysqli->insert_id;
						return ($options['success'] ? $options['success']() : true);
					},
					'error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}
				));
			}
			else {
				$query->update($table)->set($values)->where(Query::equal('id', $this->id));
				return $this->database->query($query, array(
					'success' => function ($result) use ($options) {
						return ($options['success'] ? $options['success']() : true);
					},
					'error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}
				));
			}
		}

	}
?>