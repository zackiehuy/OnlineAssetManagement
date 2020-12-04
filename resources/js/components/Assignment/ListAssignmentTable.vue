<template>
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assignment Table</h3>

                            <div class="card-tools">
                                <router-link to= "/assignments/create" tag="button" class="btn btn-success">
                                    Add New <i class="fas fa-user-plus fa-fw"></i>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="form-row align-items-center">
                                <div class="col-auto my-1">
                                    <label class="mr-sm-2" >Status</label>
                                    <select class="custom-select mr-sm-2" v-model="Status_Field" @change="getSearch">
                                        <option selected value="">All</option>
                                        <option  v-for="status in statuses"
                                                 :key="status.id" :value="status.id">{{ status.name }}</option>
                                    </select>
                                </div>
                                <div class="col-auto my-1">
                                    <label class="mr-sm-2" >Stared Date</label>
                                    <div class="mr-sm-2">
                                        <input type="date" class="form-control" v-model="StartDate_Field" @change="getSearch">
                                    </div>
                                </div>
                                <div class="col-auto my-1">
                                    <label class="mr-sm-2" >End Date</label>
                                    <div class="mr-sm-2">
                                        <input type="date" class="form-control" v-model="EndDate_Field" @change="getSearch">
                                    </div>
                                </div>
                                <div class="col-auto my-1">
                                    <label class="mr-sm-2" >Search</label>
                                    <div class="mr-sm-2">
                                        <input type="text" class="form-control" placeholder="Search user/asset..."
                                                @keyup="getSearch">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th @click="getSort('Id')">Id</th>
                                    <th @click="getSort('User')">User Id</th>
                                    <th @click="getSort('Asset')">Asset Id</th>
                                    <th @click="getSort('StartedDate')">Start Date</th>
                                    <th @click="getSort('EndDate')">End Date</th>
                                    <th @click="getSort('Status')">Status</th>
                                    <th @click="getSort('CreateBy')">Create By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="assignment in list_assignments.data" :key="assignment.id">
                                    <td>{{assignment.id}}</td>
                                    <td>{{assignment.user_id}}</td>
                                    <td>{{assignment.asset_id}}</td>
                                    <td>{{assignment.started_date}}</td>
                                    <td>{{assignment.end_date}}</td>
                                    <td v-if="assignment.status_id == 1"><span class="badge badge-warning">Waiting</span></td>
                                    <td v-else-if="assignment.status_id == 2"><span class="badge badge-success">Assigned</span></td>
                                    <td v-else-if="assignment.status_id == 3"><span class="badge badge-danger">Denied</span></td>
                                    <td v-else-if="assignment.status_id == 4"><span class="badge badge-dark">Returned</span></td>
                                    <td v-else-if="assignment.status_id == 5"><span class="badge badge-info ">Waiting to Return</span></td>
                                    <td>{{assignment.create_by}}</td>
                                    <td v-if="assignment.status_id == 2"><button id="returnbutton" class="btn btn-primary btn-sm" @click="returnRequest(assignment.user_id,assignment.id)">Return</button></td>
                                    <td v-if="assignment.status_id == 5"><button id="" class="btn btn-primary btn-sm" disabled>Return</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <strong> Per Page: </strong>
                                        <select v-on:change="getLimit($event)">
                                            <option value="10" selected>10</option>
                                            <option value="5">5</option>
                                            <option value="3">3</option>
                                            <option value="1">1</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
<!--                                    <base-pagination v-bind:pagination="pagination" v-on:click.native="getListAssignments(pagination.current_page, column, sort)"></base-pagination>-->
                                    <base-pagination v-bind:pagination="list_assignments" v-bind:pages="pages" v-on:click.native="getFilter(column, sort)"></base-pagination>
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

import axios from "axios";
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";

