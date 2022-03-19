/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #1
	Filename: chatserve.c
*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <sys/wait.h>
#include <signal.h>

#define BACKLOG 10		// How many pending connections queue will hold
#define SIZEMESS 500		// Maximum number of chars to accept as a single message.

int quitFlag = 111;	// The quitFlag is a global variable due to it's use in the signalExit handler.
int *quitPtr;		// This is the same for the quitPtr.  
/*
	I realize from the documentation this is not good practice.  I am working on a way to handle signals
	differently and ran out of time.  My goal was to implement multiple threads so the client and server
	could communicate at the same time.
*/

void sigchld_handler(int s)
{
	while(waitpid(-1, NULL, WNOHANG) > 0);
}

void signalExit(int signo)
{
	if (signo == SIGUSR1)
	{
		quitFlag = 0;
	}
	if (signo == SIGINT)
	{
		printf("Thanks for chatting!  Goodbye\n");
		exit(1);
	}
}

void *get_in_addr(struct sockaddr *sa)
{
	if (sa->sa_family == AF_INET)
	{
		return &(((struct sockaddr_in*)sa)->sin_addr);
	}

	return &(((struct sockaddr_in6*)sa)->sin6_addr);
}

// This is the initial setup function which is run to initiate my connections.
// Beej's guide was again used heavily for this implemtation.  
// This will return the file descriptor to sockfd variable for the remainder of the program.
int setupConnection(struct addrinfo *p, int sockfd, struct sigaction sa, struct addrinfo *servinfo, int yes)
{
	for( p = servinfo; p != NULL; p = p->ai_next)
	{
		if ((sockfd = socket(p->ai_family, p->ai_socktype, p->ai_protocol)) == -1)
		{
			perror("server: socket\n");
			continue;
		}

		if (setsockopt(sockfd, SOL_SOCKET, SO_REUSEADDR, &yes, sizeof(int)) == -1)
		{
			perror("setsockopt\n");
			exit(1);
		}

		if (bind(sockfd, p->ai_addr, p->ai_addrlen) == -1)
		{
			close(sockfd);
			perror("server: bind\n");
			continue;
		}

		break;
	}

	if (p == NULL)
	{
		fprintf(stderr, "server: failed to bind\n");
		return 2;
	}

	freeaddrinfo(servinfo);	// all done with this structure

	if (listen(sockfd, BACKLOG) == -1)
	{
		perror("listen");
		exit(1);
	}

	sa.sa_handler = sigchld_handler; 	// reap all dead processes
	sigemptyset(&sa.sa_mask);
	sa.sa_flags = SA_RESTART;
	if (sigaction(SIGCHLD, &sa, NULL) == -1)
	{
		perror("sigaction");
		exit(1);
	}

	return sockfd;
}

void sendMess(int new_fd)
{
	char userIn[510];
	char *fgetsFlag = "initial";
	int lenIn;
	char quitComm[10] = "\\quit";

	while (quitFlag != 0)
	{
		quitFlag = strcmp(quitComm, userIn);
		fgetsFlag = fgets(userIn, SIZEMESS, stdin);
		lenIn = strlen(userIn);
		userIn[lenIn - 1] = '\0';

		if (send(new_fd, userIn, lenIn, MSG_CONFIRM) == -1)
			perror("send");
	}

	printf("Disconnected from client.\n");
	kill(getpid(), SIGUSR1);

	return;
}

// This function receives messages in the child process until the client receives
// the quit message "\quit"
void recvMess(int sockfd, int new_fd, char *handle)
{
	int rc = 2;
	char buf[510];
	int firstMess = 0;
	char quitComm[10] = "\\quit";

	while(rc != 0 && quitFlag != 0)
	{
		close(sockfd);
		rc = 0;

		bzero(buf, sizeof(buf));

		rc = recv(new_fd, buf, 500, 0);

		quitFlag = strcmp(quitComm, buf);

		if (rc != 0 && firstMess == 1)
			printf("%s> %s\n", handle, buf);
		if (quitFlag == 0)
		{
			kill(getppid(), SIGUSR1);
		}

		if (rc == -1)
			perror("recv");

		firstMess = 1;
	}

	return;
}

int main(int argc, char **argv) 
{
	// Beginning code form Beej's Guide
	// http://beej.us/guide/bgnet/output/html/singlepage/bgnet.html

	int sockfd = 0; 
	int new_fd;
	struct addrinfo hints, *servinfo;
	struct addrinfo *p = NULL;
	struct sockaddr_storage their_addr;		// Connectors address information
	socklen_t sin_size;
	struct sigaction sa;
	int yes = 1;
	char s[INET6_ADDRSTRLEN];
	int rv;

	// Variables to handle the user input
	char userIn[510];
	bzero(userIn, sizeof(userIn));
	char handle[50];

	// Variables to use with recv
	int rc, sd;			// Counter for socket bytes

	// Process variables
	pid_t childPID = 111;

	memset(&hints, 0, sizeof hints);
	hints.ai_family = AF_UNSPEC;
	hints.ai_socktype = SOCK_STREAM;
	hints.ai_flags = AI_PASSIVE;		// use my IP

	signal(SIGINT, signalExit);
	signal(SIGUSR1, signalExit);

	if ((rv = getaddrinfo(NULL, argv[argc - 1], &hints, &servinfo)) != 0)
	{
		fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));

		return 1;
	}

	sockfd = setupConnection(p, sockfd, sa, servinfo, yes);

	// End of setup.

	printf("server: waiting for connections...\n");

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

		if (!(childPID = fork()))	// Modified by me to track child PID
		{

			// Receive the handle from the client.  This is hard coded as permitted in the 
			// assignment spec.
			rc = recv(new_fd, handle, 4, 0);
			handle[4] = '\0';

			if (rc <= 0)
				perror("recv handle");

			// Send a message to the client saying you are connected to the server.
			sd = send(new_fd, "Connected to server", 20, MSG_CONFIRM);

			recvMess(sockfd, new_fd, handle);	// Call function to receive messages.
			
			close(new_fd);
			exit(0);
		}

		// sendMess() is called which has a loop to send messages to the client on the 
		// parent process.
		sendMess(new_fd);

		quitFlag = 111;		// This resets the quit flag for the nest connection.
		close(new_fd);	// Closes the old unused socket connection.
	}

	// End code from Beej's guide.
	return 0;
}