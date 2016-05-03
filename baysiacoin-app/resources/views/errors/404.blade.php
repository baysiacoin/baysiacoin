<html>
	<head>
		<title>Page Not Found</title>
		<link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon">
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				font-family: "Raleway";
				text-align: center;
				/*display: table-cell;*/
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 62px;
			}
			.title > h2 {
				font-weight: 900;
				font-size: 180px;
				/*color: #1AAE88;*/
				margin: 0px;
			}
			.title > h3 {
				margin: 0px 0px 60px;
			}
			.ss-left::before, .ss-left.right::after {
				content: "⬅";
			}
			.button {
				text-decoration: none;
				color: #B0BEC5;
				font-size: 25px;
				padding: 10px;
				border: 1px solid;
			}
			.button:hover {
				color: #FFFFFF;
				background-color: #B0BEC5;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">
					<h2>404</h2>
					<h3>Oops! Page Not Found</h3>
					<a href="{{ url('/') }}" class="button line-color centered"><i class="ss-left"></i> Back to Home Page</a> </p>
				</div>
			</div>
		</div>
	</body>
</html>
