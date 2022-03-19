import { Component, Vue } from "vue-property-decorator";
import axios from 'axios'

@Component({ components: {} })

export default class PatientRegistration extends Vue {
  private firstName = "";
  private lastName = "";
  private dob = "";
  private phoneNumber = "";
  private email = "";
  private address = "";
  private appointmentTime = "";


  registerPatient() {
    console.log("registerPatient() called.");
  }
}