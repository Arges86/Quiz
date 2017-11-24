/*
@param QuestionNumber - The question pulled form DB being asked
@param SessionScore - The score of the user
@param QuestionsAsked - Increasing count of questions asked
@param Questionlist - Array of which questions asked
@function GetQuestion - comes up with random number exculding ones already used (on QuestionList)
@function ajaxCall - calls db for question, answers, key and then displays information
@function myChartFunction - added values to chart.js and updates it
*/
$(document).ready(function() {
	if(typeof(Storage) !== "undefined")
	//Sets initial questionnumber (aka db row number) to zero
	if (sessionStorage.QuestionNumber){
		//if variable exists, persist it
		sessionStorage.QuestionNumber = Number(sessionStorage.QuestionNumber);
	} else {
		//If variable doesn't already exist, set to zero
		sessionStorage.QuestionNumber = 0;  
	}
	//sets initial score (starts at zero. goes up from there)
	if (sessionStorage.SessionScore){
		sessionStorage.SessionScore = Number(sessionStorage.SessionScore);
	} else {
		sessionStorage.SessionScore = 0;  
	}
	//Sets Number of asked questions (1-20)
	if (sessionStorage.QuestionsAsked){
		sessionStorage.QuestionsAsked = Number(sessionStorage.QuestionsAsked);
	} else {
		sessionStorage.QuestionsAsked = 0;  
	}
	$('#QNumber').html(sessionStorage.QuestionsAsked);
	//sets QuestionList. used to keep track of questions asked
	if (sessionStorage.QuestionList){
		sessionStorage.QuestionList = (sessionStorage.QuestionList);
	} else {
		sessionStorage.QuestionList = "0";
	}
	//takes string of test case # and turns into array
	var QuestionArray = JSON.parse("[" + sessionStorage.QuestionList + "]");
	$('#score').html(sessionStorage.SessionScore);
	var totalNum = null;
	//Find the total number of questions and posts it
		$.post( "php/CountTotal.php", function( data ) {
			totalNum = data;
				$("#Qtotal").html("20");
			return totalNum;
		});
	var jdata = null;
	//ajax call for getting question and answers
	var GetQuestion = function() {
		//Returns a random number between zero and the max number of questions in the db
		var b = 0;
		var c = 0;
		while (b > -1) {
			c = Math.floor((Math.random() * totalNum) + 1);
			b = QuestionArray.indexOf(c);
			console.log(QuestionArray)
		}
		//Adds question number to array
		var a = parseInt(sessionStorage.QuestionNumber);
		QuestionArray.push(c);
		sessionStorage.QuestionList = QuestionArray;
		sessionStorage.QuestionNumber = c;
	};
	var ajaxCall = function() {
		// Return the $.ajax promise
		return $.ajax({
			data: {QuestionNumber: sessionStorage.QuestionNumber},
			url: "php/QuestionAnswer.php",
			type: 'POST',
			cache: false,
			beforeSend: function() {
				console.log("Current Question "+sessionStorage.QuestionNumber);
				//deletes any results
				$('#results').html("");
			},
			success: function(question) {
				console.log(question);
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
					$('#results').html("End of test!<br>Your final score is "+sessionStorage.SessionScore+"<br>");
					//disables answer buttons
					$("#button_UserSubmit").siblings().andSelf().prop("disabled", true);
					//shows Final Submit button_UserSubmit
					$("#final_score").css('visibility', 'visible');
				} else {
	// 						console.log(jdata);
					$("#answers").css('visibility', 'visible');
					$("#button_NextQuestion").css('visibility', 'hidden');
					//Enables answer buttons
					$("#button_UserSubmit").siblings().andSelf().prop("disabled", false);
					//Shows Question
					$('#question').fadeIn('slow').html(jdata.question);
					//Shows Question number
					$('#QNumber').html(sessionStorage.QuestionsAsked);
					//Enters values in buttons
					$("button[value='1']").html(jdata.answer1);
					$("button[value='2']").html(jdata.answer2);
					$("button[value='3']").html(jdata.answer3);
					$("button[value='4']").html(jdata.answer4);
				}
			}
		});
	};
		//Function shows all the stored scores
	var myChartFunction =  function() {
		$.post( "php/scoreSearch.php")
			.done(function(data) {
			$("#myChart").html(" ");
			var gdata = null;
			try {
				gdata = $.parseJSON(data);
// 						console.log(gdata);
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
							x: sessionStorage.SessionScore,
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
		};
	//If the score is greater than zero, hide the 'Begin Button'
	//And Start the quiz at the already set Question #
	if (sessionStorage.QuestionsAsked == 0) {
		$("#button_Begin").css('visibility', 'visible');
	} else {
		$("#button_Begin").css('visibility', 'hidden');
		$('#destroy_button').html("");
		$("#button_NextQuestion").css('visibility', 'visible');
		ajaxCall();
		myChartFunction();
	}
	//Clicks begin button
	$("#button_Begin").click(function() {
		$("#button_Begin").css('visibility', 'hidden');
		$("#button_NextQuestion").css('visibility', 'visible');
		$('#destroy_button').html("");
		//increases QuestionNumber to 1
		sessionStorage.QuestionsAsked++;
		GetQuestion();
		//Function to show question and answers
		ajaxCall();
		myChartFunction();
	});
	//Next Question button
	$("#button_NextQuestion").click(function(e) {
		// Prevent Default Submission
		e.preventDefault();
		//hides 'Next Question' button
		$("#button_NextQuestion").css('visibility', 'hidden');
		//Adds one to index incrementing questions
		sessionStorage.QuestionsAsked++;
		//If the number of asked questions is less than 21 keep going.
		//If not, end quiz
		if (sessionStorage.QuestionsAsked < 21) {
			GetQuestion();
			ajaxCall();
			myChartFunction();
		} else {
			$('#results').html("End of test!<br>Your final score is "+sessionStorage.SessionScore+"<br>");
			//disables answer buttons
			$("#button_UserSubmit").siblings().andSelf().prop("disabled", true);
			//shows Final Submit button_UserSubmit
			$("#final_score").css('visibility', 'visible');
		}
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
			sessionStorage.SessionScore++;
// 					score++;
			console.log(sessionStorage.SessionScore);
			$('#score').html(sessionStorage.SessionScore);
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
	var ResetEverything = function () {
		delete sessionStorage.QuestionNumber;
		delete sessionStorage.SessionScore;
		delete sessionStorage.QuestionList;
		delete sessionStorage.QuestionsAsked;
		location.reload();
	}
	//Function adds user's score to db
	$("#final_score").click(function(e) {
		// Prevent Default Submission
		e.preventDefault();
		console.log(sessionStorage.SessionScore);
		var form ={'score':sessionStorage.SessionScore};
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
					ResetEverything();
				} else {
					$('#errors').html("I'm sorry, something whent wrong");
				}
			}
		})
	});
	$("#button_reset").click(function(e){
		//Clears sessions information and reloads the page
		e.preventDefault();
		ResetEverything();
	});
});