Call examples:

{"add":"","call":"KC2UEZ","contact":"K2JJI","band":"20M","mode":"PHONE","class":"5A","section":"NNY","comment":"","gota":"0","gota_mentor":"0","points":"2"}
{"delete":"ID"}
{"look":"ID"}
{"edit":"ID","call":"KC2UEZ","contact":"K2JJI","band":"20M","mode":"PHONE","class":"5A","section":"NNY","comment":"","gota":"0","gota_mentor":"0","points":"2"}
{"list":""}
{"last":"COUNT"}
{"dupe":"","mode":"PHONE","contact":"K2JJI","band":"20M","gota":"0"}
{"score":""}

api_add.php: Add a contact in to the database.
api_delete.php: Moves contact to the 'Deleted' table and then delete contact by ID from the 'Event' table. Replace ID by a number.
api_look.php: Looks up a contact by ID. Replace ID by a number.
api_edit.php: Replaces the data for that ID. Replace ID by a number.
api_list.php: List all the contacts.
api_last.php: List the last COUNT countacts.
api_dupe.php: Check if a contact is already stored. A match is based on: mode, contact, band and if it is gota or not.
api_score.php: Returns the current score.

Database structure:
 Query used to create the Event table:
  CREATE TABLE Event(id INTEGER PRIMARY KEY AUTOINCREMENT, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL, points INTEGER NOT NULL, time DATETIME)

 Query used to create the Deleted table:
  CREATE TABLE Deleted(id INTEGER PRIMARY KEY AUTOINCREMENT, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL, points INTEGER NOT NULL, time DATETIME)