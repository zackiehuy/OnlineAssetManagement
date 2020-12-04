<template id="user">
    <div class="container">
        <div class="row">
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Create User</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal" v-on:submit.prevent="editUser(user.id)">
                                    <div class="form-group" style="background-color: #FFB6C1" v-if="this.errors !== null">
                                        <div v-for="error in errors" :key="error.id">
                                            <div>{{error.toString()}}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Staff Code</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.staff_code" type="text" class="form-control" id="inputStaffCode"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.name" type="text" class="form-control" id="inputName"
                                            v-bind:class="listError['name'] ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.email" type="email" class="form-control" id="inputEmail" placeholder="Email"
                                            v-bind:class="listError['email'] ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDateOfBirth" class="col-sm-2 control-label">Date of birth</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.date_of_birth" type="date" class="form-control" id="inputDateOfBirth"
                                            v-bind:class="listError['date_of_birth'] ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputJoinedDate" class="col-sm-2 control-label">Joined Date</label>

                                        <div class="col-sm-12">
                                            <input v-model="user.joined_date" type="date" class="form-control" id="inputJoinedDate"
                                            v-bind:class="listError['joined_date'] ? 'is-invalid' : ''"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gender</label>

                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="select2-purple">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" id="gender1" name="portalSelect" v-model="user.gender" value="0"
                                                        v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                                                        <label class="form-check-label" for="gender1">Male</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" v-model="user.gender" type="radio" name="gender" id="gender2" value="1"
                                                        v-bind:class="listError.includes('gender') ? 'is-invalid' : ''">
                                                        <label class="form-check-label" for="gender2">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Type</label>

                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="select2-purple">
                                                    <select class="form-control select2" v-model="user.is_admin"
                                                            style="width: 100%;" name="gender">
                                                        <option value="1">Admin</option>
                                                        <option value="0">Staff</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Location</label>

                                        <div class="col-sm-12">
                                            <select class="form-control select2" name="location"  v-model="user.location_id">
                                                <option selected v-bind:value="user.user_location.id">{{user.user_location.name}}</option>
                                                <option v-for="location in locations" :key="location.id"
                                                        v-bind:value="location.id"
                                                        v-if="location.id !== user.user_location.id">{{ location.name }}</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success" >Edit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- end tabs -->
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {APIConstants} from "../../constants";

export default {
    data(){
        return {
            user: {},
            locations: [],
            form: {},
            errors: [],
            listError: []
        }
    },
    methods: {
        getUserById() {
            axios.get(APIConstants.user + '/' + this.$route.params.id,{
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
                this.user = res.data;
            }).catch((error) => {
                console.log(error)
            })
        },
        getLocation(){
            axios.get(APIConstants.location + '/',{
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
                 this.locations = res.data;
            }).catch((error) => {
                console.log(error)
            })
        },
        editUser(){
            axios.put(APIConstants.user + '/' + this.$route.params.id, this.user,{
                headers: {
                    "Authorization" : APIConstants.cookie
                }
            }).then((res) => {
                swal.fire({
                    title: 'Edit successfully!',
                });
                this.$router.push('/users');
            }).catch((error) => {
                this.errors = error.response.data.errors;
                this.listError= Object.keys(error.response.data.errors);
            })
        }
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {
        this.getUserById();
        this.getLocation();
    }
};
</script>
