<template>
    <div class="page-wrapper full-page">
        <load :active.sync="loading" :can-cancel="false" :is-full-page="true"></load>
        <div class="row">
            <div class="col-md-6 mr-auto ml-auto mt-3">
                <h3 class="text-center">Employee Logs</h3>
            </div>
            <div class="col-md-12 mt-3" v-if="show == true">
                <div class="row">
                    <div class="col-md-6 mr-auto ml-auto text-center">
                        <h5 class="text-center">Employee Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p>{{ emplo.details.fullname }}</p>
                                <label>Fullname</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ emplo.details.name }}</p>
                                <label>Branch Assigned</label>
                            </div>
                            <div class="col-md-12">
                                <p>{{ Number(distancekm).toFixed(2) }} KM</p>
                                <label>Distance from Branch by KM</label>
                                <div class="row">
                                    <div class="col-md-6 mr-auto ml-auto">
                                        <button class="btn btn-info btn-round" @click="GetCurrentLocation">Refresh Location</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-md-6 mr-auto ml-auto">
                        <div class="container" v-if="branchlocation.center.length">
                            <l-map style="height: 500px" :zoom="zoom" :center="center">
                                <l-tile-layer :url="url"></l-tile-layer>
                                <l-circle-marker
                                        :lat-lng="branchlocation.center"
                                        :radius="branchlocation.radius"
                                        :color="branchlocation.color"
                                />
                                <l-marker
                                    :latLng="marker"
                                    >
                                </l-marker>
                        </l-map>
                        </div>
                    </div> -->
                    <div class="col-md-6 mr-auto ml-auto">
                        <div class="container">
                            <cam v-if="isshow == true" ref="webcam" :deviceId="deviceId" width="100%" height="100%"
                                :isFrontCam="frontCam" class="cam"></cam>
                            <button v-if="istimein == true && Number(distancekm).toFixed() == 0" @click="Timein"
                                class="btn btn-outline btn-success capture"><i class="fas fa-check"></i></button>
                            <button v-if="istimein == false && Number(distancekm).toFixed() == 0"  @click="Timeout"
                                class="btn btn-outline btn-danger capture"><i class="fas fa-arrow-right"></i></button>
                        </div>
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="logs in emplo.logs" :key="logs.id">
                                            <td>{{ logs.date }}</td>
                                            <td>{{ logs.time_in }}</td>
                                            <td>{{ logs.time_out }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="container mt-5">
                            <button class="btn btn-primary btn-round" @click="Done">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="show == false" class="page-content d-flex align-items-center justify-content-center">
            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-6 mx-auto flex-column align-items-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title text-center">Employee Key</div>
                            <div class="form-group">
                                <input type="text" v-model="emplo.key" class="form-control text-center"
                                    placeholder="Input Employee Key">
                                <p class="emplo-error" v-if="errors.key">Please Provide Employee Key</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mr-auto ml-auto">
                                    <button class="btn btn-primary btn-round btn-block" @click="EnterKey">Enter</button>
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
    import L from 'leaflet';
    import {
        LMap,
        LTileLayer,
        LMarker,
        LCircleMarker
    } from 'vue2-leaflet';
    import Loading from 'vue-loading-overlay';
    import {
        WebCam
    } from 'vue-cam-vision'
    import moment, {
        months
    } from 'moment'
    import * as faceapi from 'face-api.js'
    export default {
        components: {
            'l-map': LMap,
            'l-tile-layer': LTileLayer,
            'l-marker': LMarker,
            'l-circle-marker': LCircleMarker,
            'load': Loading,
            'cam': WebCam

        },
        data() {
            return {
                user: {},
                loading: false,
                show: false,
                istimein: true,

                emplo: {
                    key: '',
                    details: {},
                    logs: {},
                },
                logsdetails: {
                    date: moment(Date.now()).format('YYYY-MM-DD'),
                    time: moment(Date.now()).format('h:mm'),
                    status: moment(Date.now()).format('A'),
                    id: '',
                },
                errors: {},

                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                zoom: 15,
                center: [7.1207, 125.5553],
                caller: null,
                marker: [],

                branchlocation: {
                    center: [],
                    radius: 10,
                    color: "red"
                },
                distancekm: 0,

                isshow: true,
                frontCam: false,
                deviceId: null,
            }
        },
        methods: {
            latLng(lat, lng) {
                return L.latLng(lat, lng)
            },
            ShowPopUp(latLng, caller) {
                this.caller = caller;
                this.$refs.features.mapObject.openPopup(latLng);
            },
            EnterKey() {
                this.loading = true
                axios.get('../api/getemployeedetails/' + this.emplo.key).then(res => {
                    if (res.data.message == 'success') {
                        this.loading = false
                        this.emplo.details = res.data.details
                        this.show = true
                        this.GetEmploLogs(this.emplo.details.emploID)
                        this.branchlocation.center = [this.emplo.details.latitude, this.emplo.details
                            .longnitude]
                    } else {
                        this.show = false
                        this.loading = false
                        this.Alert('info', res.data.message)
                    }
                }).catch(err => {
                    this.show = false
                    this.loading = false
                    if (err.response.status == 422) {
                        this.errors = err.response.data.errors
                    }
                })
            },
            GetEmploLogs(id) {
                axios.get('../api/getemployeelogs/' + id).then(res => {
                    this.emplo.logs = res.data.logs
                })
            },
            Timein() {
                this.logsdetails.id = this.emplo.details.id
                this.loading = true
                axios.post('../api/employeetimein', this.logsdetails).then(res => {
                    if (res.data.message == 'success') {
                        this.loading = false
                        this.Alert('success', 'Success')
                        this.GetEmploLogs(this.logsdetails.id)
                    } else {
                        this.loading = false
                        this.Alert('info', res.data.message)
                    }
                })
            },
            Timeout(){
                this.logsdetails.id = this.emplo.details.id
                this.loading = true
                axios.post('../api/employeetimeout', this.logsdetails).then(res => {
                    if (res.data.message == 'success') {
                        this.loading = false
                        this.Alert('success', 'Success')
                        this.GetEmploLogs(this.logsdetails.id)
                    } else {
                        this.loading = false
                        this.Alert('info', res.data.message)
                    }
                })
            },
            Done(){
                this.show = false
                this.emplo = {
                    key: '',
                    details: {},
                    logs: {},
                }
            },
            getDistanceKm(lat1, lon1, lat2, lon2) {
                var R = 6371; // Radius of the earth in km
                var dLat = this.deg2rad(lat2 - lat1); // deg2rad below
                var dLon = this.deg2rad(lon2 - lon1);
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = R * c; // Distance in km
                this.distancekm = d
                console.log(this.distancekm)
            },
            deg2rad(deg) {
                return deg * (Math.PI / 180)
            },
            GetCurrentLocation() {
                if (!("geolocation" in navigator)) {
                    this.Alert('warning', "Geolocation is not available.")
                    // this.errorStr = "Geolocation is not available.";
                    return;
                }
                // get position
                navigator.geolocation.getCurrentPosition(pos => {
                    this.marker = [pos.coords.latitude, pos.coords.longitude]
                    console.log(this.marker)
                }, err => {
                    // this.gettingLocation = false;
                    // this.errorStr = err.message;
                });

                this.getDistanceKm(this.marker[0], this.marker[1], this.branchlocation.center[0], this.branchlocation
                    .center[1])
            },
            Clear() {
                this.loading = false
                this.update = false
                this.errors = {}
                this.branch = {
                    id: '',
                    name: '',
                    address: '',
                    latitude: '',
                    longnitude: '',
                }
            },
            async LoadFaceAPI(){
                this.loading = true
                Promise.all([
                    faceapi.loadFaceRecognitionModel('./models'),
                    faceapi.loadFaceLandmarkTinyModel('./models'),
                    faceapi.loadTinyFaceDetectorModel('./models')
                ]).then(() => {
                   this.loading = false
                })
            },
            Alert(icon, title) {
                toast.fire({
                    icon: icon,
                    title: title,
                })
            },
        },
        created() {
            this.LoadFaceAPI()
            if (this.logsdetails.time >= '5:00' && this.logsdetails.status == 'PM') {
                this.istimein = false
            }
            this.GetCurrentLocation()

        },
        updated() {
            this.GetCurrentLocation()
        }
    }

</script>
<style scoped>
    .emplo-error {
        color: red;
        font-size: 12px;
    }

    .main-wrapper .page-wrapper.full-page .page-content {
        margin-top: -200px;
    }

    .capture {
        position: relative;
        border-radius: 50%;
        margin-top: -80px;
        margin-left: 210px;
    }

    .cam {
        width: 250px;
        margin-left: 200px;
    }

</style>
