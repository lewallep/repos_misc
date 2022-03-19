package dfapi

import (
	"database/sql"
	"testing"

	_ "github.com/mattn/go-sqlite3"
)

func TestInitializeDb(t *testing.T) {
	database, err := sql.Open("sqlite3", "./donefirsttest.db")
	if err != nil {
		t.Errorf("Error creating the donefirsttest.db")
	}

	dropStmt, errDrop := database.Prepare("DROP TABLE IF EXISTS tpatient")
	if errDrop != nil {
		t.Errorf("%s", errDrop)
	}
	dropStmt.Exec()

	statement, errStmt := database.Prepare(
		`CREATE TABLE tpatient (
			id INTEGER PRIMARY KEY,
			firstname TEXT NOT NULL,
			lastname TEXT NOT NULL,
			dob TEXT NOT NULL,
			phoneNumber TEXT NOT NULL,
			email TEXT NOT NULL,
			address TEXT NOT NULL,
			photoName TEXT NOT NULL,
			appointmentTime	TEXT NOT NULL)`)
	statement.Exec()
	if errStmt != nil {
		t.Errorf("There was an error creating the tpatient table.")
	}

	stmtInsert, errInsert := database.Prepare(
		`INSERT INTO tpatient (
			firstname,
			lastname,
			dob,
			phoneNumber,
			email,
			address,
			photoName)
		VALUES (?, ?, ?, ?, ?, ?, ?)`)
	if errInsert != nil {
		t.Errorf("error while preparing first insert.")
	}

	var ifirstName, ilastName, idob, iphoneNumber, iemail, iaddress, iphotoName, iappointmentTime string
	ifirstName = "Ricky"
	ilastName = "Bobby"
	idob = "1971/07/16"
	iphoneNumber = "111-111-1111"
	iemail = "rickybobbynum1@rickybobby.com"
	iaddress = "17240 Connor Quay Court, Cornelius, N.C., 28031"
	iphotoName = "dummyfilename.jpeg"
	iappointmentTime = "2021/04/19 10:00"

	stmtInsert.Exec(ifirstName, ilastName, idob, iphoneNumber, iemail, iaddress, iphotoName, iappointmentTime)	

	var id int
	var firstName, lastName, dob, phoneNumber, email, address, photoName, appointmentTime string
	rows, _ := database.Query("SELECT id, firstName, lastName, dob, phoneNumber, email, address, photoName FROM tpatient")

	// The test database tpatient should only have a single record at this point regardless of what the development
	// database has.
	for rows.Next() {
		rows.Scan(&id, &firstName, &lastName, &dob, &phoneNumber, &email, &address, &photoName)
	}

	if id != 1 {
		t.Errorf("incorrect id: %d\tThe value should be 1.\n", id)
	}
	if firstName != ifirstName {
		t.Errorf("incorrect firstname:  %s\tThe value should be: %s", firstName, ifirstName)
	}
	if lastName != ilastName {
		t.Errorf("incorrect firstname: %s\tThe value should be: %s", lastName, ilastName)
	}
	if dob != idob {
		t.Errorf("incorrect dob: %s\tThe value should be: %s\n", dob, idob)
	}
	if phoneNumber != iphoneNumber {
		t.Errorf("incorrect phoneNumber: %s\tThe value should be: %s\n", phoneNumber, iphoneNumber)
	}
	if email != iemail {
		t.Errorf("incorrect email: %s\tThe value should be: %s\n", email, iemail)
	}
	if address != iaddress {
		t.Errorf("incorrect address: %s\tThe value should be: %s\n", address, iaddress)
	}
	if photoName != iphotoName {
		t.Errorf("incorrect photoName: %s\tThe value should be: %s\n", photoName, iphotoName)
	}
	if appointmentTime != iappointmentTime {
		t.Errorf("incorrect appointmentTime: %s\tThe value should be: %s\n", appointmentTime, iappointmentTime)
	}
}

