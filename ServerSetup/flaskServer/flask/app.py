"""Author: Grant Miller <gem1086@gmail.com>
Purpose: run the following and followers portion of Skitter"""

from pprint import pprint
from flask_mysqldb import MySQL
from flask import Flask
from flask import request
APP = Flask(__name__)

MYSQLDB = MySQL()
APP.config['MYSQL_USER'] = 'root'
APP.config['MYSQL_PASSWORD'] = 'root'
APP.config['MYSQL_DB'] = 'Skitter'
APP.config['MYSQL_HOST'] = '172.18.0.2'
MYSQLDB.init_app(APP)


@APP.route("/searchUsers", methods=['GET'])
def users():
    """Searches the database for users that match the GET parameter"""
    username = request.args.get('query')
    cur = MYSQLDB.connection.cursor()

    username = username + "%"

    cur.execute("SELECT userid FROM Users WHERE username LIKE %s;",
                (username,))
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


@APP.route("/addFriend", methods=['GET'])
def add_friend():
    """Adds a friend to the User specified in the GET parameters"""
    id_to_add = request.args.get('id')
    curr_id = request.args.get('currID')

    new_friend_list = get_new_list(curr_id, id_to_add)

    cur = MYSQLDB.connection.cursor()
    cur.execute("UPDATE Users SET following = %s WHERE userid = %s;",
                (new_friend_list, int(curr_id),))
    MYSQLDB.connection.commit()

    return "Success"


def get_new_list(userid, id_to_add):
    """Gets the old friend list from the Database and
    then creates the new list and returns it"""
    cur = MYSQLDB.connection.cursor()
    cur.execute("SELECT following FROM Users WHERE userid = %s;", (userid,))

    data = cur.fetchall()
    new_friend_list = data[0][0] + "," + str(id_to_add)

    return new_friend_list


@APP.route("/removeFriend", methods=['GET'])
def remove_friend():
    """Removes the specified friend from the list"""
    id_to_remove = request.args.get('id')
    curr_id = request.args.get('currID')

    updated_list = update_the_list(curr_id, id_to_remove)

    cur = MYSQLDB.connection.cursor()
    cur.execute("UPDATE Users SET following = %s WHERE userid = %s;",
                (updated_list, curr_id))
    MYSQLDB.connection.commit()

    return ""


def update_the_list(userid, id_to_remove):
    """Updates the list by creating a new list without the id to remove"""
    cur = MYSQLDB.connection.cursor()
    cur.execute("SELECT following FROM Users WHERE userid = %s;", (userid,))

    data = cur.fetchall()
    friend_list = data[0][0]
    if friend_list == "":
        return "Error Removing Friend - You have no friends"

    new_string = ""
    for friend in friend_list.split(','):
        if friend != id_to_remove:
            new_string = new_string + friend + ","
    new_string = new_string[:len(new_string) - 1]

    return new_string
