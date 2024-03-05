import { createRouter, createWebHistory, routerKey } from 'vue-router';
// Main Routes
import NotFound from './views/404.vue'
import Home from './views/Home.vue'
import Login from './views/Login.vue'
// Portal - Authenticated Routes
import AccountSetup from './views/Portal/AccountSetup.vue'
import Dashboard from './views/Portal/Dashboard.vue'

const routes = [
    {
        // Catch all for routes that do not exist
        path: "/:catchAll(.*)",
        name: "NotFound",
        component: NotFound,
    },
    {
        path: '/',
        name: "Home",
        component: Home,
        meta: {
            title: "Home"
        }
    },
    // Login / Sign Up 
    {
        path: '/login',
        name: "Login",
        component: Login,
        meta: {
            title: "Login"
        }
    },
    {
        // Authenticated Routes
        path: '/a',
        beforeEnter: validateAccessToken,
        children: [
            // Account Setup
            {
                path: 'account-setup',
                name: "Account Setup",
                component: AccountSetup,
                meta: {
                    title: "Account Setup"
                }
            },
            // Portal - Setup Completed Routes
            {
                path: 'portal',
                meta: {
                    require_setup: true
                },
                children: [
                    // Dashboard
                    {
                        path: 'dashboard',
                        name: "Dashboard",
                        component: Dashboard,
                        meta: {
                            title: "Dashboard"
                        }
                    }
                ]
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    // Adjust Meta Title Based on Route
    document.title = 'title' in to.meta && to.meta.title ? to.meta.title+' | AI Unlocked' : 'AI Unlocked'
    next()
})

// Validate Token API Call
async function validate_token(token){
    let data = null;

    await axios.get('/api/auth/validate-token', {
        "headers": {
            "Authorization": "Bearer "+token
        }
    })
    .then(response => {
        data = response;
    })
    .catch(error => {
        data = error.response;
    })

    return data;
}

// Validate Session Token for Authenticate Routes
async function validateAccessToken(to, from, next) {
    const token = localStorage.getItem("ai_unlocked_token");
    // Clear token from local storage and redirect to login page
    async function invalid(){
        if(token){
            localStorage.removeItem("ai_unlocked_token")
        }

        return next({name: "Home"});
    }

    // Check to make sure the token exists
    if(!token){ return invalid(); }

    // Check if token is valid
    const response = await validate_token(token)
    if(response && response.status == 200){
        // Check if the next route requires setup to be complete
        const require_setup = to.meta.require_setup ?? false
        // If setup is required and not completed by the user send them to the Account Setup Page
        if(require_setup && !response.data.setup_complete){
            return next({name: "Account Setup"})
        }

        return next();
    }else{
        return invalid(); 
    }
}

export default router