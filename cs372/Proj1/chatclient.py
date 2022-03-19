#!/usr/bin/python

#	Name: Philip Lewallen
#	Class: CS372 Introduction to Networks
#	Programming Assignment #1
#	Filename: chatclient.py

import socket, sys
from multiprocessing import Process
import os, signal

def handler(signum, stack):
	print "Bye Bye"
	os._exit(1)

def f():
	lengthRC = 5;
	rc = "no messages yet"

	rc = sock.recv(500)
	lengthRC = len(rc)

	while lengthRC != 0 and rc != "\quit\0":
		print rc
		rc = sock.recv(500)
		lengthRC = len(rc)

	sock.close()
	os.kill(os.getppid(), signal.SIGUSR1)
	
def contact():
	bytesSent = sock.send("Phil\0")
	received = sock.recv(20)

	print received

def sendMess():
	userInput = "Phil, seriously, you have not initialized userInput"
	quitTest = "notquittingyet"

	while quitTest != "\quit":
		sys.stdout.write(">> ")
		userInput = str.strip(sys.stdin.readline())
		bytesSent = sock.send(userInput)

		quitTest = userInput
		if quitTest == "\quit":
			print "Goodbye."
			break

# Get the hostname and port to pass to the socket constructor
HOST = sys.argv[1]
PORT = int(sys.argv[2])

# setup a signal handler to terminate my client when receiving a message.
signal.signal(signal.SIGUSR1, handler)

sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
sock.connect((HOST, PORT))

contact()

p = Process(target = f)
p.start()

sendMess()

p.terminate()
sock.close()
exit()