# Quiz Project
This is a web based quiz game
Questions are stored in a mysql database and can be added to over time
Once the participant has finished the quiz, they can choose to submit the score

## Getting Started
These instructions will (hopefully) get you a copy of the project up and running

### Prerequisites
What things you need to install the software and how to install them:

* Web server with either Apache nginx, or ISS 
* MySQL database

### Installing
* Download project to a folder
* Point your web server to it
* Add a 'config.php' file in the directory above the project
> With SQL servers': servername, username, password, and database name
* Create Quiz table
```
CREATE TABLE `Quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(100) DEFAULT NULL,
  `answer1` varchar(45) DEFAULT NULL,
  `answer2` varchar(45) DEFAULT NULL,
  `answer3` varchar(45) DEFAULT NULL,
  `answer4` varchar(45) DEFAULT NULL,
  `Key` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1
```
* Create Quiz_Score table
```
CREATE TABLE `Quiz_Score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `score` int(20) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1
```
### Running site
* In the Quiz table, the 'question' column holds the question getting asked (duh)
* the 'answer1' 'answer2' 'answer3'  and 'answer4' column hold the four possible answers to the question
* the 'Key' column holds a number 1 through 4. This represents the answer key for the question.
> For example, of the Key value is 3, then the third answer in the table is the correct answer to the question
* The Quiz_Score table is populated with information once a user finishes the quiz and submits their results.
No info needs to be pre-populated here.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details