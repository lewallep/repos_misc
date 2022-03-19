package main

import (
	"database/sql"
	"os"

	_ "github.com/mattn/go-sqlite3"
)

func main() {
	deleteDb()
	createPatientsDb()
	createAdminsDb()
}


func deleteDb() {
	_ = os.Remove("donefirst.db") // Swallow error as the file might not exist.

}

// Seperate out the patients users from the admins to not waste any column space
// Another goal is to make the different functionality of the users as self documenting as possible.
func createPatientsDb() {
	db, errDb := sql.Open("sqlite3", "./donefirst.db")
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	stmt, err := db.Prepare(
		`CREATE TABLE IF NOT EXISTS patients (
			id INTEGER PRIMARY KEY,
			firstName TEXT NOT NULL,
			lastName TEXT NOT NULL,
			dob TEXT NOT NULL,
			phoneNumber TEXT NOT NULL,
			email TEXT NOT NULL,
			address TEXT NOT NULL,
			photoName TEXT NOT NULL,
			appointmentTime TEXT NOT NULL)`)

	if err != nil {
		panic(err)
	}
	stmt.Exec()

	stmt, err = db.Prepare(
		`INSERT INTO patients (
			firstName, lastName, dob, phoneNumber, email, address, photoName, appointmentTime) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?)`)
	if err != nil {
		panic(err)
	}
	stmt.Exec("Ricky", "Bobby", "1976/07/16", "111-111-1111", "rickybobbynum1@rickybobby.com",
		"17240 Connor Quay Court, Cornelius, N.C., 28031", "rickysselfportrait.jpg", "2021/04/19 10:00")

	stmt.Exec("patient2", "patient2lastname", "1908/01/02", "222-222-2222", "patient2@gmail.com", 
		"222 Second Ave, SecondCity, Illinois, 57846", "usertwophoto.jpg", "2021/04/22 10:00")

	stmt.Exec("patient3", "patient3lastname", "2000/01/23", "333-333-3333", "patient3@gmail.com",
		"333 Third St, Bend, OR, 23846", "patient3photo.jpg", "2021/04/23: 11:00")
}

func createAdminsDb() {
	db, errDb := sql.Open("sqlite3", "./donefirst.db")
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	stmt, err := db.Prepare(
		`CREATE TABLE IF NOT EXISTS admins (
			id INTEGER PRIMARY KEY,
			username TEXT NOT NULL,
			password TEXT NOT NULL)`)
	if err != nil {
		panic(err)
	}
	stmt.Exec()

	stmt, err = db.Prepare(`INSERT INTO admins (username, password) VALUES (?, ?)`)
	if err != nil {
		panic(err)
	}
	stmt.Exec("default", "Password1$")
}