# max_primesv3
The third version of the find primes program using python.  It utilizes multiple cores and Erastones Sieve.

## Installation
This script requires Python 3.

## Useage
Run it on your favorite command line.  This program is OS agnostic.

1. The first is an integer which is to specificy the size of the set of numbers to calculate on each processor.  For instance a size of 100 will send 0 through 99 to the first process and then 100 through 199 to the next process.

2. The second command line parameter is how long the program will run for before quitting.  This will receive a floating point number.

* NOTE: By default the program records the amount of available CPU's and uses all of them for the duration of the program.

## Future Improvements
The program utilizes a class to append to lists.  This class keeps track of the number evaluated and if this number is either a prime number or not.  These lists are destroyed each iteration.  It appears this could be causing a bottleneck regarding on chip memory.  

The most successful run of this program used a calc size (first command line parameter) of 100.  This would produce a prime in the range of 73,000.  For future versions I would need to reduce the memory footprint.  The first method to do this is to return to the use of lists with only boolean values.  This could in theory be done using a counter which will add to the current index of the array and this counter will represnet which elements in the array correspond to which particular number being evaluated.

