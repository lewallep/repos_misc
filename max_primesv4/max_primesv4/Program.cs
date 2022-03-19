using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace max_primesv4
{
    class Program
    {
        static void Main(string[] args)
        {
            int sieveLength = int.Parse(args[1]);
            float runTime = float.Parse(args[2]);
            long curNumber = 0;
            int indexCounter = 0;
            long divisor = 3;
            int sieveSegmentCounter = 1;
            long sieveStartIndex = 0;
                
            
            bool [] primeSieve = new bool[sieveLength];

            Console.WriteLine("The sieveLength is: {0} and runTime is: {1}.", sieveLength, runTime); 
            
            // Initialize all of the array values to true.  They will be marked off as the 
            // Array is traversed.
            for (var i = 0; i < sieveLength; i++)
            {
                primeSieve[i] = true;
            }

            primeSieve[0] = false;

            for (var i = 4; i < sieveLength; i += 2)
            {
                primeSieve[i] = false;
            }


            while (divisor < 9)
            {
                for (var i = divisor; i < sieveLength; i += divisor)
                {
                    if (i > divisor)
                    {
                        primeSieve[i] = false;
                    }
                }

                divisor += 2;
            }

            for (var i = 0; i < sieveLength; i++)
            {
                Console.WriteLine("curNumber: {0}    isPrime {1}", curNumber, primeSieve[i]);
                curNumber++;
            }

            // Replace this a timer event.
            while (sieveSegmentCounter < 1000)
            {
                divisor = 3;
                sieveSegmentCounter += 1;

                for (var i = 0; i < sieveLength; i++)
                {
                    primeSieve[i] = true;
                }

                for (var i = 0; i < sieveLength; i += 2)
                {
                    primeSieve[i] = false;
                }

                Console.WriteLine("The currentNumber before analysis is: {0}", curNumber);

                while (divisor < sieveLength + curNumber)
                {
                    // The divisor will start at what the modulos form the curNumber.
                    // For instance when we are dividing by 3 we need to start at curNumber
                    // 102 which is index 2. 
                    // However when the divisor is 5 we need to start at index 0.
                    // 
                    sieveStartIndex = divisor - (curNumber % divisor);
                    //Console.WriteLine("sieveStartIndex: {0}", sieveStartIndex);

                    for (var i = sieveStartIndex; i < sieveLength; i += divisor)
                    {
                        if (i > divisor)
                        {
                            primeSieve[i] = false;
                        }
                    }

                    divisor += 2;
                }

                for (var i = 0; i < sieveLength; i++)
                {
            
                    Console.WriteLine("curNumber: {0}    isPrime {1}", curNumber, primeSieve[i]);
                    curNumber++;
                }
            }
            

        }
    }
}
