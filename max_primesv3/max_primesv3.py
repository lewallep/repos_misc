#!/usr/bin/python
import sys, math, time, os
import multiprocessing as mp
from multiprocessing import Process, Queue

class primeObject:
	def __init__(self, evalNumber, isPrime):
		self.evalNumber = evalNumber
		self.isPrime = isPrime

def calcPrimesErastos(numProcs, calcRange, endTime, threadCount, q):
	# This is a place holder initializer until I setup the multiple threads.
	# At this time each individual thread will have a number which I will 
	# multiply by the calc range to 

	# This index is to help me keep track of which index I am adding which prime to
	# Essentially all it is is a glorified counter for my lists. 
	evalNumber = (threadCount * calcRange) + 1	#Used to put the numbers into the lists for record keeping. Will be replaced.
	#Will be used in the loops to ensure I have not gone beyond the list extents.
		#0 * calcRange
	listLength = 0
	highestCurNum = 0
	curPossiblePrime = 0

	# outermost while loop of evaluation will start here
	# Append to the list as needed.  Move the initialization loops into this outer while loop.	
	while time.time() < endTime:
		primeList = []
		divisor = 3
		startIndex = 0
		endIndex = startIndex + int(calcRange / 2)
		evalIndex = 0
		#print ("Start Index: " + str(startIndex))
		#print ("End Index: " + str(endIndex))

		if evalNumber == 1:
			for i in range(startIndex, endIndex - 1):
				if evalNumber == 1:
					primeList.append(primeObject(evalNumber, True))
					primeList.append(primeObject(2, True))
					evalNumber += 2			
				primeList.append(primeObject(evalNumber, True))	
				evalNumber += 2
		else:
			for i in range(startIndex, endIndex):			
				primeList.append(primeObject(evalNumber, True))	
				evalNumber += 2
		
		if primeList[0].evalNumber < 3:
			print ("Prime found: " + str(1))
			print ("Prime found: " + str(2))
		
		listLength = len(primeList)

		highestCurNum = primeList[listLength - 1].evalNumber
		#print ("highestCurNum: " + str(highestCurNum))
		#print ("listLength: " + str(listLength))

		while divisor < highestCurNum:
			while evalIndex < listLength:
				curPossiblePrime = int(primeList[evalIndex].evalNumber)
				if (curPossiblePrime / divisor) > 1 and (curPossiblePrime % divisor) == 0 \
				and (curPossiblePrime != divisor):
					primeList[evalIndex].isPrime = False
				evalIndex += 1
			evalIndex = 0
			divisor += 2

		highestCurNum += (numProcs * calcRange) - calcRange
		evalNumber += (numProcs * calcRange) - calcRange

		if time.time() < endTime:
			#for i in range(startIndex, listLength):
			#	print ("Index: " + str(i) + " evalNumber: " + str(primeList[i].evalNumber) \
			#		+ " isPrime: " + str(primeList[i].isPrime))

			#print ("highestCurNum: " + str(highestCurNum))
			#print ("numProcs: " + str(numProcs))
			# Refer list iterator
			r = listLength - 1
			# There seems to be a glitch with this piece of code
			# At certain times it seems the memory cannot be accessed even though
			# the list has the proper length.
			while primeList[r].isPrime == False:
				r -= 1
			print("Highest prime this number range in thread: " + str(threadCount) \
				+ " is: " + str(primeList[r].evalNumber))
			q.put(primeList[r].evalNumber)

			#print ("The highest prime this list is: " + str(primeList[r].evalNumber))
			#print ("highestCurNum: " + str(highestCurNum))
			#print ("evalNumber: " + str(evalNumber))
			#print ("time: " + (str(time.time())))
		# End outermost while loop.


def main(args):
	runTime = float(args[2])
	calcRange = int(args[1])
	
	# Initializing the timer as soon as possible.  Must first grab args.
	endTime = time.time() + runTime

	numProcs = mp.cpu_count()
	timeLeft = runTime
	highestPrimeList = []
	highestPrime = 0
	whileCounter = 0

	q = Queue()

	for i in range(0, numProcs):
		p = Process(target = calcPrimesErastos, args = (numProcs, calcRange, endTime, i, q))
		p.start()

	while timeLeft > 0:
		print ()
		print ("There are: " + str(timeLeft) + " seconds left to find a higher prime.")
		print ()
		time.sleep(1)
		timeLeft -= 1

	while not q.empty():
		highestPrimeList.append(q.get())	

	listLength = len(highestPrimeList)

	while numProcs > whileCounter:
		if highestPrimeList[listLength - whileCounter - 1] > highestPrime:
			highestPrime = highestPrimeList[listLength - whileCounter - 1]
		whileCounter += 1

	print ()
	print ("Time is up!")
	print ()
	print ("The highest overall prime is: " + str(highestPrime))
	print ()

	for i in range(0, numProcs):
		p.join()

	q.close()

if __name__ == '__main__':
	main(sys.argv)