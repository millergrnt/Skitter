from flask import Flask
from flask import request
from flask.ext.mysql import MySQL
from pprint import pprint
app = Flask(__name__)

mysql = MySQL()
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = 'root'
app.config['MYSQL_DATABASE_DB'] = 'Skitter'
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
mysql.init_app(app)

#Searches the database for users that match the GET parameter
@app.route("/searchUsers", methods=['GET'])
def users():
	username = request.args.get('query')
	conn = mysql.connect()
	cur = conn.cursor()

	username = username + "%"

	cur.execute("SELECT userid FROM Users WHERE username LIKE %s;", (username,))
	data = cur.fetchall()
	pprint(data)

	final = ""
	i = 0
	while i < len(data):
		final += ", "
		final += str(data[i][0])
		i += 1
	final = final[2:]

	return final


#Adds a friend to the User specified in the GET parameters
@app.route("/addFriend", methods=['GET'])
def addFriend():
	idToAdd = request.args.get('id')
	currID = request.args.get('currID')

	newFriendList = getNewList(currID, idToAdd)

	conn = mysql.connect()
	cur = conn.cursor()
	cur.execute("UPDATE Users SET following = %s WHERE userid = %s;", (newFriendList, int(currID),))
	conn.commit()

	return "Success"

#Gets the old friend list from the Database and then creates the new list and returns it
def getNewList(userid, idToAdd):
	conn = mysql.connect()
	cur = conn.cursor()
	cur.execute("SELECT following FROM Users WHERE userid = %s;", (userid,))

	data = cur.fetchall()
	newFriendList = data[0][0] + "," + str(idToAdd)

	return newFriendList


#Removes the specified friend from the list
@app.route("/removeFriend", methods=['GET'])
def removeFriend():
	idToRemove = request.args.get('id')
	currID = request.args.get('currID')

	updatedList = updateList(currID, idToRemove)

	conn = mysql.connect()
	cur = conn.cursor()
	cur.execute("UPDATE Users SET following = %s WHERE userid = %s;", (updatedList, currID))
	conn.commit()
	data = cur.fetchone()

	return ""

def updateList(userid, idToRemove):
	conn = mysql.connect()
	cur = conn.cursor()
	cur.execute("SELECT following FROM Users WHERE userid = %s;", (userid,))

	data = cur.fetchall()
	friendList = data[0][0]
	if(friendList == ""):
		return "Error Removing Friend - You have no friends"
		
	newString = ""
	for x in friendList.split(','):
		if x != idToRemove:
			newString = newString + x + ","
	newString = newString[:len(newString) - 1]

	return newString