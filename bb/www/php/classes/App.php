<?php
	class App extends Thing {

		// App constructing
		public function __construct() {

			// App initializing
			$this->initialize();

			// Constants loading
			$this->loadConstants(('classes.' . $this->class), array('error' => function ($error) {
				trigger_error($error->message);
			}));

			// Locales loading
			$this->loadLocales(array('error' => function ($error) {
				trigger_error($error->message);
			}));

			// Timezones loading
			$this->loadTimezones(array('error' => function ($error) {
				trigger_error($error->message);
			}));

			// Texts loading
			$this->loadTexts(('classes.' . $this->class), array('error' => function ($error) {
				trigger_error($error->message);
			}));

			// User loading
			$this->loadUser(array('error' => function ($error) {
				trigger_error($error->message);
			}));

			// App loading
			$this->load($_GET['app'], array('error' => function ($error) {
				switch ($error->type) {
					case 'appFetching':
						$this->load('errors.404', array('error' => function ($error) {
							trigger_error($error->message);
						}));
						break;
					case 'userAccess':
						$this->load('users.login', array('error' => function ($error) {
							trigger_error($error->message);
						}));
						break;
					default:
						trigger_error($error->message);
				}
			}));

			// App rendering
			$this->render();

		}

		// Constants exporting
		public function exportConstants() {
			foreach ($this->constants as $paths) {
				foreach ($paths as $constant) {
					if ($constant->javaScript) {
						echo Scripts::exportVariable($constant->name, $constant->value);
					}
				}
			}
		}

		// Content getting
		public function getContent($path, $options) {
			if ($this->type == 'text/html; charset=UTF-8') {
				$css = '/css/' . str_replace('.', '/', $path) . '.css';
				if (file_exists(APPS_PATH . $css)) {
					$this->headers[] = '<link href="' . $css . '" rel="stylesheet" type="text/css" />';
				}
				$js = '/js/' . str_replace('.', '/', $path) . '.js';
				if (file_exists(APPS_PATH . $js)) {
					$this->headers[] = '<script src="' . $js . '"></script>';
				}
			}
			return $this->loadConstants($path, array(
				'success' => function () use ($path, $options) {
					return $this->loadTexts($path, array(
						'success' => function () use ($path, $options) {
							ob_start();
							$file = APPS_PATH . '/php/' . str_replace('.', '/', $path) . '.php';
							if (!(include $file)) {
								$error = new Thing(array(
									'type' => 'fileInclusion',
									'message' => 'File inclusion error: ' . $file
								));
								return ($options['error'] ? $options['error']($error) : false);
							}
							return ($options['success'] ? $options['success'](ob_get_clean()) : ob_get_clean());
						},
						'error' => function ($error) use ($options) {
							return ($options['error'] ? $options['error']($error) : false);
						}
					));
				},
				'error' => function ($error) use ($options) {
					return ($options['error'] ? $options['error']($error) : false);
				}
			));
		}

		// Locale getting
		public function getLocale() {
			if (array_key_exists($_COOKIE['locale'], $this->locales)) {
				return $this->locales[$_COOKIE['locale']];
			}
			$language = str_replace('-', '_', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			if (preg_match_all('/[a-z]{2}_[A-Z]{2}/', $language, $languages)) {
				foreach ($languages[0] as $language) {
					if (array_key_exists($language, $this->locales)) {
						return $this->locales[$language];
					}
				}
			}
			return $this->locales[DEFAULT_LOCALE];
		}

		// Text getting
		public function getText($path, $name, $options) {
			foreach ($this->texts[$path][$name] as $text) {
				$equal = (($options['count'] === $text->minimum) && ($options['count'] === $text->maximum));
				$between = (($text->minimum !== null) && ($text->maximum !== null) && ($options['count'] >= $text->minimum) && ($options['count'] <= $text->maximum));
				$above = (($text->minimum !== null) && ($text->maximum === null) && ($options['count'] >= $text->minimum));
				$below = (($text->maximum !== null) && ($text->minimum === null) && ($options['count'] <= $text->maximum));
				$count = ($equal || (($options['count'] !== null) && ($between || $above || $below)));
				$context = ($options['context'] === $text->context);
				if ($count && $context) {
					return $text->value;
				}
			}
			return $name;
		}

		// Texts getting
		public function getTexts() {
			$texts = array_filter(array_map(function ($texts) {
				return array_filter(array_map(function ($texts) {
					return array_map(function ($text) {
						return array(
							'minimum' => $text->minimum,
							'maximum' => $text->maximum,
							'context' => $text->context,
							'value' => $text->value
						);
					}, array_filter($texts, function ($text) {
						return $text->javaScript;
					}));
				}, $texts), function ($text) {
					return count($text);
				});
			}, $this->texts), function ($text) {
				return count($text);
			});
			return (count($texts) ? $texts : null);
		}

		// Timezone getting
		public function getTimezone() {
			return (array_key_exists($_COOKIE['timezone'], $this->timezones) ? $this->timezones[$_COOKIE['timezone']] : $this->timezones[$this->locale->timezone]);
		}

		// Error handling
		public static function handleError($error, $message, $file, $line) {
			if ($error & (E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT)) {
				$message = $message . ' in ' . $file . ' on line ' . $line . ((PHP_SAPI == 'cli') ? "\n" : '');
				App::sendError('HTTP/1.1 520 Web server is returning an unknown error', 520, $message);
			}
		}

		// Exception handling
		public static function handleException($exception) {
			$message = $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine() . ((PHP_SAPI == 'cli') ? "\n" : '');
			App::sendError('HTTP/1.1 520 Web server is returning an unknown error', 520, $message);
		}

		// App initializing
		public function initialize() {
			set_error_handler(array($this, 'handleError'));
			set_exception_handler(array($this, 'handleException'));
			mb_internal_encoding('UTF-8');
			mb_regex_encoding('UTF-8');
			if (PHP_SAPI == 'apache2handler') {
				session_start();
			}
			$this->class = get_class($this);
		}

		// App loading
		public function load($path, $options) {
			$path = (APPS_AVAILABILITY ? $path : 'errors.503');
			$query = new Query;
			$query->select('*')->from('apps')->where(Query::equal('path', Query::value($path)));
			return $this->database->query($query, array(
				'success' => function ($apps) use ($path, $options) {
					if (!($app = Query::fetch($apps))) {
						$error = new Thing(array(
							'type' => 'appFetching',
							'message' => 'App fetching error: ' . $path
						));
						return ($options['error'] ? $options['error']($error) : false);
					}
					$access = (!($app->flags & APPS_FLAG_ACCESS_USER) || $this->user);
					$access &= (!($app->flags & APPS_FLAG_ACCESS_ADMIN) || ($this->user->flags & USERS_FLAG_ROLE_ADMIN));
					if (!$access) {
						$error = new Thing(array(
							'type' => 'userAccess',
							'message' => 'User access error: ' . $path
						));
						return ($options['error'] ? $options['error']($error) : false);
					}
					parent::load($app);
					$path = (($this->template !== null) ? ('templates.' . $this->template) : ('apps.' . $this->path));
					$this->content = $this->getContent($path, array('error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}));
					return ($options['success'] ? $options['success']() : true);
				},
				'error' => function ($error) use ($options) {
					return ($options['error'] ? $options['error']($error) : false);
				}
			));
		}

		// Constants loading
		public function loadConstants($path, $options) {
			if ($this->constants[$path] === null) {
				$query = new Query;
				$query->select('*')->from('constants')->where(Query::equal('path', Query::value($path)));
				return $this->database->query($query, array(
					'success' => function ($constants) use ($path, $options) {
						$this->constants[$path] = array();
						while ($constant = Query::fetch($constants)) {
							$constant->value = ($constant->evaluate ? eval('return ' . $constant->value . ';') : $constant->value);
							define($constant->name, $constant->value);
							$this->constants[$path][] = $constant;
						}
						return ($options['success'] ? $options['success']() : true);
					},
					'error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}
				));
			}
			return ($options['success'] ? $options['success']() : true);
		}

		// Locales loading
		public function loadLocales($options) {
			$query = new Query;
			$query->select('*')->from('locales');
			return $this->database->query($query, array(
				'success' => function ($locales) use ($options) {
					while ($locale = Query::fetch($locales)) {
						$this->locales[$locale->value] = $locale;
					}
					$this->setLocale($this->getLocale());
					return ($options['success'] ? $options['success']() : true);
				},
				'error' => function ($error) use ($options) {
					return ($options['error'] ? $options['error']($error) : false);
				}
			));
		}

		// Timezones loading
		public function loadTimezones($options) {
			$query = new Query;
			$query->select('*')->from('timezones');
			return $this->database->query($query, array(
				'success' => function ($timezones) use ($options) {
					while ($timezone = Query::fetch($timezones)) {
						$this->timezones[$timezone->value] = $timezone;
					}
					$this->setTimezone($this->getTimezone());
					return ($options['success'] ? $options['success']() : true);
				},
				'error' => function ($error) use ($options) {
					return ($options['error'] ? $options['error']($error) : false);
				}
			));
		}

		// Texts loading
		public function loadTexts($path, $options) {
			if ($this->texts[$path] === null) {
				$query = new Query;
				$query->select('*', Query::alias(Query::escape($this->locale->name), 'value'))->from('texts');
				$query->where(Query::equal('path', Query::value($path)));
				return $this->database->query($query, array(
					'success' => function ($texts) use ($path, $options) {
						$this->texts[$path] = array();
						while ($text = Query::fetch($texts)) {
							$this->texts[$path][$text->name][] = $text;
						}
						return ($options['success'] ? $options['success']() : true);
					},
					'error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}
				));
			}
			return ($options['success'] ? $options['success']() : true);
		}

		// User loading
		public function loadUser($options) {
			if ($_SESSION['id'] && $_SESSION['password']) {
				$query = new Query;
				$query->select('*')->from('users');
				$query->where(Query::ands(Query::equal('id', intval($_SESSION['id'])), Query::equal('password', Query::value($_SESSION['password']))));
				return $this->database->query($query, array(
					'success' => function ($users) use ($options) {
						$this->user = Query::fetch($users, array('class' => 'User'));
						return ($options['success'] ? $options['success']() : true);
					},
					'error' => function ($error) use ($options) {
						return ($options['error'] ? $options['error']($error) : false);
					}
				));
			}
		}

		// App logout
		public function logout() {
			unset($this->user);
			unset($_SESSION['id']);
			unset($_SESSION['password']);
		}

		// App rendering
		public function render() {
			header('Content-Type: ' . $this->type);
			if ($this->type == 'text/html; charset=UTF-8') {
				?>
					<!DOCTYPE html>
					<html lang="<?= $this->locale->language ?>" xml:lang="<?= $this->locale->language ?>">
						<head>
							<meta charset="UTF-8" />
							<?php
								if ($this->title !== null) {
									echo '<title>' . htmlspecialchars($this->title) . '</title>';
								}
								if ($this->description !== null) {
									echo '<meta content="' . htmlspecialchars($this->description) . '" name="description" />';
								}
								$robots = array(
									'index' => ((APPS_INDEX && ($this->flags & APPS_FLAG_INDEX)) ? 'index' : 'noindex'),
									'follow' => ((APPS_FOLLOW && ($this->flags & APPS_FLAG_FOLLOW)) ? 'follow' : 'nofollow')
								);
								?>
									<meta content="<?= implode(', ', $robots) ?>" name="robots" />
									<script src="/js/classes/Thing.js"></script>
									<script src="/js/classes/App.js"></script>
									<script src="/js/classes/<?= $this->class ?>.js"></script>
									<script>
										<?php
											$this->exportConstants();
											echo Scripts::exportVariable('app', array('texts' => $this->getTexts()), $this->class);
										?>
									</script>
									<?= implode(array_unique($this->headers)) ?>
								<?php
							?>
						</head>
						<body>
							<?php
								echo $this->content;
								if (APPS_ANALYTICS != 'APPS_ANALYTICS') {
									?>
										<script>
											(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
											(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
											m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
											})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
											ga('create', '<?= APPS_ANALYTICS ?>', 'auto');
											ga('send', 'pageview');
										</script>
									<?php
								}
							?>
						</body>
					</html>
				<?php
			}
			else {
				echo $this->content;
			}
		}

		// Error sending
		public static function sendError($header, $code, $message) {
			header($header, true, $code);
			exit($message);
		}

		// Locale setting
		public function setLocale($locale) {
			setlocale(LC_ALL, ($locale->value . '.UTF-8'));
			$format = localeconv();
			$locale->decimalPoint = $format['decimal_point'];
			$locale->thousandsSeparator = $format['thousands_sep'];
			$this->locale = $locale;
		}

		// Timezone setting
		public function setTimezone($timezone) {
			date_default_timezone_set($timezone->value);
			$this->timezone = $timezone;
		}

	}
?>