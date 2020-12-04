<template>
    <div class="container">
        <div class="row">
            <!-- tab -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assigment request form</h3>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">@@@@@@@</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form v-on:submit.prevent="onSubmit">
                                    <div class="form-group" style="background-color: #FFB6C1" v-if="this.errors !== null">
                                        <div v-for="error in errors">
                                            <div>{{error.toString()}}</div>
                                        </div>
                                    </div>
                                    <div id="Cate">
                                        <label>Choose Category: </label>
                                        <select v-model="selected" id="cateName">
                                            <option v-for="cate of list_categories" :key="cate.id" v-bind:value="{ id: cate.id, name: cate.name }">{{ cate.name }}
                                            </option>
                                        </select>
                                        <i v-on:click="addCate" class="fas fa-plus-circle fa-fw"></i>
                                        <h6><strong>Selected Categories:</strong></h6>
                                        <div v-for="sc of selectedCate" class=".col">
                                            <i v-on:click="removeCate(sc)" class="fas fa-minus-circle fa-fw .col"></i>
                                            {{sc.name}}
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Send</button>
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
import axios from 'axios'
import {
    APIConstants
} from "../../constants";

export default {
    name: "CreatedForm",
    data() {
        return {
            range: 0,
            form: {},
            selected: '',
            selectedCate: [],
            list_categories: [],
            errors: {},
        }
    },
    created() {
        this.getListCategories();

    },
    methods: {
        getListCategories() {
            axios.get(APIConstants.category, {
                headers: {
                    "Authorization": APIConstants.cookie
                }
            })
                .then(response => {
                    this.list_categories = response.data
                })
                .catch(error => {

                })
        },
        addCate() {
            if (this.selected != '') {
                this.selectedCate.push(this.selected)
            }
        },
        removeCate(cate) {
            let index = this.selectedCate.indexOf(cate);
            console.log(index);
            this.selectedCate.splice(index, 1);
        },
        onSubmit() {
            let selectedId = []
            this.form['user_id'] = localStorage.getItem('id');
            this.form['created_by'] = this.form['user_id'];
            //get category id
            for (let s = 0; s <= this.selectedCate.length - 1; s++) {
                selectedId.push(this.selectedCate[s].id);
            }
            this.form['asset_category_id'] = selectedId;
            axios.post(APIConstants.request,
                this.form)
                .then(r=>{
                    swal.fire({
                        position: 'middle',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.$router.push('/request/assign-request')
                })
                .catch(error=>{
                    this.errors = error.response.data.errors;
                })
        },
    }
}
</script>

<style scoped>

</style>
