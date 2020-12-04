<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users Table</h3>
            <div class="card-tools">
              <base-button @click.native="navigateToCreate">
                Add New <i class="fas fa-user-plus fa-fw"></i>
              </base-button>
                <button style="margin: 30px" @click="reset" class="btn btn-success"> Reset</button>
            </div>
          </div>
          <!-- Search form -->
          <!-- /.card-header -->
          <div class="card-secondary">
            <form class="form-horizontal">
              <div class="form-group">
                <label>Staff Code</label>
                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                       v-model="search.staff_code" v-on:keyup="searchUsers">
              </div>
              <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                v-model="search.name" v-on:keyup="searchUsers">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" placeholder="Search" aria-label="Search"
                       v-model="search.email" v-on:keyup="searchUsers">
              </div>
              <div class="form-group">
                <label>Location</label>
                  <select class="form-control" v-model="search.location_id"
                          style="width: 100%;" name="location" v-on:change="searchUsers">
                    <option v-bind:value="location.id"
                            v-for="location in locations" :key="location.id">
                      {{ location.name }}
                    </option>
                  </select>
              </div>
              <div class="form-group">
                <label>Gender</label>
                  <select class="form-control select2" v-model="search.gender"
                          style="width: 100%;" name="gender" v-on:change="searchUsers">
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                  </select>
              </div>
              <div class="form-group">
                <label>Type</label>
                    <select class="form-control select2" v-model="search.is_admin"
                            style="width: 100%;" name="type" v-on:change="searchUsers">
                      <option value="0">Admin</option>
                      <option value="1">Staff</option>
                    </select>
              </div>
              <div class="form-group" style="margin: 0 10px">
                <label>Date of birth</label>
                <input type="date" v-model="search.date_of_birth"
                      class="form-control date-format"
                      id="inputDateOfBirth" v-on:change="searchUsers"/>
              </div>
              <div class="form-group" style="margin: 0 10px">
                <label>Joined date</label>
                <input type="date" v-model="search.joined_date"
                      class="form-control date-format"
                      id="inputJoinedDate" v-on:change="searchUsers"/>
              </div>
            </form>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
              <tr>
                <th @click="getSort('StaffCode')">Staff Code</th>
                <th @click="getSort('UserName')">Name</th>
                <th @click="getSort('UserEmail')">Email</th>
                <th @click="getSort('UserLocation')">Location</th>
                <th @click="getSort('UserType')">Type</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody v-for="user in users.data" :key="user.id">
              <tr>
                <th scope="row">{{ user.staff_code }}</th>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.user_location.name }}</td>
                <td v-if="user.is_admin == 1">Admin</td>
                <td v-else>Staff</td>
                <td>
                  <router-link v-bind:to="{ name: 'ShowUsers', params: { id: user.id }}">Detail</router-link>
                  <router-link v-bind:to="{ name: 'HistoryUsers', params: { id: user.id }}">History</router-link>
                  <a href="#" @click="displayNotify(user.id)">Delete</a>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-6">
                <p><strong>Per Page:</strong>
                    <select v-on:change="getLimit($event)">
                        <option value="10" selected>10</option>
                        <option value="5">5</option>
                        <option value="3">3</option>
                        <option value="1">1</option>
                    </select>
                  </p>
              </div>
              <div class="col-sm-6">
                <base-pagination v-bind:pagination="users" v-on:click.native="getUsers()"></base-pagination>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
import BaseButton from "../BaseComponent/BaseButton";

export default {
  components: {BasePagination, BaseButton},
  data() {
    return {
      users: {},
      locations: {},
      column: 'StaffCode',
      sort: 'asc',
      search: {},
      limit: ''
    }
  },
  created() {
    this.getLocation();
    this.getUsers();
  },
  methods: {
    getUrl(){
      let query = APIConstants.user + '?&column=' + this.column + '&sort=' + this.sort + '&limit=' + this.limit;
      $.each(this.search, function (key, value) {
        if (value !== '') {
          query += '&' + key + '=' + value;
        }
      })
      return query;
    },
    getLocation() {
      axios.get(APIConstants.location).then((res) => {
        this.locations = res.data;
        console.log(this.locations)
      }).catch((error) => {
        console.log(error)
      })
    },
    getUsers() {
      let query = this.getUrl();
      query += '&page=' + this.users.current_page;
      console.log(query);
      axios.get(query, {
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
        this.users = res.data;
      })
    },
    searchUsers() {
      let query = this.getUrl();
      axios.get(query, {
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
          this.users = res.data;
      })
    },
    deleteUser(id) {
      axios.delete(APIConstants.user + id, {
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
        this.getUsers(this.page, this.column, this.sort);
      }).catch((error) => {
        console.log(error)
      })
    },
    displayNotify(id) {
      swal.fire({
        title: 'Are you sure to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.value) {
          this.deleteUser(id);
          swal.fire(
            'Deleted successfully'
          );
        }
      })
    },
    getSort(column) {
      if (this.sort == 'asc') {
        this.sort = 'desc';
      } else {
        this.sort = 'asc';
      }
      this.column = column;
      return this.getUsers();
    },
    navigateToCreate() {
      this.$router.push('users/create');
    },
    getLimit(event){
      this.limit = event.target.value;
      this.getUsers();
    },
    reset(){
        $("input, select").val("");
        this.search = {};
        this.getUsers();
    }
  }
}
</script>

<style scoped>
.form-group {
  width: 15%;
  float: left;
  margin: 5px;
}
</style>
