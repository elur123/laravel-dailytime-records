<template>
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">
        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 pr-md-0">
                            <div class="auth-left-wrapper">

                            </div>
                        </div>
                        <div class="col-md-8 pl-md-0">
                            <div class="auth-form-wrapper px-4 py-5">
                                <div class="alert alert-warning" v-if="errors != ''">
                                    <span>
                                        {{ errors }}
                                    </span>
                                </div>
                                <a href="#" class="noble-ui-logo logo-light d-block mb-2">Accounting <span>UI</span></a>
                                <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.
                                </h5>
                                <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Username</label>
                                        <input type="email" class="form-control" v-model="user.uname"
                                            placeholder="Input Username">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" v-model="user.pass"
                                            autocomplete="current-password" placeholder="Input Password">
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" @click="Login"
                                            class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">Login</a>
                                        <!-- <button type="button"
                                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                            <i class="btn-icon-prepend" data-feather="twitter"></i>
                                            Login with twitter
                                        </button> -->
                                    </div>
                                </form>
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
    export default {
        data(){
            return{
                user:{
                    uname:'',
                    pass:'',
                },
                errors: '',
            }
        },
        methods:{
            Login(){
                this.$router.push('/admin/dashboard')
            }
        },
        created(){

        }
    }

</script>
<style scoped>
    .main-wrapper .page-wrapper .page-content {
        flex-grow: 1;
        padding: 5px;
        margin-top: 0px;
    }

</style>
