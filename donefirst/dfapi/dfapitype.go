package dfapi

type PatReg struct {
	FirstName 		string
	LastName 		string
	Dob				string
	PhoneNumber		string
	Email 			string
	Address			string
	PhotoName		string
	AppointmentTime	string
}

type Admin struct {
	Username	string
	Password	string
}

type Patients []PatReg