<?php

	// App loading
	$this->headers[] = '<script src="/js/classes/Form.js"></script>';
	$this->headers[] = '<script src="/js/classes/Request.js"></script>';
	$this->headers[] = '<script src="/js/classes/Popup.js"></script>';
	$this->headers[] = '<script>' . Scripts::exportVariable('app.election', array(
		'opened' => $this->election->opened,
		'questions' => $this->election->questions
	)) . '</script>';
	$this->title = $this->election->short_name;
	$this->description = $this->election->description;

	// App displaying
	?>
		<div id="app">
			<h1><?= htmlspecialchars($this->election->name) ?></h1>
			<p><?= htmlspecialchars($this->election->description) ?></p>
			<?php
				if ($this->election->opened) {
					?>
						<p>Vote ouvert du <?= $this->election->start->format('d/m/Y à H:i:s') ?> au <?= $this->election->end->format('d/m/Y à H:i:s') ?>.</p>
						<form>
							<div>
								<label for="appCredential">Votre clé privée :</label>
								<input autocomplete="off" id="appCredential" type="text" />
								<span class="invalid">Ce champ est obligatoire.</span>
							</div>
							<?php
								foreach ($this->election->questions as $key => $question) {
									?>
										<div>
											<label><?= htmlspecialchars($question->question) ?></label>
											<?php
												$id = 'appQuestion' . $key;
												$name = 'question' . $key;
												$type = ((($question->min == 1) && ($question->max == 1)) ? 'radio' : 'checkbox');
												foreach ($question->answers as $key => $answer) {
													echo '<input id="' . $id . 'Answer' . $key . '" name="' . $name . '" type="' . $type . '" />';
													echo '<label for="' . $id . 'Answer' . $key . '">' . htmlspecialchars($answer) . '</label>';
												}
												$message = $this->getText('apps.home', 'invalidAnswer', array(
													'count' => $question->max,
													'context' => (($question->min == $question->max) ? 'equal' : 'different')
												));
												echo '<span class="invalid">' . str_replace(array('$min', '$max'), array($question->min, $question->max), $message) . '</span>';
											?>
										</div>
									<?php
								}
							?>
							<div>
								<input type="submit" value="Voter" />
							</div>
						</form>
						<div id="appError">
							<p id="appErrorMessage"></p>
							<p><button id="appErrorClose">Fermer</button></p>
						</div>
						<div id="appEncrypting"><p>Chiffrement du bulletin...</p></div>
						<div id="appPosting"><p>Envoi du bulletin...</p></div>
						<div id="appConfirmation">
							<p>Votre bulletin a été pris en compte, voici la clé publique permettant de vérifier sa présence dans l'urne :</p>
							<p id="appConfirmationKey"></p>
							<p><button id="appConfirmationClose">Fermer</button></p>
						</div>
					<?php
				}
				else {
					echo '<p>Vote fermé.</p>';
				}
			?>
			<footer>
				<p>Empreinte de l'élection : <?= $this->fingerprint ?></p>
				<p>
					Données de vérification :
					<?php
						$links[] = '<a href="/pub/election.json" target="_blank">élection</a>';
						$links[] = '<a href="/pub/public_creds.txt" target="_blank">électeurs</a>';
						$links[] = '<a href="/pub/public_keys.jsons" target="_blank">scrutateurs</a>';
						$links[] = '<a href="/pub/ballots.jsons" target="_blank">urne</a>';
						if (filesize(APPS_PATH . '/pub/result.json')) {
							$links[] = '<a href="/pub/result.json" target="_blank">résultats</a>';
						}
						echo implode(' - ', $links);
					?>
				</p>
				<p>Système de vote basé sur <a href="http://belenios.gforge.inria.fr" target="_blank">Belenios</a></p>
			</footer>
		</div>
	<?php

?>