<?php

	// Header sending
	header('HTTP/1.1 503 Service Temporarily Unavailable', true, 503);

	// App loading
	$this->title = '503 error';

	// App displaying
	?>
		<div class="app">
			<h1>503 error</h1>
		</div>
	<?php

?>