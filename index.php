<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Useful Websites for Development" content="">
	<meta name="Arges86" content="">
	<title>Quiz</title>
	<link rel="icon" href="question.png" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?lang=css&skin=desert"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>
	<script src="https://arges86.homeserver.com/how_to/js/scrl_to_top.js"></script>
	<link rel="stylesheet" href="/how_to/css/background_no_img.css">
	<link rel="stylesheet" href="/how_to/css/header.css">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
	<link rel="stylesheet" href="/how_to/css/style.css">
	<link rel="stylesheet" href="css/quiz.css">
	<!-- Analytics -->
	<script>
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
		ga('create', 'UA-75796062-1', 'auto');
		ga('send', 'pageview');
	</script>
	<!-- Analytics -->
</head>

<body>
	<?php include '../common/header.php';?>
	<!-- Post Content -->
	<p> &nbsp </p>
	<p> &nbsp </p>
	<p> &nbsp </p>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
			</div>
			<div class="col-md-6">
				Test your knowledge!
			</div>
			<div class="col-md-3">
			</div>
		</div>
		<div class="row">
			<div class="col-md-5">
				<h3>Question: #<span id="QNumber"></span> Out of: <span id="Qtotal"></span></h3> <br>
				<span id="question"><h4>A series of 20 random questions</h4>
					<br>Are you ready to start?
    </span> &nbsp; &nbsp; &nbsp;
				<span id="answers">
			<br>Answer:<br>
			<button type="button" value="1" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="2" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="3" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="4" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
		</span>
				<br>
				<span id="destroy_button"><button type="button" id="button_Begin" class="btn btn-info">Begin!</button></span>
				<button type="button" id="button_NextQuestion" class="btn btn-info">Next Question</button>
				<br>
				<span id="results"></span>
				<button type='button' id='final_score' class='btn btn-success'>Submit Your Score</button></br>
<!-- 				<button type='button' id='show_score' class='btn btn-success'>See all the scores</button> -->
				<br>
				<span id="errors"></span>
				<br>
			</div>
			<div class="col-md-5">
				<canvas id="myChart" width="400" height="auto"></canvas>
				<br>
			</div>
			<div class="col-md-2">
				<h3>Your Score: <span id="score"></span></h3><br>
				<button type="button" id="button_reset" class="btn btn-reset">Reset And try again</button>
			</div>
		</div>
	</div>
<!-- 	Loads JS file that does work-->
	<script src="js/quiz.js"></script>
</body>
</html>