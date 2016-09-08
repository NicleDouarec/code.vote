<?php
	class Scripts {

		// Value exporting
		public static function exportValue($value) {
			return '(' . json_encode(Scripts::washVariable($value)) . ')';
		}

		// Variable exporting
		public static function exportVariable($name, $value, $class) {
			$value = json_encode(Scripts::washVariable($value));
			return $name . ' = ' . (($class !== null) ? ('new ' . $class . '(' . $value . ')') : $value) . ';';
		}

		// Variable washing
		public static function washVariable($variable) {
			if (is_object($variable)) {
				$variable = get_object_vars($variable);
			}
			if (is_array($variable)) {
				foreach ($variable as $key => $value) {
					if ($value === null) {
						unset($variable[$key]);
					}
					else {
						$variable[$key] = Scripts::washVariable($value);
					}
				}
			}
			elseif (is_string($variable)) {
				$variable = html_entity_decode($variable);
			}
			return $variable;
		}

	}
?>