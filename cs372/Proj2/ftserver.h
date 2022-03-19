/*
	Name: Philip Lewallen
	Class: CS372 Introduction to Networks
	Programming Assignment #2
	Filename: ftserver.h
*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/wait.h>
#include <sys/stat.h>
#include <netinet/in.h>
#include <netdb.h>
#include <arpa/inet.h>
#include <signal.h>
#include <assert.h>
#include <fcntl.h>

#define BACKLOG 10	// Defines how many pending connections queue will hold
#define MAX_ARGS 10		// Defines how many arguements can be stored.
#define ARG_LEN	100		// Maximum Length of an argument.  I figure the URL's could be fairly long.
#define MAXBUFSIZE 1024	// Maximum number of bytes to send in one send command.

void sigchld_handler();
void valComm(int argc);
void *get_in_addr(struct sockaddr *sa);
int setupConnection(struct addrinfo *p, int sockfd, struct sigaction sa, struct addrinfo *servinfo, int yes);
void initCArgs(char *clientArgs[]);
void getInfo(int new_fd, char *clientArgs[]);
void freeCArgs(char *clientArgs[]);
int makeDataSock(char *clientArgs[]);
void commPrint(char *clientArgs[]);
int sendStdout(int sockQ, char *clientArgs[]);
void sendFile(int sockQ, char *clientArgs[]);
void commSelect(int sockQ, char *clientArgs[]);
