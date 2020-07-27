<template>
    <div class="page-wrapper">
        <load :active.sync="loading"
        :can-cancel="false"
        :is-full-page="true"></load>
        <sidenav :user="user" />
        <div class="page-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-0">Branch</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                                <h6 v-if="update == false" class="card-title mb-0">Create New Branch</h6>
                                <h6 v-if="update == true" class="card-title mb-0">Update Branch</h6>
                                <!-- <div class="dropdown mb-2">
                                    <button class="btn p-0" type="button" id="dropdownMenuButton5"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right"
                                        aria-labelledby="dropdownMenuButton5">
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i
                                                data-feather="eye" class="icon-sm mr-2"></i> <span
                                                class="">View</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i
                                                data-feather="edit-2" class="icon-sm mr-2"></i> <span
                                                class="">Edit</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i
                                                data-feather="trash" class="icon-sm mr-2"></i> <span
                                                class="">Delete</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i
                                                data-feather="printer" class="icon-sm mr-2"></i> <span
                                                class="">Print</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i
                                                data-feather="download" class="icon-sm mr-2"></i> <span
                                                class="">Download</span></a>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
										<label for="exampleInputUsername1">Branch Name</label>
										<input type="text" class="form-control" v-model="branch.name"
											autocomplete="off" placeholder="Input Branch Name">
                                        <p class="branch-error" v-if="errors.name">{{ errors.name[0] }}</p>
									</div>
                                    <div class="form-group">
										<label for="exampleInputUsername1">Latitude</label>
										<input type="text" class="form-control" v-model="branch.latitude"
											autocomplete="off" placeholder="Input Branch Latitude">
                                        <p class="branch-error" v-if="errors.longitude">{{ errors.longitude[0] }}</p>
									</div>
                                    <div class="form-group">
										<label for="exampleInputUsername1">Longnitude</label>
										<input type="text" class="form-control" v-model="branch.longitude"
											autocomplete="off" placeholder="Input Branch Longnitude">
                                        <p class="branch-error" v-if="errors.longitude">{{ errors.longitude[0] }}</p>
									</div>
                                    <div class="row">
                                        <div class="col-md-6 mr-auto ml-auto">
                                            <button v-if="update == false" :disabled="loading == true" class="btn btn-primary btn-round btn-block" @click="CreateBranch">Create</button>
                                            <button v-if="update == true" :disabled="loading == true" class="btn btn-primary btn-round btn-block" @click="UpdateBranch">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                                <h6 class="card-title mb-0">Branch List</h6>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Name</th>
                                                <th>Longitude</th>
                                                <th>Latitude</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="(list, index) in branchList.branch" :key="list.id">
                                                <td>{{ list.name }}</td>
                                                <td>{{ list.longnitude }}</td>
                                                <td>{{ list.latitude }}</td>
                                                <td>
                                                    <button class="btn btn-info btn-round btn-sm" @click="SelectUpdate(index)">Update</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                   <l-map style="height: 500px" :zoom="zoom" :center="center">
                        <l-tile-layer :url="url"></l-tile-layer>
                        <l-feature-group ref="features">
                            <l-popup > <span> Branch Name {{caller}}</span></l-popup>
                        </l-feature-group>
                        <l-marker
                            :key="index"
                            v-for="(marker, index) in branchList.branch"
                            :name="marker.name"
                            :latLng="latLng(marker.latitude, marker.longnitude)"
                             @click="ShowPopUp(latLng(marker.latitude, marker.longnitude), marker.name)"
                            >
                        </l-marker>
                   </l-map>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import L from 'leaflet';
    import { LMap, LTileLayer, LMarker, LFeatureGroup, LPopup, } from 'vue2-leaflet';
    import AdminSideNav from './AdminSideNav'
    import Loading from 'vue-loading-overlay';
    export default {
        components: {
            'sidenav': AdminSideNav,
            'l-map' : LMap,
            'l-tile-layer' : LTileLayer,
            'l-marker' : LMarker,
            'l-feature-group': LFeatureGroup,
            'l-popup' : LPopup,
            'load' : Loading,

        },
        data(){
            return{
                user:{},
                loading:false,
                update : false,

                branch: {
                    id:'',
                    name:'',
                    latitude:'',
                    longitude:'',
                },
                branchList:{},
                errors:{},

                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                zoom: 6,
                center: [7.1207, 125.5553],
                caller: null,
                // markerLatLng: L.latLng(7.086645, 125.6013325),
            }
        },
        methods:{
            GetBranch(){
                axios.get('../api/getbranch').then(res => {
                    this.branchList = res.data
                })
            },
            latLng(lat, lng){
                return L.latLng(lat, lng)
            },
            ShowPopUp(latLng, caller) {
                this.caller = caller;
                this.$refs.features.mapObject.openPopup(latLng);
            },
            CreateBranch(){
                this.loading = true
                axios.post('../api/createbranch', this.branch).then(res => {
                    if (res.data.message == 'success') {
                        this.loading = false
                        this.Clear()
                        this.Alert('success', 'Successfully Created')
                    }
                    else{
                        this.loading = false
                        this.Alert('info', res.data.message)
                    }
                }).catch(err => {
                    this.loading = false
                    if (err.response.status == 422) {
                        this.errors = err.response.data.errors
                    }
                })
            },
            SelectUpdate(index){

            },
            UpdateBranch(){

            },
            Clear(){
                this.loading = false
                this.update = false
                this.errors = {}
                this.branch = {
                    id:'',
                    name:'',
                    address:'',
                    latitude:'',
                    longnitude:'',
                }
                this.GetBranch()
            },
            getResults(page = 1){

            },
            Alert(icon, title) {
                toast.fire({
                    icon: icon,
                    title: title,
                })
            },
        },
        created(){
            this.GetBranch()
        }
    }

</script>
<style scoped>
.branch-error{
    color: red;
    font-size: 12px;
}
</style>
