export default [
    { path: '/', component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/departments', component: require('./components/Departments.vue').default },
    { path: '/students', component: require('./components/Students.vue').default },
    { path: '/results', component: require('./components/Results.vue').default },
    { path: '/login', component: require('./components/Login.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
];
