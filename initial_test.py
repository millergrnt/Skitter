#
#   Python Testing Script for Skitter
#
#   Authors: Grant Miller <gem1086@g.rit.edu>
#	     Nick Querci <ncq7286@g.rit.edu>
#
import unittest

class TestTestingFramework(unittest.TestCase):

	def test_math(self):
		self.assertEqual(3 * 4, 12);

	def test_strings(self):
		self.assertEqual('foo'.upper(), 'FOO');

if __name__ == '__main__':
	unittest.main();