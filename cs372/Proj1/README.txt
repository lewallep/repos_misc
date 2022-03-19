/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #1
	Filename: README.txt
*/

FILENAME OF COMPLETE PROGRAM
	The program consists of two files,
		chatserve.c
		chatclient.py


HOW TO COMPILE
	Place the files into the folders of your choice on the machine(s) you would like to run them on.
	Compile chatserve.c with the following command,
		gcc -Wall -o cs chatserve.c

	chatclient.py does not require compiling.


HOW TO RUN THE CHAT PROGRAM
	Start chatserve first.  Using the command from above this would be done with
		./cs 30666 

	Any name of the executeable can be used. I chose this as it was short and easy and quick to type.

	To run the client use the following command
		python chatclient.py flip.engr.oregonstate.edu 30666

	flip.engr.oregonstate.edu can be substituted for any host you like as long as you move chatserve to that host 
	and the host accepts incoming external connections.

USING THE PROGRAM
	When the server is quitting a connection one last message must be sent from the server to run through
	the server send loop and set the proper exit flags.  I know I need to upgrade with this program with a second child
	process to fix this bug.

	