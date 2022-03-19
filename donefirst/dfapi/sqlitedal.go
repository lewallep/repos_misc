package dfapi

import (
	"database/sql"
	"fmt"
	"log"

	_ "github.com/mattn/go-sqlite3"
)

var db 		*sql.DB
var errDb	error
const dbType = "sqlite3"
const dbName = "./donefirst.db"


func (p *PatReg) insertAppointment() {
	db, errDb = sql.Open(dbType, dbName)
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	stmt, err := db.Prepare(
		`INSERT INTO patients (firstName, lastName, dob, phoneNumber, email, 
			address, photoName, appointmentTime)
		VALUES(?, ?, ?, ?, ?, ?, ?, ?)`)
	if err != nil {
		panic(err)
	}
	stmt.Exec(p.FirstName, p.LastName, p.Dob, p.PhoneNumber, p.Email, 
		p.Address, p.PhotoName, p.AppointmentTime)

	log.Printf("Patient registered with appointment time of: %s\n", p.AppointmentTime)
}


func (admin *Admin) getAdminByUsername() string {
	var passworddb string

	db, errDb = sql.Open(dbType, dbName)
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	rows, err := db.Query(`SELECT password FROM admins WHERE username=?`, admin.Username)
	if err != nil {
		log.Printf("%v\n", err)
	}
	for rows.Next() {
		rows.Scan(&passworddb)
	}
	fmt.Println("placeholder")

	return passworddb
}


func (admin *Admin) getAllPatients() []PatReg {
	var patients Patients
	var id int

	db, errDb = sql.Open(dbType, dbName)
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	rows, err := db.Query(`SELECT * FROM patients`)
	if err != nil {
		log.Printf("%v\n", err)
	}

	for rows.Next() {
		var pat PatReg
		rows.Scan(&id, &pat.FirstName, &pat.LastName, &pat.Dob, &pat.PhoneNumber, &pat.Email, 
			&pat.Address, &pat.PhotoName, &pat.AppointmentTime)
		patients = append(patients, pat)
	}

	return patients
}