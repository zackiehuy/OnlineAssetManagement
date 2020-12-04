<template>
    <div class="container">
        <div class="row">
            <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Update Asset</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <div class="alert alert-danger" v-if="errors !== null    ">
                                    <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="(v, k) in errors" :key="k">
                                            {{ v.toString() }}
                                        </li>
                                    </ul>

                                </div>
                                <form class="form-horizontal" @submit.prevent="createAsset()" >
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"
                                        >Asset Code</label>
                                        <div class="col-sm-12">
                                            <input
                                                class="form-control"
                                                placeholder="Asset code"
                                                v-model="asset.asset_code"
                                                name="asset_code"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label"
                                        >Asset Name</label
                                        >
                                        <div class="col-sm-12">
                                            <input
                                                class="form-control"
                                                id="inputName"
                                                placeholder="Name"
                                                v-model="asset.name"
                                                name="name"
                                                v-bind:class="error.includes('name') ? 'is-invalid' : ''"

                                            />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                        >Location</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" style="width: 100%;"
                                                    v-model="asset.location_id" id="location"
                                                    v-bind:class="error.includes('location_id') ? 'is-invalid' : ''"
                                            >
                                                <option v-for="location in locations"
                                                        :key="location.id" :value="location.id"
                                                >{{ location.name }}</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label"
                                        >Description</label
                                        >
                                        <div class="col-sm-12">
                                          <textarea
                                              class="form-control"
                                              id="inputExperience"
                                              placeholder="Description"
                                              v-model="asset.description"
                                              name="desc"
                                          ></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label"
                                        >Installed Date</label
                                        >

                                        <div class="col-sm-12">
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="password"
                                                placeholder="Passport"
                                                v-model="asset.installed_date"
                                                name="install"
                                                v-bind:class="error.includes('installed_date') ? 'is-invalid' : ''"
                                            />
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                        >Category</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" style="width: 100%;"
                                                    v-model="asset.asset_category_id" id="category"
                                                    v-bind:class="error.includes('asset_category_id') ? 'is-invalid' : ''"
                                            >
                                                <option v-for="category in categories"
                                                        :key="category.id" :value="category.id"
                                                >{{ category.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label label-default="" for="">Specification</label>
                                            <select class="form-control select2" style="width: 100%;"
                                                    v-model="selected" id=""
                                            >
                                                <option v-for="cate_spec in cate_specs"
                                                        :key="cate_spec.id" :value="{id: cate_spec.id, value: cate_spec.default_value }"
                                                >{{ cate_spec.default_value }}</option>

                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label label-default="" for="username">&nbsp; </label>
                                            <div>
                                                <button class="btn btn-success" v-on:click.prevent="addSpec">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" v-for="data in selectedSpec">
                                        <div class="form-group col-lg-6">
                                            <input  class="form-control"
                                                type="text" v-bind:value="data.value"
                                                @keyup="saveSpec(data,$event.target.value)">

                                        </div>
                                        <div class="form-group col-lg-2">
                                            <button class="btn btn-danger" v-on:click.prevent="removeSpec(data)">-</button>

                                        </div>
                                    </div>


                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success">
                                                Submit
                                            </button>
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
import {APIConstants} from "../../constants";
export default {
    data() {
        return {
            categories: [],
            locations: [],
            errors : null,
            asset : {},
            error: [],
            cate_specs: [],
            selected: '',
            selectedSpec: [],
            idSpec: [],
        }
    },
    created() {
        this.fetchCategories()
        this.fetchLocations()
        this.fetchCateSpec()
        this.fetchAsset();
    },
    methods:{
        createAsset(){
            for(let i=0; i<= this.selectedSpec.length-1; i++){
                let str1 = "specification";
                let str2 = this.selectedSpec[i].id;
                let res = str1.concat(str2);
                this.asset[res] = this.selectedSpec[i].value;
            }
                axios.post('/api/assets', this.asset, {
                    headers: {
                        "Authorization" : APIConstants.cookie
                    },
                })
                .then(response => {
                    swal.fire({
                        icon: 'success',
                        title: 'Asset has been created',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.$router.push('/assets');

                })
                .catch(e => {
                    this.errors = e.response.data.errors;
                   this.error = Object.keys(this.errors);
                })
        },
        fetchCategories() {
            axios.get("/api/categories", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.categories = res.data;
            });
        },
        fetchLocations() {
            axios.get("/api/locations", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.locations = res.data;
            });
        },
        fetchAsset() {
            axios.get("/api/assets/" + this.$route.params.id, {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.asset = res.data;
            });
        },
        fetchCateSpec() {
            axios.get("/api/cate-spec", {
                headers: {
                    "Authorization" : APIConstants.cookie
                },
            }).then((res) => {
                this.cate_specs = res.data;
                console.log(res.data)

            });

        },
        addSpec(){
            this.selectedSpec.push(this.selected)
        },
        removeSpec(id){
            let index = this.selectedSpec.indexOf(id);
            this.selectedSpec.splice(index,1);

        },
        saveSpec(id, value){
            let index = this.selectedSpec.indexOf(id);
            this.selectedSpec[index].value = value;
            console.log(this.selectedSpec[index]);
        }


    }
}
</script>

<style scoped>

</style>
