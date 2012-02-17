<html>
	<head>
		<title></title>
	</head>
	<body>
	<?php //print_r($venues); ?>
	
	<?php foreach($venues->response->groups[0]->items as $v): ?>
		<a href="/foursquare/checkin/<?=$v->id;?>"><?=$v->name; ?></a><br/>
	<?php endforeach; ?>
	</body>
</html>
