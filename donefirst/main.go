package main

import (
	"fmt"
	"net/http"

	 "donefirst/dfapi"
)

//TODO
// I need to validate that the patient has uploaded 

// let's get two patients into the development db
// one default administrator
// the default admin's credentials should be documented into the readme.
// 

func main() {
	testver := func(w http.ResponseWriter, r *http.Request) {
		switch r.Method {
		case "GET":
			fmt.Fprintf(w, "Version 0.0.1")
		default:
			fmt.Fprintf(w, "Somehow found a non type request.")
		}
	}

	// Example to show functions as arguments.
	http.HandleFunc("/", testver)

	// An example to show anonymous functions.
	http.HandleFunc("/register", func(w http.ResponseWriter, r *http.Request) {
		var patient dfapi.PatReg		
		w.Header().Set("Access-Control-Allow-Headers", "*")
		w.Header().Set("Content-Type", "application/json")
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "*")
		
		switch r.Method {
		case "GET":
			fmt.Fprintf(w, "Found a get method on h3.")
		case "POST":
			patient.RegisterPatient(r)
		default:
			fmt.Fprintf(w, "h3 - somehow the default method was used.")
		}
	})

	http.HandleFunc("/login", func(w http.ResponseWriter, r *http.Request) {
		var admin dfapi.Admin
		w.Header().Set("Access-Control-Allow-Headers", "*")
		w.Header().Set("Content-Type", "application/json")
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "*")

		switch r.Method {
		case "POST":
			resp := admin.LoginAdmin(r)
			fmt.Fprintf(w, resp)
		default:
			fmt.Fprintf(w, "login default case")
		}
	})

	// http.HandleFunc("/viewpatients", func(w http.ResponseWriter, r *http.Request) {
	// 	switch r.Method {
	// 	case "GET":
	// 		w.Header().Set("Content-Type", "application/json")
	// 	default:
	// 		fmt.Fprintf(w, "viewpatients default case")
	// 	}
	// })
	fmt.Println("donefirst api running on port 8081.")
	http.ListenAndServe(":8081", nil)

}