 # Project Group: 13
 # Members: Thomas Osterbind, Philip Lewallen, Adam Lamb
 # Email: osterbit@onid.oregonstate.edu, lewallep@onid.oregonstate.edu, lamba@onid.oregonstate.edu
 # Class: CS325-400
 # Assignment: 1

CC=gcc
DEBUG=-g
CFLAGS=$(DEBUG) -Wall
PROGS=close2zero

all: $(PROGS)

close2zero: close2zero.o
	$(CC) $(CFLAGS) -o close2zero close2zero.o

close2zero.o: close2zero.c
	$(CC) $(CFLAGS) -c close2zero.c

clean:
	rm -f $(PROGS) *.o *~