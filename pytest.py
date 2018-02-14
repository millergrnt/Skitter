#
#   Python Testing Script for Skitter
#
#   Authors: Grant Miller <gem1086@g.rit.edu>
#	     Nick Querci <ncq7286@g.rit.edu>
#
import sys

def func(x):
	return x + 1;

def main():
	print("Beginning Tests:\n\tTesting func(3) == 4:");
	if(func(3) == 4):
		print("\t\t[+]Testing Successful");
		sys.exit(0);
	else:
		print("\t\t[-]Testing Failed");
		sys.exit(1);

if __name__ == '__main__':
	main();
