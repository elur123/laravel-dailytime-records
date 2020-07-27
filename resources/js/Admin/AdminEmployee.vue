<template>
    <div class="page-wrapper">
        <load :active.sync="loading"
        :can-cancel="false"
        :is-full-page="true"></load>
        <sidenav :user="user" />
        <div class="page-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-0">Employee</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                                <h6 v-if="update == false" class="card-title mb-0">Create New Branch</h6>
                                <h6 v-if="update == true" class="card-title mb-0">Update Branch</h6>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
										<label for="exampleInputUsername1">FullName</label>
										<input type="text" class="form-control" v-model="emplo.fullname"
											autocomplete="off" placeholder="Input Employeee Fullname">
                                        <p class="branch-error" v-if="errors.fullname">{{ errors.fullname[0] }}</p>
									</div>
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select v-model="emplo.branch" class="from-control">
                                            <option v-for="list in branchList" :key="list.id" :value="list.id">{{ list.name }}</option>
                                        </select>
                                        <p class="branch-error" v-if="errors.branch">{{ errors.branch[0] }}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mr-auto ml-auto">
                                            <button v-if="update == false" :disabled="loading == true" class="btn btn-primary btn-round btn-block" @click="CreateEmployee">Create</button>
                                            <button v-if="update == true" :disabled="loading == true" class="btn btn-primary btn-round btn-block" @click="UpdateBranch">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <cam
                        v-if="isshow == true"
                        ref="webcam"
                        :deviceId="deviceId"
                        width="100%"
                        height="100%"
                        :isFrontCam="frontCam"
                    ></cam>
                    <img v-if="isshow == false" :src="emplo.image" alt="">
                    <button v-if="isshow == true" @click="Capture" class="btn btn-outline btn-info capture"><i class="fas fa-camera"></i></button>
                    <button v-if="isshow == false" @click="CancelCapture" class="btn btn-outline btn-info capture"><i class="fas fa-trash"></i></button>
                     <p class="branch-error" v-if="errors.image">{{ errors.image[0] }}</p>
                </div>
                <div class="col-md-12 grid-margin stretch-card">
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
                                                <th>FullName</th>
                                                <th>Key</th>
                                                <th>Branch</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="(list, index) in emploList" :key="list.id">
                                                <td>{{ list.fullname }}</td>
                                                <th>{{ list.emplo_key }}</th>
                                                <td>{{ list.name }}</td>
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
        </div>
    </div>
</template>
<script>
    import AdminSideNav from './AdminSideNav'
    import Loading from 'vue-loading-overlay';
    import { WebCam } from 'vue-cam-vision'
    export default {
        components: {
            'sidenav': AdminSideNav,
            'load' : Loading,
            'cam' : WebCam
        },
        data(){
            return{
                user:{},
                loading:false,
                update : false,

                emplo: {
                    id:'',
                    fullname:'',
                    image:'',
                    branch:'',
                },
                emploList:{},
                branchList:{},
                errors: {},


                isshow:true,
                frontCam: false,
                deviceId: null,
            }
        },
        methods:{
            updatefiles(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                if (file['size'] < 10111775) {
                    if (file['type'] == 'image/png' || file['type'] == 'image/jpeg') {
                        // reader.readAsDataURL(file);
                        this.emplo.image = file

                    } else {
                        this.emplo.image = ''
                        this.$refs.myimage.value = ''
                        this.Alert('error', 'Invalid File')
                    }
                } else {
                    this.emplo.image = ''
                    this.$refs.myimage.value = ''
                    this.Alert('error', 'You are uploading a Large File!!');
                }
            },
            async Capture(){
                this.$refs.webcam.capture()
                if (this.$refs.webcam.captures.length) {
                    // console.log(this.$refs.webcam.captures[0].image)
                    this.emplo.image = this.$refs.webcam.captures[0].image
                    // this.$refs.webcam.stop()
                    this.isshow = false
                }
            },
            CancelCapture(){
                this.isshow = true
                // this.$refs.webcam.start()
                this.emplo.image = ''
            },
            GetEmployee(){
                axios.get('../api/getemplo').then(res => {
                    this.emploList = res.data.emplo
                })
            },
            CreateEmployee(){
                this.loading = true
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                let formdata = new FormData()
                formdata.append('fullname', this.emplo.fullname)
                formdata.append('image', this.emplo.image)
                formdata.append('branch', this.emplo.branch)
                axios.post('../api/createemplo', formdata, config).then(res => {
                    if (res.data.message == 'success') {
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
                this.isshow = true
                this.errors = {}
                this.emplo = {
                    id:'',
                    fullname:'',
                    branch:'',
                }
                this.GetEmployee()
            },
            Alert(icon, title) {
                toast.fire({
                    icon: icon,
                    title: title,
                })
            },
        },
        created(){
            axios.get('../api/getbranch').then(res => {
                this.branchList = res.data.branch
            })
            this.GetEmployee()
        }
    }

</script>
<style scoped>
.branch-error{
    color: red;
    font-size: 12px;
}
.capture{
    position: relative;
    border-radius: 50%;
    margin-top: -120px;
    margin-left: 50px;
}

</style>
