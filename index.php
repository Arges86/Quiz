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
	<style>
		input[type=text] {
			color: #000;
		}
		#errors {
			color: #FF0000;
			font-weight: bold;
		}
		#contact {
			position: fixed;
			bottom: 0;
		}
		#answers {
			visibility: hidden;
		}
		#final_score {
			visibility: hidden;
		}
		#show_score {
			visibility: hidden;
		}
		#myChart {
			border-radius: 25px;
		}
	</style>
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
				<span id="question">You ready to start?
    </span> &nbsp; &nbsp; &nbsp;
				<span id="answers">
			<br>Answer:<br>
			<button type="button" value="1" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="2" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="3" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
			<button type="button" value="4" id="button_UserSubmit" class="btn btn-primary"></button>&nbsp;
		</span>
				<br>
				<button type="button" id="button_NextQuestion" class="btn btn-info">Next Question</button>
				<br>
				<span id="results"></span>
				<button type='button' id='final_score' class='btn btn-success'>Submit Your Score</button></br>
<!-- 				<button type='button' id='show_score' class='btn btn-success'>See all the scores</button> -->
				<br>
				<span id="errors"></span>
				<br>
			</div>
			<div class="col-md-4">
				<canvas id="myChart" width="400" height="auto"></canvas>
				<br>
			</div>
			<div class="col-md-3">
				<h3>Your Score: <span id="score"></span></h3>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			//Sets initial questionnumber (aka db row number) to zero
			var QuestionNumber = 0;
			//sets initial score
			var score = 0;
			$('#score').html(score);
			var totalNum;
			//Find the total number of questions and posts it
				$.post( "php/CountTotal.php", function( data ) {
					var totalNum = data;
					$("#Qtotal").html(totalNum);
				});
			//ajax call for getting question
			var ajaxCall = function(QuestionNumber) {
				// Return the $.ajax promise
				return $.ajax({
					data: QuestionNumber,
					url: "php/QuestionAnswer.php",
					type: 'POST',
					cache: false,
					beforeSend: function() {
						//deletes any results
						$('#results').html("");
					},
				});
			};
			var jdata = null;
			//Next Question button
			$("#button_NextQuestion").click(function(e) {
				// Prevent Default Submission
				e.preventDefault();
				//hides 'Next Question' button
				$("#button_NextQuestion").css('visibility', 'hidden');
				//Adds one to index incrementing questions
				QuestionNumber++;
				//Makes call
				var getQuestion = ajaxCall({QuestionNumber});
				//when call is done...
				$.when(getQuestion).then(function(question) {
					try {
						jdata = $.parseJSON(question);
					} catch (e) {
						//Catches error, like at end of database
						console.log(e);
						console.log(question);
						$('#results').html("I'm sorry, an error occured. Please Try again");
					}
					//outputs a '0' if there are no questions left. Triggering end of quiz
					if (jdata == 0 ) {
						$('#results').html("End of test!<br>Your final score is "+score+"<br>");
						//disables answer buttons
						$("#button_UserSubmit").siblings().andSelf().prop("disabled", true);
						//shows Final Submit button_UserSubmit
						$("#final_score").css('visibility', 'visible');
					} else {
						console.log(jdata);
						$("#answers").css('visibility', 'visible');
						$("#button_NextQuestion").css('visibility', 'hidden');
						//Enables answer buttons
						$("#button_UserSubmit").siblings().andSelf().prop("disabled", false);
						//Shows Question
						$('#question').fadeIn('slow').html(jdata.question);
						//Shows Question number
						$('#QNumber').html(jdata.id);
						//Enters values in buttons
						$("button[value='1']").html(jdata.answer1);
						$("button[value='2']").html(jdata.answer2);
						$("button[value='3']").html(jdata.answer3);
						$("button[value='4']").html(jdata.answer4);
					}
				});
			});
			//User clicks on an answer
			$("#button_UserSubmit").siblings().andSelf().click(function(e) {
				// Prevent Default Submission
				e.preventDefault();
				//disables answer buttons
				$("#button_UserSubmit").siblings().andSelf().prop("disabled", true);
				//shows 'Next Question' button
				$("#button_NextQuestion").css('visibility', 'visible');
				//Gets Question # that user chose
				var UserAnswer = $(this).attr("value");
				//Determines if answer is correct. Increases score by one if correct
				console.log(UserAnswer+"->"+jdata.Key);
				if (UserAnswer == jdata.Key) {
					$('#results').html("You are correct!");
					score++;
					console.log(score);
					$('#score').html(score);
				} else {
				//switch statement finds correct answer and displays it
					switch (jdata.Key) {
						case 1:
							$('#results').html("I'm sorry, thats not the right answer.<br>The correct answer is " + jdata.answer1);
							break;
						case 2:
							$('#results').html("I'm sorry, thats not the right answer.<br>The correct answer is " + jdata.answer2);
							break;
						case 3:
							$('#results').html("I'm sorry, thats not the right answer.<br>The correct answer is " + jdata.answer3);
							break;
						case 4:
							$('#results').html("I'm sorry, thats not the right answer.<br>The correct answer is " + jdata.answer4);
							break;
					}
				}
			});
			//Function adds user's score to db
			$("#final_score").click(function(e) {
				// Prevent Default Submission
				e.preventDefault();
				console.log(score);
				var form ={'score':score};
				$.ajax({
					type: 'POST',
					url: 'php/score.php',
					data: form,
					success: function(data) {
						console.log(data);
						if (data =="1") {
							$('#results').html("Your score was added!")
							$("#final_score").css('visibility', 'hidden');
							$('#show_score').css('visibility','visible');
						} else {
							$('#errors').html("I'm sorry, something whent wrong");
						}
					}
				})
			});
			//Function shows all the stored scores
			$("#button_NextQuestion").click(function(e) {
				// Prevent Default Submission
				e.preventDefault();
				$.post( "php/scoreSearch.php")
					.done(function(data) {
					$("#myChart").html(" ");
					try {
						var gdata = $.parseJSON(data);
						console.log(gdata);
					}catch (e){
						$('#errors').html("I'm sorry, something whent wrong.<br>Please try again later");
						console.log(e);
					}
					//Takes highest y axis value, adds one and will use it later for maximum y chart length
					var i = (gdata.length)-1;
					var yAxis = parseInt((gdata[i]["y"]))+1;
					//creates chart of results for display
					var ctx = $("#myChart");
					var color = Chart.helpers.color;
					window.chartColors = {
						red: 'rgb(255, 99, 132)',
						orange: 'rgb(255, 159, 64)',
						yellow: 'rgb(255, 205, 86)',
						green: 'rgb(75, 192, 192)',
						blue: 'rgb(54, 162, 235)',
						purple: 'rgb(153, 102, 255)',
						grey: 'rgb(201, 203, 207)'
					};
					var myChart = new Chart(ctx, {
						type: 'scatter',
						data: {
								datasets: [{
									label: 'Number of Scores',
									data: gdata,
									pointStyle: 'rectRot',
									radius: '7',
									borderColor: color(window.chartColors.grey).alpha(0.5).rgbString(),
									backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
								}, {
								data: [{ //shows the user's score amoungst the others
									x: score,
									y: 1,
								}],
								label: "Your Score",
								radius: "7",
								pointStyle: 'triangle',
								backgroundColor: color(window.chartColors.red).alpha(0.4).rgbString(),
								}
								]
						},
						options: {
							title: {
								display: true,
								text: 'Count of Each Score'
	            },
							scales: {
								xAxes: [{
									type: 'linear',
									position: 'bottom',
									scaleLabel: {
	            			labelString: 'Score',
	            			display: true,
	            		},
									ticks: {
										fontColor: "#008000",
	            			userCallback: function(tick) {
	            				return tick.toString() + "pts";
	            			}},
								}],
								yAxes: [{
									type: 'linear',
									scaleLabel: {
	            			labelString: 'Count',
	            			display: true,
	            		},
									ticks: {
										fontColor: "#DB8E00",
										min: 0,
										max: yAxis,
										//forces y axis to be 1 unit
										stepSize: 1
									}
								}]
							}
						}
					});
					$("#myChart").css('background', '#FFF');
					})
					.fail(function() {
					$('#errors').html("I'm sorry, something whent wrong.<br>Please try again later");
					});
				});
		});
	</script>
</body>
</html>