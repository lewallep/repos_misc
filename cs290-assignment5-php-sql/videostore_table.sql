#Student:  Philip Lewallen
#Class: CS290-400
#Instructor: Justin Wolford

DROP TABLE IF EXISTS VideoStore;
CREATE TABLE VideoStore (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR (255),
category VARCHAR (255),
length INT unsigned,
rented BOOLEAN DEFAULT TRUE,
PRIMARY KEY (id)
);