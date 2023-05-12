<?php 

$base = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>HOME</title>
		<link rel="stylesheet" type="text/css" href="<?=$base?>/assets/libs/bootstrap/css/bootstrap.min.css">
	</head>
	
	<body>
		<main class="container py-4">
			<h3>SAMPLE HAPUS DATA PADA ALAT</h3>
			<table class="table border">
				<thead>
					<tr>
						<th>No</th>
						<th>User ID</th>
						<th>Nickname</th>
						<th>Privilege</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					</tr>
				</tbody>
			</table>
		</main>
	</body>
</html>