/*
 * Project Group: 13
 * Members: Thomas Osterbind, Philip Lewallen, Adam Lamb
 * Email: osterbit@onid.oregonstate.edu, lewallep@onid.oregonstate.edu, lamba@onid.oregonstate.edu
 * Class: CS325-400
 * Assignment: 1
 */

#include <stdio.h>
#include <unistd.h>

void enum1(int *arr, int arrSize)
{
  int i, j, k, tempSum;
  int bestSumVal = arr[0];
  int bestSumBeg = 0;
  int bestSumEnd = 0;

  for (i = 0; i < arrSize; ++i)
  {
    for (j = i; j < arrSize; ++j)
    {
      tempSum = 0;
      for (k = i; k <= j; ++k)
        tempSum = tempSum + arr[k];
      if (tempSum*tempSum < bestSumVal*bestSumVal)
      {
        //update bestVals
        bestSumVal = tempSum;
        bestSumBeg = i;
        bestSumEnd = j;
      }
    }

  }

  printf("ENUM 1\nclosest to zero:\nSum between indexes %d and %d\nvalue: %d\n", bestSumBeg, bestSumEnd, bestSumVal);
}

void enum2(int *arr, int arrSize)
{
  int i, j, tempSum;
  int prevSum = 0;//added
  int bestSumVal = arr[0];
  int bestSumBeg = 0;
  int bestSumEnd = 0;

  for (i = 0; i < arrSize; ++i)
  {
    for (j = i; j < arrSize; ++j)
    {
      tempSum = arr[j] + prevSum; //changed   
      //for (k = i; k <= j; ++k)
        //tempSum = tempSum + arr[k];
      if (tempSum*tempSum < bestSumVal*bestSumVal)
      {
        //update bestVals
        bestSumVal = tempSum;
        bestSumBeg = i;
        bestSumEnd = j;
      }
      prevSum = tempSum;//added
    }
    prevSum = 0; //added
  }
  printf("ENUM 2\nclosest to zero:\nSum between indexes %d and %d\nvalue: %d\n", bestSumBeg, bestSumEnd, bestSumVal);
}

int main (int argc, char **argv) {
  /*TODO: randomly generate arrays to test*/
  /*TODO: output and save timing data*/
  int arr[] = {-80, -99, 55, 960, -36, 44, 88, 92};
  int arrSize = sizeof(arr)/ sizeof(*arr);

  enum1(arr, arrSize);
  enum2(arr, arrSize);
 
  return 0;
}
