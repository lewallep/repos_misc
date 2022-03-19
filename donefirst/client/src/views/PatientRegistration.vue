<template>
  <v-container>
    <v-row justify="center">
      <h1 >Done Patient Registration coding challenge</h1>
    </v-row>
    <br />
    <br />
    <v-row justify="center">
      <h2>To register for an appointment please enter your information below</h2>
    </v-row>
    <br />
    <br />
    
    <v-form>
      <v-row justify="center">
        <v-col cols="5">
          <v-text-field
            v-model="firstName"
            placeholder="First Name"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="lastName"
            placeholder="Last Name"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="dob"
            placeholder="Date of Birth"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="phoneNumber"
            placeholder="Phone Number"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="email"
            placeholder="Email"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="address"
            placeholder="Address"
            solo
            clearable
          ></v-text-field>
          <v-text-field
            v-model="appointmentTime"
            placeholder="Desired Appointment Time"
            solo
            clearable
          ></v-text-field>
          <v-file-input
            solo
            clearable
            accept="image/png, image/jpeg, image/bmp"
            prepend-icon="mdi-camera"
            label="Drivers License"
          ></v-file-input>
        </v-col>
      </v-row>
    </v-form>
    <v-row justify="center">
      <v-btn
        x-large
        color="primary"
      >Please register me</v-btn>
    </v-row>
  </v-container>
</template>

<script lang="ts" src="./PatientRegistration.ts"/>