<?php

	// Header sending
	header('HTTP/1.1 404 Not Found', true, 404);

	// App loading
	$this->title = '404 error';

	// App displaying
	?>
		<div class="app">
			<h1>404 error</h1>
		</div>
	<?php

?>