<template>
    <div>
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    Accounting<span>UI</span>
                </a>
                <div class="sidebar-toggler " :class="`${isbody == false ? 'not-active' : 'active'}`"  @click="ToggleSide">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="sidebar-body">
                <ul class="nav">
                    <li class="nav-item nav-category">Main</li>
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" class="nav-link">
                            <i class="fas fa-th-large"></i>
                            <span class="link-title">Dashboard</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/branch" class="nav-link">
                            <i class="fas fa-boxes"></i>
                            <span class="link-title">Branch</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/employee" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="link-title">Users</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/employee-logs" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="link-title">Employee Logs</span>
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i data-feather="menu"></i>
            </a>
            <div class="navbar-content">
                <form class="search-form">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i data-feather="search"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
                    </div>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown nav-profile" :class="{' show' : this.ishow == true}">
                        <a @click="ToggleNav" class="nav-link dropdown-toggle" href="#" id="profile" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://via.placeholder.com/30x30" alt="profile">
                        </a>
                        <div class="dropdown-menu" :class="{' show' : this.ishow == true}" aria-labelledby="profile">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="figure mb-3">
                                    <img src="https://via.placeholder.com/80x80" alt="">
                                </div>
                                <div class="info text-center">
                                    <p class="name font-weight-bold mb-3">{{ user.fullname }}</p>
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item">
                                        <a href="pages/general/profile.html" class="nav-link">
                                            <i data-feather="user"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:;" class="nav-link">
                                            <i data-feather="edit"></i>
                                            <span>Edit Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:;" class="nav-link">
                                            <i data-feather="repeat"></i>
                                            <span>Switch User</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link" @click="Logout">
                                            <i data-feather="log-out"></i>
                                            <span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

</template>
<script>
export default {
    props:['user'],
    data(){
        return{
            ishow: false,
            isbody: false
        }
    },
    methods:{
        ToggleNav(){
            this.ishow = !this.ishow
        },
        ToggleSide(){
            this.isbody = !this.isbody
            const e = document.body
            if (this.isbody == true) {
                e.classList.add('sidebar-folded')
            }
            else{
                e.classList.remove('sidebar-folded')
            }
        },
        Logout(){
            localStorage.removeItem('user');
            localStorage.removeItem('sales');
            localStorage.removeItem('details');
            localStorage.removeItem('payroll');
            this.$router.push('/')
        }
    }
}
</script>
<style scoped>
.sidebar .sidebar-body .nav .nav-item .nav-link .link-title {
    margin-left: 15px;
    font-size: 14px;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
}
</style>
