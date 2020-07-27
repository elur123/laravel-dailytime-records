import AdminBranch from './Admin/AdminBranch'
import AdminDashboard from './Admin/AdminDashboard'
import AdminEmployee from './Admin/AdminEmployee'

import EmployeeLogs from './components/EmployeeLogs'
import Login from './Login'
export default [
    {
        path: '/',
        component: Login,
        name: 'Login'
    },
    {
        path: '/admin/dashboard',
        component: AdminDashboard,
        name: 'AdminDashboard'
    },
    {
        path: '/admin/branch',
        component: AdminBranch,
        name: 'AdminBranch'
    },
    {
        path: '/admin/employee',
        component: AdminEmployee,
        name: 'AdminEmployee'
    },
    {
        path: '/employee-logs',
        component: EmployeeLogs,
        name: 'EmployeeLogs'
    }
]
