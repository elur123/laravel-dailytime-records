<template>
    <div class="page-wrapper">
        <sidenav :user="user" />
        <div class="page-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-0">Dashboard</h4>
                </div>
            </div>
            <div class="row">
            </div> <!-- row -->
        </div>

    </div>
</template>
<script>
    import AdminSideNav from './AdminSideNav'
    export default {
        components: {
            'sidenav': AdminSideNav,
        },
        data() {
            return {
                user: {},
                loading: false,
                update: false,

                branch: {
                    id: '',
                    name: '',
                    address: '',
                    latitude: '',
                    longitude: '',
                },
                branchList: {},
                errors: {},
            }
        },
        methods: {
            GetBranch() {

            },
            CreateBranch() {

            },
            SelectUpdate(index) {

            },
            UpdateBranch() {

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
                this.GetBranch()
            },
            getResults(page = 1) {

            },
            Alert(icon, title) {
                toast.fire({
                    icon: icon,
                    title: title,
                })
            },
        },
        created() {

        }
    }

</script>
<style scoped>
    .branch-error {
        color: red;
        font-size: 12px;
    }

</style>
