<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets Information</h3>
                        <div class="card-tools">
                        <router-link to= "/assets/" tag="button" class="btn btn-success">
                            Back to list <i class="fa fa-reply" aria-hidden="true"></i>
                        </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class=" col-sm-4" id="settings">
                                    <div class="form-group">
                                        <label for="a">Asset Code</label>
                                        <input type="text" class="form-control" use :value = "this.asset_history.asset_code" id="a" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="b">Asset Name</label>
                                        <input type="text" class="form-control" use :value = "asset_history.name" id="b" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="c">Location</label>
                                        <input type="text" class="form-control" use :value = "asset_history.location" id="c" readonly>
                                    </div>
                                </div>
                                <div class=" col-sm-4" >
                                    <div class="form-group">
                                        <label for="d">Asset Category</label>
                                        <input type="text" class="form-control" use :value = "asset_history.category" id="d" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="e">Create by</label>
                                        <input type="text" class="form-control" use :value = "asset_history.create_by" id="e" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="f">Create at</label>
                                        <input type="text" class="form-control" use :value = "asset_history.created_at" id="f" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets History</h3>
                    </div>
                    <!-- /.card-header -->
                    <div v-if="assignmentIsNull === null">
                        <h3 class ="p-4">This asset don't have any assignment</h3>
                    </div>
                    <div v-else>
                        <div class="card-body table-responsive p-0" >
                            <div class="container mx-1" >
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="z">Assign to</label>
                                            <input v-model="search.AssignTo" v-on:keyup="filterAssignment()" type="text" class="form-control"  id="z" >
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="j">Assign by</label>
                                            <select id = "j" class="form-control" v-model="search.AssignBy" v-on:change="filterAssignment()">
                                                <option selected value=""></option>
                                                <option v-for="list of assign_by" :value="list.id">{{list.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >Started date</label>
                                            <div class = "container">
                                                <div class = "row">
                                                    <select class = "form-control" id ="m" v-model="search.StartedDate" v-on:change="filterAssignment()">
                                                        <option selected value =""></option>
                                                        <option v-for="list of started_date" :value="list">{{list }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label >End date</label>
                                            <div class = "container">
                                                <div class = "row">
                                                    <select class = "form-control" id ="n" v-model="search.EndDate" v-on:change="filterAssignment()">
                                                        <option selected value =""></option>
                                                        <option v-for="list of end_date" :value="list">{{list }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <button style="margin: 30px" @click="reset" class="btn btn-success"> Reset</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <div class="card-body">
                                </div>
                                <tbody>
                                <tr>
                                    <th>#</th>
                                    <th v-on:click="getSort('AssignTo')">
                                        Assign to
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('AssignBy')">
                                        Assign by
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('StartedDate')">
                                        Started date
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('EndDate')">
                                        End date
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th v-on:click="getSort('Status')">
                                        Status
                                        <i class="fas fa-sort"></i>
                                    </th>
                                </tr>
                                <tr v-for="(list,index) of assignment.data" >
                                    <td>{{index+1}}</td>
                                    <td>{{list.assign_to}}</td>
                                    <td>{{list.assign_by.name}}</td>
                                    <td>{{list.started_date | formatDate}} </td>
                                    <td>{{list.end_date | formatDate}}</td>
                                    <td>{{list.status}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="col-sm-6">
                                <p><strong>Per Page:</strong>
                                    <select v-model="limitchange" v-on:change="getLimit()">
                                        <option value="10" selected>10</option>
                                        <option value="5">5</option>
                                        <option value="3">3</option>
                                        <option value="1">1</option>
                                    </select>
                                </p>
                            </div>
                            <base-pagination v-bind:pagination="assignment" v-on:click.native="ListAssetHistory(pagination.current_page,column,sort,limit)"></base-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";
export default {
    components: {BasePagination},
    data(){
        return{
            asset_history: [],
            pagination: {
                current_page : 1,
            },
            column: 'StartedDate',
            sort: 'asc',
            assignment: [],
            assignmentIsNull: 1,
            limit: 2,
            limitchange: 1,
            assign_by: [],
            status: [],
            started_date: [],
            end_date: [],
            assign_to: "",
            url : null,
            baseUrl: `${APIConstants.asset}/${this.$route.params.id}/history?`,
            filterAssignTo: "",
            assignBy: "",
            filterAssignBy: "",
            StartedDate: "",
            EndDate: "",
            search: {
            },
            total: null,
            value : '',
        }
    },
    methods: {
        ListAssetHistory: function(page, column, sort,limit) {
            if(this.url == null)
            {
                this.url = this.baseUrl;
            }
            axios.get(`${this.url}page=${page}&column=${column}&sort=${sort}&limit=${limit}`,{
                headers: {
                    "Authorization": APIConstants.cookie
                }
            })
                .then(response =>  {
                    console.log(response);
                    if(response.data == 0)
                    {
                        alert("This Asset is not available");
                        this.$router.push('/assets/')
                    }
                    else
                    {
                        if(response.data.assignment === 1)
                        {
                            alert("This Asset does not belong to you");
                            this.$router.push('/assets/')
                        }
                        else if(response.data.assignment === null)
                        {
                            this.assignmentIsNull = null;
                        }
                    }
                    this.asset_history = response.data;
                    if(response.data.assignmentAsset != null)
                    {
                        this.assignment = response.data.assignmentAsset.assignment;
                        this.pagination = response.data.assignmentAsset.assignment;
                    }
                    else
                    {
                        this.assignment = [];
                        this.pagination = {};
                    }
                    if(this.total == null)
                    {
                        this.total = this.pagination.total;
                        this.getFilter(this.total);
                    }
                }).catch((error) => {
                console.log(error)
            })
        },
        getFilter(total){
            axios.get(`${this.url}limit=${total}`,{
                headers: {
                    "Authorization": APIConstants.cookie
                }
            })
                .then(response =>  {
                    this.assign_by = [];
                    response.data.assignmentAsset.assignment.data.forEach(element => this.assign_by.push(element.assign_by));
                    this.status = response.data.status;
                    this.end_date = response.data.assignmentAsset.end_date;
                    this.started_date = response.data.assignmentAsset.started_date;
                }).catch((error) => {
                console.log(error)
            })
        },
        getSort(column) {
            if (this.sort == 'asc'){
                this.sort = 'desc';
            }else{
                this.sort = 'asc';
            }
            this.column = column;
            this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limit);
        },
        getLimit(){
            this.limit = this.limitchange;
            this.pagination.current_page = 1;
            this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limitchange);
        },
        filterAssignment(){
            this.url = this.baseUrl;
            for (const [key, value] of Object.entries(this.search)) {
                if(value !== '')
                {
                        this.url += `${key}=${value}&`;
                }
            }
            this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limit);
        },
        reset()
        {
            this.search = {};
            this.url = this.baseUrl;
            this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limit);
        }
    },
    mounted() {
        this.ListAssetHistory(this.pagination.current_page, this.column, this.sort, this.limit);
    }
}
</script>
