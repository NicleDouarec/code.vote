<?php
	class Query {

		// "AS" handling
		public static function alias($field, $value) {
			return $field .' AS '. $value;
		}

		// "AND" handling
		public static function ands() {
			return '(' . implode(') AND (', func_get_args()) . ')';
		}

		// "ASC" handling
		public static function asc($field) {
			return $field . ' ASC';
		}

		// "BETWEEN" handling
		public static function between($field, $minimum, $maximum) {
			return $field . ' BETWEEN ' . $minimum . ' AND ' . $maximum;
		}

		// "()" handling
		public static function bracket($value) {
			return '(' . $value . ')';
		}

		// "COUNT" handling
		public static function count($field) {
			return 'COUNT(' . $field . ')';
		}

		// "DELETE" handling
		public function delete() {
			$this->type = 'delete';
			return $this;
		}

		// "DESC" handling
		public static function desc($field) {
			return $field . ' DESC';
		}

		// "/" handling
		public static function divide($field, $value) {
			return $field . ' / ' . $value;
		}

		// "=" handling
		public static function equal($field, $value) {
			return $field . (($value !== null) ? (' = ' . $value) : ' IS NULL');
		}

		// Query escaping
		public static function escape($value) {
			if ($value == '*') {
				return $value;
			}
			elseif (strpos($value, '.') !== false) {
				return implode('.', array_map('Query::escape', explode('.', $value)));
			}
			return '`' . str_replace('`', '``', $value) . '`';
		}

		// Result fetching
		public static function fetch($result, $options) {
			$object = (($options['class'] !== null) ? $result->fetch_object($options['class']) : $result->fetch_object());
			for ($count = 0; $count < $result->field_count; $count++) {
				$field = $result->fetch_field_direct($count);
				if ($object->{$field->name} !== null) {
					switch ($field->type) {
						case MYSQLI_TYPE_TINY:
							$object->{$field->name} = intval($object->{$field->name});
							break;
						case MYSQLI_TYPE_SHORT:
							$object->{$field->name} = intval($object->{$field->name});
							break;
						case MYSQLI_TYPE_INT24:
							$object->{$field->name} = intval($object->{$field->name});
							break;
						case MYSQLI_TYPE_LONG:
							$object->{$field->name} = intval($object->{$field->name});
							break;
						case MYSQLI_TYPE_LONGLONG:
							$object->{$field->name} = intval($object->{$field->name});
							break;
						case MYSQLI_TYPE_NEWDECIMAL:
							$object->{$field->name} = floatval($object->{$field->name});
							break;
					}
				}
			}
			return $object;
		}

		// "FROM" handling
		public function from() {
			foreach (func_get_args() as $table) {
				$this->tables[] = $table;
			}
			return $this;
		}

		// Query getting
		public function get() {
			switch ($this->type) {
				case 'select':
					$values[] = 'SELECT ' . implode(', ' , $this->fields) . ' FROM ' . implode(', ' , $this->tables);
					break;
				case 'insert':
					$values[] = 'INSERT INTO ' . $this->table . '(' . implode(', ' , $this->fields) . ') VALUES(' . implode(', ' , $this->values) . ')';
					break;
				case 'update':
					$values[] = 'UPDATE ' . $this->table . ' SET ' . implode(', ' , $this->values);
					break;
				case 'delete':
					$values[] = 'DELETE FROM ' . implode(', ' , $this->tables);
					break;
				default:
					$values[] = $this->value;
			}
			foreach ($this->joins as $key => $join) {
				$values[] = $this->joins[$key];
				$values[] = $this->ons[$key];
			}
			if ($this->where !== null) {
				$values[] = $this->where;
			}
			if ($this->orders !== null) {
				$values[] = 'ORDER BY ' . implode(', ' , $this->orders);
			}
			if ($this->limit !== null) {
				$values[] = $this->limit;
			}
			return implode(' ', $values);
		}

		// ">" handling
		public static function greater($field, $value) {
			return $field . ' > ' . $value;
		}

		// ">=" handling
		public static function greaterEqual($field, $value) {
			return $field . ' >= ' . $value;
		}

		// "INNER JOIN" handling
		public function innerJoin() {
			foreach (func_get_args() as $table) {
				$this->joins[] = 'INNER JOIN ' . $table;
			}
			return $this;
		}

		// "INSERT" handling
		public function insert($values) {
			$this->type = 'insert';
			foreach ($values as $key => $value) {
				$this->fields[] = $key;
				$this->values[] = (($value === null) ? 'NULL' : $value);
			}
			return $this;
		}

		// "INTO" handling
		public function into($table) {
			$this->table = $table;
			return $this;
		}

		// "LEFT JOIN" handling
		public function leftJoin() {
			foreach (func_get_args() as $table) {
				$this->joins[] = 'LEFT JOIN ' . $table;
			}
			return $this;
		}

		// "<" handling
		public static function less($field, $value) {
			return $field . ' < ' . $value;
		}

		// "<=" handling
		public static function lessEqual($field, $value) {
			return $field . ' <= ' . $value;
		}

		// "LIKE" handling
		public static function like($field, $value) {
			return $field . ' LIKE ' . $value;
		}

		// "LIMIT" handling
		public function limit($offset, $count) {
			$this->limit = 'LIMIT ' . $offset . (($count !== null) ? (', ' . $count) : '');
			return $this;
		}

		// "MATCH" handling
		public static function match($fields, $value, $options) {
			$fields = (is_array($fields) ? $fields : array($fields));
			$mode = (($options['mode'] !== null) ? $options['mode'] : 'BOOLEAN');
			return 'MATCH(' . implode(', ', $fields) . ') AGAINST(' . $value . ' IN ' . $mode . ' MODE)';
		}

		// "MAX" handling
		public static function max($field) {
			return 'MAX(' . $field . ')';
		}

		// "MIN" handling
		public static function min($field) {
			return 'MIN(' . $field . ')';
		}

		// "-" handling
		public static function minus($field, $value) {
			return $field . ' - ' . $value;
		}

		// "*" handling
		public static function multiply($field, $value) {
			return $field . ' * ' . $value;
		}

		// "<>" handling
		public static function notEqual($field, $value) {
			return $field . (($value !== null) ? (' <> ' . $value) : ' IS NOT NULL');
		}

		// "ON" handling
		public function on() {
			foreach (func_get_args() as $on) {
				$this->ons[] = 'ON ' . $on;
			}
			return $this;
		}

		// "ORDER BY" handling
		public function orderBy() {
			foreach (func_get_args() as $order) {
				$this->orders[] = $order;
			}
			return $this;
		}

		// "OR" handling
		public static function ors() {
			return '(' . implode(') OR (', func_get_args()) . ')';
		}

		// "+" handling
		public static function plus($field, $value) {
			return $field . ' + ' . $value;
		}

		// "REGEXP" handling
		public static function regexp($field, $value) {
			return $field . ' REGEXP ' . $value;
		}

		// "SELECT" handling
		public function select() {
			$this->type = 'select';
			foreach (func_get_args() as $field) {
				$this->fields[] = $field;
			}
			return $this;
		}

		// "SET" handling
		public function set($values) {
			foreach ($values as $key => $value) {
				$this->values[] = $key . ' = ' . (($value === null) ? 'NULL' : $value);
			}
			return $this;
		}

		// "UNION ALL" handling
		public static function unionAll() {
			foreach (func_get_args() as $query) {
				$values[] = $query->get();
			}
			$value = '(' . implode(') UNION ALL (', $values) . ')';
			return new Query(array('value' => $value));
		}

		// "UNION DISTINCT" handling
		public static function unionDistinct() {
			foreach (func_get_args() as $query) {
				$values[] = $query->get();
			}
			$value = '(' . implode(') UNION (', $values) . ')';
			return new Query(array('value' => $value));
		}

		// "UPDATE" handling
		public function update($table) {
			$this->type = 'update';
			$this->table = $table;
			return $this;
		}

		// Value handling
		public static function value($value) {
			if (is_string($value)) {
				return '"' . str_replace('"', '""', $value) . '"';
			}
			elseif (is_float($value)) {
				return number_format($value, ini_get('precision'), '.', '');
			}
			return $value;
		}

		// "WHERE" handling
		public function where($where) {
			$this->where = 'WHERE ' . $where;
			return $this;
		}

	}
?>