package dfapi

import (
	"database/sql"
	"fmt"

	_ "github.com/mattn/go-sqlite3"
)

// Diagnostic helper function to print out contents of db
func PrintAllPatients() {
	var firstname, lastname string
	var id int

	db, errDb = sql.Open(dbType, dbName)
	if errDb != nil {
		panic(errDb)
	}
	defer db.Close()

	rows, err := db.Query(`SELECT id, firstName, lastName FROM patients`)
	if err != nil {
		panic(err)
	}
	for rows.Next() {
		rows.Scan(&id, &firstname, &lastname)
		fmt.Printf("id %v firstname: %v, lastname %v\n", id, firstname, lastname)
	}
}
