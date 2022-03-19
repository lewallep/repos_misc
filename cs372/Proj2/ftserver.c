/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #2
	Filename: ftserver.c
*/

#include "ftserver.h"

/*	Stuff I need to do
I have to figure out how I want to handle the data port.  My thought is to simply send this to the server on 
the listening port and then open a new socket in a new thread which then opens up the new socket and closes the 
socket when it is finished.

I need to build a function which can be called to initialize the socket.
I think I did this in my original program proj1.

Because this program is a one time pony, I don't need to keep a pool of sockets going.  I can 
make a new socket on a new thread each time I get a new connection.  
*/


int main(int argc, char **argv)
{
	// Beginning code form Beej's Guide
	// http://beej.us/guide/bgnet/output/html/singlepage/bgnet.html
	int sockfd = 0;
	int new_fd;
	struct addrinfo hints, *servinfo;
	struct addrinfo *p = NULL;
	struct sockaddr_storage their_addr;
	socklen_t sin_size;
	struct sigaction sa;
	int yes = 1;
	char s[INET6_ADDRSTRLEN];
	int rv;

	int sockQ;

	memset(&hints, 0, sizeof hints);
	hints.ai_family = AF_UNSPEC;
	hints.ai_socktype = SOCK_STREAM;
	hints.ai_flags = AI_PASSIVE;

	pid_t childPID;

	// Array of pointers to hold the differnet command line parameters from the client
	char *clientArgs[MAX_ARGS];
	initCArgs(clientArgs);

	valComm(argc);	

	if ((rv = getaddrinfo(NULL, argv[argc - 1], &hints, &servinfo)) != 0)
	{
		fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));

		return 1;
	}

	sockfd = setupConnection(p, sockfd, sa, servinfo, yes);

	printf("server: waiting for connection . . .\n");

	// Start of the infinate listening loop to wait for new connections.
	while(1)
	{
		sin_size = sizeof their_addr;
		new_fd = accept(sockfd, (struct sockaddr *)&their_addr, &sin_size);

		if (new_fd == -1)
		{
			perror("accept");
			continue;
		}

		inet_ntop(their_addr.ss_family, get_in_addr((struct sockaddr *)&their_addr), s, sizeof s);
		printf("server: got connection from %s\n", s);

		childPID = fork();

		if (childPID >= 0)			// fork was successful
		{	
			if (childPID == 0) 		// Child process
			{
				getInfo(new_fd, clientArgs);
				sockQ = makeDataSock(clientArgs);
				
				commSelect(sockQ, clientArgs);

				close(sockQ);
			}
			else	// Parent process.
			{
				//	Currently nothing to be done in the parent process.
			}
		}
		else		// Fork Failed
		{
			printf("Fork failed.\n");
		}

		close(new_fd);
	}

	freeCArgs(clientArgs);
	close(sockfd);

	return 0;
}