export default {
    components: {BasePagination},
    data(){
        return{
            list_assignments: {
                current_page: 1
            },
            // pagination: {
            //     total: 0,
            //     per_page: 0,
            //     from: 1,
            //     to: 0,
            //     current_page: 1
            // },
            column: 'started_date',
            sort: ' ',
            // page: '1',
            UserId_Field: [],
            AssetId_Field: [],
            StartDate_Field: [],
            EndDate_Field: [],
            Status_Field: [],
            Create_Field: [],
            statuses: [
                {
                    id: 1,
                    name: 'Waiting',
                    value: 1
                },
                {
                    id: 2   ,
                    name: 'Returned',
                    value: 0
                },
                {
                    id: 3   ,
                    name: 'Assigned',
                    value: 0
                }
            ],
            pages: [
                {
                    id: 1,
                    name: 1,
                    value: 1
                },
                {
                    id: 2   ,
                    name: 5,
                    value: 5
                }
            ]

        }
    },
    created(){
        this.getListAssignments();

    },
    methods: {
        getSearch(){
            let url = this.getQuery();
            axios.get(url)
                .then((res, ) => {
                    this.list_assignments = res.data;
                 });
        },

        getLimit(event){
            this.limit = event.target.value;
            this.list_assignments.current_page =1;
            console.log(this.limit);
            this.getFilter();
        },
        getFilter(){
            let url = this.getQuery();
            url += '&page=' + this.list_assignments.current_page;
            axios.get(url)
                .then((res, ) => {
                    this.list_assignments = res.data;
                });
            console.log(url);
        },
        getQuery(){
            let user_id =  this.UserId_Field;
            let user_id_query = '';
            if(Number.isInteger(user_id))
            {
                user_id_query = 'user_id=' + user_id;
            }
            let asset_id =  this.AssetId_Field;
            let asset_id_query = '';
            if(Number.isInteger(asset_id))
            {
                asset_id_query = 'asset_id=' + asset_id;
            }
            let start_date =  this.StartDate_Field;
            let start_date_query = '';
            if(start_date.length > 0)
            {
                start_date_query = '&start_date=' + start_date;
            }
            let end_date =  this.EndDate_Field;
            let end_date_query = '';
            if(end_date.length > 0)
            {
                end_date_query = '&end_date=' + end_date;
            }
            let status =  this.Status_Field;
            let status_query = '';
            if(Number.isInteger(status))
            {
                status_query = '&status_id=' + status;
            }

            let page_query = '&page=' + this.assets.current_page;
            let column_query = '';
            if(this.column)
            {
                column_query = '&column=' + this.column;
            }
            let sort_query = '';
            if(this.sort)
            {
                sort_query = '&sort=' + this.sort;
            }
            let limit_query = '';
            if(this.limit)
            {
                limit_query = '&limit=' + this.limit;
            }
            let url = "api/assignments?" + user_id_query
                + asset_id_query
                + start_date_query
                + end_date_query
                + status_query
                + column_query
                + limit_query
                + sort_query;
            return url;
        },
        returnRequest(userid,assignmentid){

            axios.post(APIConstants.request,{user_id: userid, assignment_id: assignmentid,}  ,{
                headers:{
                "Authorization" : APIConstants.cookie
                },
            }).then(response =>{
                document.getElementById("returnbutton").disabled = true;
                this.updateReturnStatusAssignment(assignmentid);
                console.log('ok return')
            }).catch(error =>{

            })
        },
        updateReturnStatusAssignment(id){
            axios.patch(APIConstants.assign+"/"+id,{status_id: 5},{
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then(response =>{
                console.log('update status OK');
                this.getListAssignments();
                this.$forceUpdate();
            })
        },
        getListAssignments() {
            axios.get(APIConstants.assign+ '?page=' + this.list_assignments.current_page
                + '&column=' + this.column + '&sort=' + this.sort,{
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            })
                .then(response => {
                    this.list_assignments = response.data

                })
                .catch(error => {
                    this.errors = error.response.data.errors.name
                })
        },
    getSort(column){
        if (this.sort === 'asc'){
            this.sort = 'desc';
        }else{
            this.sort = 'asc';
        }
        this.column = column;
        return this.getListAssignments(this.list_assignments.current_page, this.column, this.sort);
    },
        mounted() {
            this.getListAssignments(this.list_assignments.current_page, this.column, this.sort);
        },
    },
}

</script>
<style lang="css" scoped>

</style>
