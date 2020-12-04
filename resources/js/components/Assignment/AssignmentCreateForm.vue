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
                                <h3 class="text-center">Create Assignment</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal" v-on:submit.prevent="createAssignment">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">user_id</label>

                                        <div class="col-sm-12">
                                            <input type="text" v-model="assignment.user_id"
                                                   class="form-control"
                                                   id="inputName" placeholder=""/>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Asset_id</label>

                                        <div class="col-sm-12">
                                            <input type="text" v-model="assignment.asset_id"
                                                   class="form-control"
                                                   id="inputEmail" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDateOfBirth" class="col-sm-2 control-label">End date</label>

                                        <div class="col-sm-12">
                                            <input type="date" v-model="assignment.end_date"
                                                   class="form-control"
                                                   id="inputDateOfBirth"/>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button type="submit" class="btn btn-success">Submit</button>
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
import axios from "axios";
export default {
    data() {
        return {
            assignment: {
                user_id: '',
                asset_id: '',
                end_date:'',
            }
        }
    },
    methods: {
        createAssignment() {
            axios.post(APIConstants.assign,this.assignment,{
                headers: {
                    "Authorization": APIConstants.cookie
                }
            }).then(response => {
                this.$router.push('/assignments');
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>
