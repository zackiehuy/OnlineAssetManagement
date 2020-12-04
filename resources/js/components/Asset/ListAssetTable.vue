<style scoped>
.dropdown-menu {
    width:150px
}

.dropdown-menu.columns-3 {
    min-width: 500px;
}
.dropdown-menu li a {
    padding: 5px 15px;
    font-weight: 300;
}
.multi-column-dropdown {
    list-style: none;
}
.multi-column-dropdown li a {
    display: block;
    clear: both;
    line-height: 1.428571429;
    color: #333;
    white-space: normal;
}
.multi-column-dropdown li a:hover {
    text-decoration: none;
    color: #262626;
    background-color: #f5f5f5;
}

@media (max-width: 767px) {
    .dropdown-menu.multi-column {
        min-width: 240px !important;
        overflow-x: hidden;
    }
}
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assets Table</h3>

                        <div class="card-tools" v-if="checkRoles === 1">
                            <router-link to= "/assets/create" tag="button" class="btn btn-success">
                                Add New <i class="fas fa-user-plus fa-fw"></i>
                            </router-link>
                        </div>
                        <div class="card-tools" v-else>
                            <router-link to= "/assets/create" tag="button" class="btn btn-success">
                                Request Asset <i class="fas fa-plus fa-fw"></i>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-row align-items-center">
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >Category</label>
                                <select class="custom-select mr-sm-2" v-model="checkedNames" @change="getSearch">
                                    <option selected value="">All</option>
                                    <option v-bind:id="category.id"
                                            v-bind:value="category.id"
                                            v-for="category in categories">{{ category.name }}</option>
                                </select>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2">Location</label>
                                <select class="custom-select mr-sm-2" v-model="locationFields" @change="getSearch">
                                    <option selected value="">All</option>
                                    <option v-for="location in locations"
                                            v-bind:id="location.id"
                                            v-bind:value="location.id"
                                    >{{ location.name }}</option>
                                </select>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >RAM</label>
                                <select class="custom-select mr-sm-2" v-model="ramFields" @change="getSearch">
                                    <option selected value="">All</option>
                                    <option  v-for="ram in rams"
                                             :key="ram.id" :value="ram.name">{{ ram.name }}</option>
                                </select>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >CPU</label>
                                <select class="custom-select mr-sm-2" v-model="cpuFields" @change="getSearch">
                                    <option selected value="">All</option>
                                    <option  v-for="cpu in CPUs"
                                             :key="cpu.id" :value="cpu.name">{{ cpu.name }}</option>
                                </select>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >Status</label>
                                <select class="custom-select mr-sm-2" v-model="statusFields" @change="getSearch">
                                    <option selected value="">All</option>
                                    <option  v-for="status in statuses"
                                             :key="status.id" :value="status.id">{{ status.name }}</option>
                                </select>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >Installed Date</label>
                                <div class="mr-sm-2">
                                    <input type="date" class="form-control" v-model="dateFields" @change="getSearch">
                                </div>
                            </div>
                            <div class="col-auto my-1">
                                <label class="mr-sm-2" >Search</label>
                                <div class="mr-sm-2">
                                    <input type="text" class="form-control" placeholder="Search asset name..."
                                           v-model="assetNameCodeFields" @keyup="getSearch">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Asset Code</th>
                                <th>Asset Name</th>
                                <th v-on:click="getSort('AssetLocation')">Location
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetCategory')">Category
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th v-on:click="getSort('AssetInstalledDate')">Installed Date
                                    <i class="fas fa-sort"></i>
                                </th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(asset, index) in assets.data" :key="asset.id">
                                <td>{{ index+1 }}</td>
                                <td>{{asset.asset_code}}</td>
                                <td>{{asset.name}}</td>
                                <td >{{asset.asset_location.name}}</td>
                                <td>{{asset.asset_category.name}}</td>
                                <td >{{asset.installed_date}}
                                <td v-if="asset.assignment"><span class="badge badge-warning">Assigned</span></td>
                                <td v-else><span class="badge badge-success">Available</span></td>
                                <td>
                                    <div v-if="checkRoles === 1">
                                        <a href="" v-on:click.prevent="detailAsset(asset)">Detail</a>
                                        <router-link :to="{name: 'edit_asset', params: { id: asset.id }}">Edit</router-link>
                                        <a href="#" @click="deleteAsset(asset.id)">Delete</a>
                                    </div>
                                    <div v-else>
                                        <a href="" v-on:click.prevent="detailAsset(asset)">Detail</a>
                                        <a href="#" v-on:click.prevent="postReturn(asset.assignment)">Return</a>
                                    </div>
                                </td>

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
                                <base-pagination v-bind:pagination="assets" v-bind:pages="pages" v-on:click.native="getFilter(column, sort)"></base-pagination>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Asset details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Asset Name</label>
                            <p>{{ detail.name }}</p>
                        </div>
                        <div class="form-group" v-if="detailAssignment">
                            <label class="col-form-label">Assigned</label>

                            <p> <strong><i>Name:</i></strong> {{ detailAssignment.name }}</p>
                            <p> <strong><i>Staff Code:</i></strong> {{ detailAssignment.staff_code }}</p>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Asset Code:</label>
                            <p>{{ detail.asset_code }}</p>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Asset Location:</label>
                            <p>{{detailLocation}}</p>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Specs:</label>
                            <div v-if="detail.asset_detail">
                                <p v-for="spec in detail.asset_detail">{{spec.value}}</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Send message</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BaseButton from "../BaseComponent/BaseButton";
