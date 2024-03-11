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
    document.title = 'title' in to.meta && to.meta.title ? to.meta.title+' | GPT4 Unlimited' : 'GPT4 Unlimited'
    next()
})

// Validate Session Token for Authenticate Routes
async function validateAccessToken(to, from, next) {
    // Check if token is valid
    const app = window.gpt4u.config.globalProperties;
    if(await app.$session.valid()){
        const session = app.$session.get_session();

        // Check if the next route requires setup to be complete
        const require_setup = to.meta.require_setup ?? false
        // If setup is required and not completed by the user send them to the Account Setup Page
        if(require_setup && !session.setup_complete){
            return next({name: "Account Setup"})
        }

        return next();
    }else{
        app.$session.clear_session()
        next({name: "Login", query: {then: to.path}})
    }
}

export default router