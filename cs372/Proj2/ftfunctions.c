/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #2
	Filename: ftfunctions.c
*/

#include "ftserver.h"

void sigchld_handler()
{
	while(waitpid(-1, NULL, WNOHANG) > 0);
}

/*	This function counts the arguments on the command line to ensure the proper 
	amount of arguments.
*/
void valComm(int argc)
{
	if(argc != 2)
	{
		printf("Error: The server requires one argument.\n");
		printf("Arg1: Proggram name  Arg2: PORTNUM\n");
		exit(2);
	}

	return;
}

void *get_in_addr(struct sockaddr *sa)
{
	if (sa->sa_family == AF_INET)
	{
		return &(((struct sockaddr_in*)sa)->sin_addr);
	}

	return &(((struct sockaddr_in6*)sa)->sin6_addr);
}

// This code is from Beej's guide.  I have redcated it to be held in a function.
// http://beej.us/guide/bgnet/output/html/singlepage/bgnet.html
int setupConnection(struct addrinfo *p, int sockfd, struct sigaction sa, struct addrinfo *servinfo, int yes)
{
	for (p = servinfo; p != NULL; p = p->ai_next)
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

/*	
	MAX_ARGS
	ARG_LEN
*/
void initCArgs(char *clientArgs[])
{
	assert(clientArgs != NULL);

	int i;	// Loop counter variable

	for (i = 0; i < MAX_ARGS; i++)
	{
		clientArgs[i] = (char*)malloc(sizeof(char) * ARG_LEN);
	}

	for (i = 0; i < MAX_ARGS; i++)
	{
		bzero(clientArgs[i], sizeof(clientArgs[i]));
	}

	return;
}

// This function receives the command from the client.
void getInfo(int new_fd, char *clientArgs[])
{
	// Initiate variables.
	int rc =  9999;
	char clientMess[200];
	char *token = NULL;
	int i = 0;

	// zero out the array to ensure no stray characters.
	bzero(clientMess, sizeof(clientMess));

	// Receive the command line on connection P
	rc = recv(new_fd, clientMess, 200, 0);

	token = strtok(clientMess, " \n");

	if (token != NULL)
	{
		strcpy(clientArgs[i], token);
	}

	while (token != NULL)
	{
		if (token != NULL)
		{
			strcpy(clientArgs[i], token);
			token = strtok(NULL, " \n");
		}
		i++;
	}

	return;
}

// Frees the mmeory from the command line parseing.
void freeCArgs(char *clientArgs[])
{
	assert(clientArgs != NULL);

	int i;	// Loop counter variable.

	for (i = 0; i < MAX_ARGS; i++)
	{
		free(clientArgs[i]);
	}

	return;
}

/*	In this function I will make a new socket on the port designated by the client.
	The socket is only designed to live for the duration of the function.  
	This function is from the client example of Beej's guide
	http://beej.us/guide/bgnet/examples/client.c
*/
int makeDataSock(char *clientArgs[])
{
	assert(clientArgs[0] != NULL);

	// Declare variables for the new socket
	int sockQ;	// numbytes;
	// char buf[MAXBUFSIZE];
	struct addrinfo hints, *servinfo, *p;
	int rv;
	char s[INET6_ADDRSTRLEN];

	memset(&hints, 0, sizeof hints);
	hints.ai_family = AF_UNSPEC;
	hints.ai_socktype = SOCK_STREAM;

	if ((rv = getaddrinfo(clientArgs[0], clientArgs[3], &hints, &servinfo)) != 0)
	{
		fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
	}

	p = servinfo;

	if ((sockQ = socket(p->ai_family, p->ai_socktype, p->ai_protocol)) == -1)
	{
		perror("client: socket");
	}

	if (connect(sockQ, p->ai_addr, p->ai_addrlen) == -1)
	{
		close(sockQ);
		perror("client: connect");
	}

	if (p == NULL)
	{
		fprintf(stderr, "client: failed to connect\n");
	}

	inet_ntop(p->ai_family, get_in_addr((struct sockaddr *)p->ai_addr), s, sizeof s);
	printf("server: connecting to %s\n", s);

	freeaddrinfo(servinfo);	// All done with this structure

	return sockQ;
}

void commPrint(char *clientArgs[])
{
	int i;

	for (i = 0; i < MAX_ARGS; i++)
	{
		printf("clientArgs %d: %s\n", i, clientArgs[i]);
	}

	return;
}

int sendStdout(int sockQ, char *clientArgs[])
{
	// How to read the file size
	// http://www.linuxquestions.org/questions/programming-9/how-to-get-size-of-file-in-c-183360/
	FILE *fp;
	int status;
	char buf[MAXBUFSIZE];
	int bufLen = 0;
	int sd;

	fp = popen("ls", "r");
	if (fp == NULL)
	    /* Handle error */;

	while (fgets(buf, MAXBUFSIZE, fp) != NULL)
	{
		bufLen = strlen(buf);
	    sd = send(sockQ, buf, bufLen, MSG_CONFIRM);
	}

	status = pclose(fp);

	return sd;
}

void sendFile(int sockQ, char *clientArgs[])
{
	FILE *fp = NULL;
	int sd, totalSent;
	char errMess[] = "The file specified was unable to be opened";
	int fileSize;
	int fRead;
	char buf[MAXBUFSIZE];

	fp = fopen(clientArgs[4], "r");
	if (fp == NULL)		// Check to see if the file is available.
	{
		int errMessLen = strlen(errMess);

		sd = send(sockQ, errMess, errMessLen, MSG_CONFIRM);
	}
	else	// Found the file and sending the file to the client.
	{
		// Test the size of the file and print it to the server console
		struct stat st;
		stat(clientArgs[4], &st);
		fileSize = st.st_size;
		totalSent = 0;
		sd = 0;

		// Initialize my file descriptor and open the file.
		int fd = open(clientArgs[4], O_RDONLY, 0755);

		// Read through the entire file sending each buf to the client.
		while(fileSize >= totalSent)
		{		
			fRead = read(fd, buf, MAXBUFSIZE - 1);
			sd = send(sockQ, buf, MAXBUFSIZE - 1, MSG_CONFIRM);
			bzero(buf, sizeof(buf));
			totalSent = totalSent + sd;
		}
	}

	return;
}

void commSelect(int sockQ, char *clientArgs[])
{
	int sd;

	if (strcmp(clientArgs[2], "-l") == 0)
	{
		sd = sendStdout(sockQ, clientArgs);
	}
	else if (strcmp(clientArgs[2], "-g") == 0)
	{
		sendFile(sockQ, clientArgs);
	}

	return;
}