import {APIConstants} from "../../constants";
import BasePagination from "../BaseComponent/BasePagination";

export default {
    name: 'ListAssetTable',
    components: {BasePagination, BaseButton},
    computed: {
        checkRoles(){
            return  this.$store.getters.getUser.is_admin;
        }
    },
    data() {
        return {
            column: '',
            assets: {},
            detail: {},
            detailLocation: '',
            detailAssignment: '',
            categories: [],
            locations: [],
            assetNameCodeFields: null,
            sort: '',
            checkedNames: [],
            locationFields: [],
            dateFields: [],
            ramFields: [],
            cpuFields: [],
            statusFields: [],
            limit: 0,
            rams: [
                {
                    id: 1,
                    name: '8GB',
                    value: '8GB'
                },{
                    id: 2,
                    name: '16GB',
                    value: '16GB'
                },{
                    id: 3,
                    name: '32GB',
                    value: '32GB'
                },
            ],
            CPUs: [
                {
                    id: 1,
                    name: 'i5',
                    value: 'i5'
                },{
                    id: 2,
                    name: 'i7',
                    value: 'i7'
                },{
                    id: 3,
                    name: 'i9',
                    value: 'i9'
                },
            ],
            statuses: [
                {
                    id: 1,
                    name: 'Assigned',
                    value: 1
                },
                {
                    id: 2   ,
                    name: 'Available',
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
        };
    },
    created() {
        this.fetchCategories()
        this.fetchLocations()
        this.getResults();
    },
    methods: {
        getResults() {
            axios.get(APIConstants.asset)
                .then((res) => {
                this.assets = res.data;
            }).catch(e=> console.log(e));
        },
        getQuery(){
            let cate =  this.checkedNames;
            let cate_query = '';
            if(Number.isInteger(cate))
            {
                cate_query = 'asset_category_id=' + cate;
            }

            let location =  this.locationFields;
            let location_query = '';
            if(Number.isInteger(location))
            {
                location_query = '&location_id=' + location;
            }
            let date =  this.dateFields;
            let date_query = '';
            if(date.length > 0)
            {
                date_query = '&installed_date=' + date;
            }
            let name_or_code =  this.assetNameCodeFields;
            let name_code_query = '';
            if(name_or_code)
            {
                name_code_query = '&name=' + name_or_code;
            }

            let ram =  this.ramFields;
            let ram_query = '';
            if(ram.length > 0)
            {
                ram_query = '&spec=' + ram;
            }
            let cpu =  this.cpuFields;
            let cpu_query = '';
            if(cpu.length > 0)
            {
                cpu_query = '&spec=' + cpu;
            }
            let status =  this.statusFields;
            let status_query = '';
            if(Number.isInteger(status))
            {
                status_query = '&status=' + status;
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
            let url = "api/assets?" + cate_query
                + location_query
                + name_code_query
                + date_query
                + cpu_query
                + status_query
                + ram_query
                + column_query
                + limit_query
                + sort_query;
            return url;
        },
        getSearch(){
            let url = this.getQuery();
            axios.get(url, {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res, ) => {
                this.assets = res.data;
            });
        },
        getFilter(){
            let url = this.getQuery();
            url += '&page=' + this.assets.current_page;
            axios.get(url, {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res, ) => {
                this.assets = res.data;
            });
            console.log(url);
        },
        deleteAsset(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete("/api/assets/" + id, {
                            headers: {
                                "Authorization" : APIConstants.cookie
                            },
                        })
                        .then((response) => {
                            swal.fire({
                                icon: 'success',
                                title: 'Delete asset successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.getResults();
                        })
                        .catch((error) => {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Cannot delete this asset because it currently assigned!',
                            })
                        });
                }
            });
        },
        updateOrderDirection() {
            if (this.query.order_direction === "desc") {
                this.query.order_direction = "asc";
            } else {
                this.query.order_direction = "desc";
            }
        },

        fetchCategories() {
            axios.get("api/categories", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.categories = res.data;
            });
        },
        fetchLocations() {
            axios.get("api/locations", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.locations = res.data;
            });
        },
        getSort(column){
            if (this.sort === 'asc'){
                this.sort = 'desc';
            }else{
                this.sort = 'asc';
            }
            this.column = column;
            this.getFilter();

        },

        uniqueCheck(e){
            this.additional_grouped = [];
            if (e.target.checked) {
                this.additional_grouped.push(e.target.value);
            }
        },
        detailAsset(asset){
            console.log(asset.assignment);
            this.detail = asset;
            this.detailLocation = asset.asset_location.name;
            if(asset.assignment){
                this.detailAssignment = asset.assignment.user_assignment;
            }
            else {
                this.detailAssignment = null;
            }

            $('.modal').modal('show');
            // this.form.fill(asset);
        },
        getLimit(event){
            this.limit = event.target.value;
            this.assets.current_page =1;
            console.log(this.limit);
            this.getFilter();
        },
        postReturn(data){
            console.log(data);
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form= {
                        "user_id": data.user_id,
                        "assignment_id": data.id,
                        "created_by": data.user_id
                    }
                    console.log(form);
                    axios.post(APIConstants.request, {
                        form
                    }).then(r=>{
                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    })

                }
            })
        }

    },
};
</script>
