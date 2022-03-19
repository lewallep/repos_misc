#!/usr/bin/python

#	Name: Philip Lewallen
#	Class: CS372 Introduction to Networks
#	Programming Assignment #2
#	Filename: ftclient.py

import socket, sys, os, signal
from multiprocessing import Process

class commArgs(object):
	def __init__(args, sHost, sPort, command, qPort, filename):
		args.sHost = sHost
		args.sPort = sPort
		args.command = command
		args.qPort = qPort
		args.filename = filename

# Checks to see if the command line was produced properly.  If it is
# the function returns an object holding the arguments.
# If not it respond with an exit and an error.
def parseComm():
	if len(sys.argv) < 5:
		print "Not enough arguments"
		print "Command Line should be in form:"
		print "<PROGRAM_NAME> <SERVER_HOST> <SERVER_PORT> <COMMAND> <DATA_PORT> <FILENAME>"

	# Check to see if the path is too short.  This is a very rough way to identify the URL.
	if len(sys.argv[1]) < 4:
		print "SERVER_HOST is incorrect"
		print "Command Line should be in form:"
		print "<PROGRAM_NAME> <SERVER_HOST> <SERVER_PORT> <COMMAND> <DATA_PORT> <FILENAME>"
		sys.exit(1)

	if (int(sys.argv[2]) < 10 or (int(sys.argv[2])) > 65535):
		print "SERVER_PORT is incorrect"
		print "It should be higher than 30000 and lower than 65535"
		sys.exit(1)

	if len(sys.argv[3]) == 0:
		print "Error: COMMAND missing"
		sys.exit(1)
	elif sys.argv[3] != "-l" and sys.argv[3] != "-g" and sys.argv[3] != "-cd":
		print "Error: the command entered was not valid"
		print "Valid commands: -l (list) -g (get file)"
		sys.exit(1)
	elif (int(sys.argv[4]) < 10 or (int(sys.argv[4])) > 65535):
		print "DATA_PORT is incorrect"
		print "It should be higher than 30000 and lower than 65535"
		sys.exit(1)
	elif len(sys.argv) < 6 and sys.argv[3] == "-g":
		print "Error: Filename was not entered"
		sys.exit(1)
	elif len(sys.argv) == 5 and sys.argv[3] == "-l":
		args = commArgs(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], "no_filename")
		return args
	elif len(sys.argv) == 5 and sys.argv[3] == "-cd":
		args = commArgs(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], "no_filename")
		return args
	else:
		args = commArgs(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
		return args

# Sends parsed information to the server on connection P
def sendInfo(object):
	# I am reassembling the object into a single string to send all at once.
	commArgs = pArgs.sHost + " " + pArgs.sPort + " " + pArgs.command + " " + pArgs.qPort + " " + pArgs.filename
	sent = sockP.send(commArgs)
	
# This function
def cReceive(object):
	sockQ = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	sockQ.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

	sockQ.bind((pArgs.sHost, int(pArgs.qPort)))
	sockQ.listen(1)

	conn, addr = sockQ.accept()

	if pArgs.command == "-l":
		while 1:
			data = conn.recv(1024)
			if not data: break
			print data
	else:
		fp = open(pArgs.filename, 'w')
		dFlag = 0

		while 1:
			data = conn.recv(1024)
			s = str(data)
			if s == "The file specified was unable to be opened": 
				print "The file specified was unable to be opened"
				dFlag = 1
				break
			if not data: break
			s = str(data)
			fp.write(s)

		if s != "The file specified was unable to be opened":
			print "File transfer complete"

		sockQ.close()
		fp.close()

		if dFlag == 1:
			os.remove(pArgs.filename)


if __name__ == '__main__':
	pArgs = parseComm()

	sockP = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	sockP.connect((pArgs.sHost, int(pArgs.sPort)))

	sendInfo(pArgs)

	cReceive(pArgs)

	sockP.close()
	exit()
