<template>
    <nav>
        
        <!-- <link href="/users">Users</link> -->
        <ul class="flex space-x-4 list-inside">
            <li>
                
                <NavLink href="/home" :active="$page.component === 'Home'">Go home little bird</NavLink>
            </li>
            <li>
                <!-- <Link href="/users">All the epic users</Link> -->

                <NavLink href="/users" :active="$page.component === 'Users'">All the epic users</NavLink>
            </li>

            <!-- Here's how you can submit POST requests -->
            <!-- Inertia has an event listener that sends this request -->
            <!-- Inertia uses Axios -->
            <!-- Passing CRSF token is automatically in responses in Laravel -->
            <!-- Rendering the Link as a button means people can't CTRL+click it and accidentally send GET requests -->
            <!-- You can also pass data into the request using :data -->
            <li>
                <Link href="/testpost" method="post" :data="{
                    foo: 'bar',
                    test: 'awesome'
                }" as="button">Logout</Link>
            </li>
        </ul>

        
    </nav>

    <ul class="flex space-x-4 list-inside">
            <li>
                <!-- Conditional class application -->
                <!-- <Link href="/home" :class="{ 'font-bold underline': $page.component === 'Home' }">Go home little bird</Link> -->


                <NavLink href="/home" :active="$page.component === 'Home'">Go home little bird</NavLink>
            </li>
            <li>
                <!-- <Link href="/users">All the epic users</Link> -->

                <NavLink href="/users" :active="$page.component === 'Users'">All the epic users</NavLink>
            </li>

            <!-- Here's how you can submit POST requests -->
            <!-- Inertia has an event listener that sends this request -->
            <!-- Inertia uses Axios -->
            <!-- Passing CRSF token is automatically in responses in Laravel -->
            <!-- Rendering the Link as a button means people can't CTRL+click it and accidentally send GET requests -->
            <!-- You can also pass data into the request using :data -->
            <li>
                <Link href="/testpost" method="post" :data="{
                    foo: 'bar',
                    test: 'awesome'
                }" as="button">Logout</Link>
            </li>
        </ul>
</template>



<script>

// To not have every link be a complete refresh, you gotta setup some Inertia stuff
// Using Link, an AJAX request is sent instead. You'll be returned a JSON object with the data you need for the views.
import { Link } from '@inertiajs/vue3';
import NavLink from '../Shared/NavLink.vue';


export default {
    components: {
        Link, NavLink
    },
}


</script>