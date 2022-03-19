package dfapi

import (
	"encoding/json"
	//"fmt"
	"log"
	"net/http"
)

func (p *PatReg) RegisterPatient(r *http.Request) {		
	err := json.NewDecoder(r.Body).Decode(p)
	if err != nil {
		log.Print(err)
	}
	p.insertAppointment()
}

func validateFirstName() {

}

func validateLastName() {

}

func validateDob() {

}

func validatePhoneNumber() {

}

func validateEmail() {

}

func validateAddress() {

}

func validatePhotoName() {

}

func validateAppointmentTime() {

}

func (admin *Admin) LoginAdmin(r *http.Request) string {
	err := json.NewDecoder(r.Body).Decode(admin)
	if err != nil {
		log.Print(err)
		return ""
	}
	if admin.getAdminByUsername() == admin.Password {
		log.Printf("admin: %s logged in\n", admin.Username)
		patients, err := json.Marshal(admin.getAllPatients())
		if err != nil {
			log.Printf("%v\n", err)
			return ""
		}
		return string(patients)
	} else {
		log.Printf("An attempt was made to login with the following credentials, username: %v \t password: %v\n",
			admin.Username, admin.Password)
		return ""
	}
}

func validateUsername() {

}

func validatePassword() {

}