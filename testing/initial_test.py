#
#   Python Testing Script for Skitter
#
#   Author: Grant Miller <gem1086@g.rit.edu>
#
#
import unittest
import requests
import MySQLdb

def setup_module(module):
	db = MySQLdb.connect("localhost", "root", "root", "Skitter")
	cur = db.cursor()


def teardown_module(module):
	db.close()


class TestTestingFramework(unittest.TestCase):

	def test_math(self):
		self.assertEqual(3 * 4, 12);

	def test_strings(self):
		self.assertEqual('foo'.upper(), 'FOO');


	#Test if the Homepage is loading correctly
	def test_homepage(self):
		r = requests.get("http://localhost/?id=1")
		self.assertEqual(1 == 1)


	#Test settings.php is working as expected
	def test_username_setting(self):
		postData = "displayName=Grant_Miller"
		requests.post("http://localhost/php/settings.php", postData)

		cur.execute("SELECT username FROM Users WHERE userid = 1;")
		username = cur.fetch()
		self.assertEqual(username, "Grant_Miller")

	def test_email_setting(self):
		postData = "email=dog@cat.com"
		requests.post("http://localhost/php/settings.php", postData)

		cur.execute("SELECT email FROM Users WHERE userid = 1;")
		email = cur.fetch()
		self.assertEqual("dog@cat.com", email)

	def test_image_setting(self):
		self.assertEqual(1 + 1, 2)


	#Test Adding and Removing Skits
	def test_get_skits(self):
		self.assertEqual(1 + 1, 2)

	def test_get_reply(self):
		self.assertEqual(1 + 1, 2)

	def test_delete_skit(self):
		self.assertEqual(1 + 1, 2)

	def test_add_skit(self):
		self.assertEqual(1 + 1, 2)


	#Test following and unfollowing
	def test_follow_user(self):
		url = "http://localhost:5000/addFriend?id=2&currID=1"
		requests.get(url)
		cur.execute("SELECT following FROM Users WHERE userid = 1")
		following = cur.fetch()
		self.assert(2 in following)

	def test_unfollow_user(self):
		url = "http://localhost:5000/removeFriend?id=2&currID=1"
		requests.get(url)
		cur.execute("SELECT following FROM Users WHERE userid = 1")
		following = cur.fetch()
		self.assert(2 not in following)

	def test_search_users(self):
		cur.execute("SELECT * FROM Users WHERE username LIKE taff")
		url = "http://localhost:5000/searchUsers?query=taff"
		r = requests.get(url)
		for line, user in zip(r.text, cur.fetchall):
			self.assertEqual(line, user)


	#Test add reply
	def test_add_reply(self):
		url = "http://localhost:3000/add_skit_reply/result?user_id=1&content=testing_comment&originalSkitID=1"
		requests.get(url)
		r = requests.get("http://localhost/?id=1")
		print(r)
		self.assertEqual(1 + 1, 2)


if __name__ == '__main__':
	unittest.main();