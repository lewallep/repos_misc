/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #2
	Filename: README.txt
*/

FILENAMES OF COMPLETE PROGRAM
	The program consists of four files
		ftfunctions.c
		ftserver.h
		ftclient.py
		ftserver.c

HOW TO COMPILE
	Place the files into the folders of your choice on the machine(s) you would like to run them on.
	Compile chatserve.c with the following command,
	
		gcc -Wall -o fs ftserver.h ftfunctions.c ftserver.c

	ftclient.py does not require compiling.

HOW TO RUN THE FTSERVER PROGRAM
	Start the server first.  After compiling this is done with 
	./fs 30666 or any other port number one would like.  

	Any name for the executeable can be used.

	Then to run the client, which can be run from any location you desire use the following format 
	on the command line,
		python ftclient.py <SERVER_HOST> <SERVER_PORT> <COMMAND> <DATA_PORT> <FILENAME>

	There are only two available commands, "-g" and "-l" without the double quotes of course.

	An example of a successful command would be

	python ftclient.py flip2.engr.oregonstate.edu 30666 -l 30667
	or
	python ftclient.py flip2.engr.oregonstate.edu 30666 -g 30667 test.txt

EXTRA CREDIT
	I made the server multitheaded and can accept multiple simultaneous connections as long as
	different data ports are specified for each connection.