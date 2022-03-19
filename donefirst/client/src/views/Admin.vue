<template>
  <v-container>
    <v-row justify="center">
      <h1>Hello admins</h1>
    </v-row>
    <br />
    <br />
    <v-row justify="center">
      <h4>Please enter your admin credentials to see a list of patients and their appointments</h4>
    </v-row>
    <v-row justify="center">
      <v-col cols="3">
        <v-text-field
          v-model="username"
          placeholder="Username"
          solo
          clearable
        ></v-text-field>
        <v-text-field
          v-model="password"
          placeholder="Password"
          solo
          clearable
        ></v-text-field>
      </v-col>
    </v-row>
    <br />
    <br />
    <v-row justify="center">
      
    </v-row>
    <v-row justify="center">
      <v-data-table
        :headers="header"
        :items="patients"
        item-key="id"
        class="elevation-2"
      ></v-data-table>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import axios from 'axios'

@Component({ components: {} })

export default class Admin extends Vue {
  private username = "";
  private password = "";

  private headers: Record<string, unknown>[] = [
    {}
  ];

  private patients: Record<string, unknown>[] = [
    {}
  ];

  mounted() {
    axios.post('/login', {
      "username": "default",
      "password": "Password1$"
    })
    .then((res) => {
      console.log(res);
      // call helper function to create headers
    })
  }



}
</script>