<template>
    <!-- <h1>For practising Tailwind stuff</h1> -->

    <!-- It's probably best to put stuff like md: and hover: as the last classes -->

    <!-- Navbar -->

    <!-- Relative positioning. Will be useful for hamburger menu later on -->
    <!-- Container uses the screen sizes that we have specified -->

    <!-- m-2, m-3 usually means margins with rem -->
    <!-- With just m, margin is applied everywhere but you can also do mt, mb, ml, mr, mx, my -->
    <!-- Similar situation for padding with p -->
    <nav class="relative container mx-auto p-6">
        <!-- Flex container -->
        <!-- Flex row by default, add flex-col if you want columns -->
        <div class="flex items-center justify-between">

            <!-- Logo -->
            <div class="pt-2">
                <img src="" alt="" class="w-20 h-20">

                <!-- Insert an image that's at least 50 px in width and height -->
                <!-- If you want to make it smaller, use w-10, h-10 -->
            </div>

            <!-- Menu -->
            <!-- You can make this hidden unless you hit a screen size -->
            <div class="hidden space-x-6 md:flex">
                <a href="#" class="hover:text-darkGrayishBlue">Pricing</a>
                <a href="#" class="hover:text-darkGrayishBlue">Product</a>
                <a href="#" class="hover:text-darkGrayishBlue">About Us</a>
                <a href="#" class="hover:text-darkGrayishBlue">Careers</a>
                <a href="#" class="hover:text-darkGrayishBlue">Community</a>
            </div>

            <!-- rounded-full creates pills and circles -->
            <a href=""
                class="hidden p-3 px-6 pt-2 text-white bg-brightRed rounded-full baseline hover:bg-brightRedLight md:block">Get
                Started</a>

            <!-- Hamburger -->
            <button id="menu-btn" class="block hamburger md:hidden focus:outline-none">
                <span class="hamburger-top "></span>
                <span class="hamburger-middle"></span>
                <span class="hamburger-bottom"></span>
            </button>
        </div>

        <div >
            <div id="menu"
                class="absolute flex-col items-center self-end py-8 mt-10 space-y-6 font-bold bg-white sm:w-auto sm:self-center left-6 right-6 drop-shadow-md">
                <a href="#" class="hover:text-darkGrayishBlue">Pricing</a>
                <a href="#" class="hover:text-darkGrayishBlue">Product</a>
                <a href="#" class="hover:text-darkGrayishBlue">About Us</a>
                <a href="#" class="hover:text-darkGrayishBlue">Careers</a>
                <a href="#" class="hover:text-darkGrayishBlue">Community</a>
            </div>
        </div>
    </nav>

    <!-- Hero section -->
    <section id="hero">
        <div class="container flex flex-col-reverse items-center px-6 mx-auto mt-10 space-y-0 md:space-y-0 md:flex-row">

            <!-- You can set the width to half -->
            <!-- Some people find that on larger screens, text to the left looks better -->
            <div class="flex flex-col mb-32 space-y-12 md:w-1/2">
                <h1 class="max-w-md text-4xl font-bold text-center md:text-5xl md:text-left">
                    Bring everyone together to build better products
                </h1>
                <p class="max-w-sm text-center text-darkGrayishBlue md:text-left">
                    Manage makes it simple for software teams to plan day-to-day tasks
                    while keeping the larger team goals in view.
                </p>
                <div class="flex-justify-center md:justify-start">
                    <a href=""
                        class="p-3 px-6 pt-2 text-white bg-brightRed rounded-full baseline hover:bg-brightRedLight">Get
                        Started</a>
                </div>
            </div>

            <div class="md:w-1/2">
                <img src="" alt="">
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container flex flex-col px-4 mx-auto mt-10 space-y-12 md:space-y-0 md:flex-row">
            <div class="flex flex-col space-y-12 md:w-1/2">
                <h2 class="max-w-md text-4xl font-bold text-center md:text-left">
                    What's different about Manage?
                </h2>
                <p class="max-w-sm text-center text-darkGrayishBlue md:text-left">
                    Manage provides all the functionality your team needs, without the
                    complexity. Our software is tailor-made for modern digital product
                    teams.
                </p>
            </div>


            <div class="flex flex-col space-y-8 md:w-1/2">
                <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                    <!-- Heading -->
                    <div class="rounded-l-full bg-brightRedSupLight md:bg-transparent">
                        <div class="flex items-center space-x-2">
                            <div class="px-4 py-2 text-white rounded-full bg-brightRed md:py-1">
                                01
                            </div>
                            <h3 class="text-base font-bold md:mb-4 md:hidden">
                                Track company-wide progress
                            </h3>

                        </div>
                    </div>

                    <div>
                        <h3 class="hidden mb-4 text-lg font-bold md:block">
                            Track company-wide progress
                        </h3>
                        <p class="text-darkGrayishBlue">
                            See how your day-to-day tasks fit into the wider vision. Go from
                            tracking progress at the milestone level all the way down to the
                            smallest of details. Never lose sight of the bigger picture
                            again.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                    <!-- Heading -->
                    <div class="rounded-l-full bg-brightRedSupLight md:bg-transparent">
                        <div class="flex items-center space-x-2">
                            <div class="px-4 py-2 text-white rounded-full bg-brightRed md:py-1">
                                02
                            </div>
                            <h3 class="text-base font-bold md:mb-4 md:hidden">
                                Advanced built-in reports
                            </h3>

                        </div>
                    </div>

                    <div>
                        <h3 class="hidden mb-4 text-lg font-bold md:block">
                            Advanced built-in reports
                        </h3>
                        <p class="text-darkGrayishBlue">
                            Set internal delivery estimates and track progress toward
                            company goals. Our customisable dashboard helps you build out
                            the reports you need to keep key stakeholders informed.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                    <!-- Heading -->
                    <div class="rounded-l-full bg-brightRedSupLight md:bg-transparent">
                        <div class="flex items-center space-x-2">
                            <div class="px-4 py-2 text-white rounded-full bg-brightRed md:py-1">
                                03
                            </div>
                            <h3 class="text-base font-bold md:mb-4 md:hidden">
                                Everything you need in one place
                            </h3>

                        </div>
                    </div>

                    <div>
                        <h3 class="hidden mb-4 text-lg font-bold md:block">
                            Everything you need in one place
                        </h3>
                        <p class="text-darkGrayishBlue">
                            Stop jumping from one service to another to communicate, store
                            files, track tasks and share documents. Manage offers an
                            all-in-one team productivity solution.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials">
        <!-- Containing heading and testimonials -->
        <div class="max-w-6xl px-5 mx-auto mt-32 text-center">
            <!-- Heading -->
            <h2 class="text-4xl font-bold text-center">
                What's Different About Manage?
            </h2>

            <div class="flex flex-col mt-24 md:flex-row md:space-x-6">
                <div class="flex flex-col items-center p-6 space-y-6 rounded-lg bg-veryLightGray md:w-1/3">
                    <!-- You can have negative margins -->
                    <img class="w-16 -mt-14" src="" alt="">
                    <h5 class="text-lg font-bold">
                        Anisha Li
                    </h5>
                    <p class="text-sm text-darkGrayishBlue">
                        “Manage has supercharged our team’s workflow. The ability to
                        maintain visibility on larger milestones at all times keeps
                        everyone motivated.”
                    </p>
                </div>

                <div class="hidden flex-col items-center p-6 space-y-6 rounded-lg bg-veryLightGray md:flex md:w-1/3">
                    <!-- You can have negative margins -->
                    <img class="w-16 -mt-14" src="" alt="">
                    <h5 class="text-lg font-bold">
                        Anisha Li
                    </h5>
                    <p class="text-sm text-darkGrayishBlue">
                        “Manage has supercharged our team’s workflow. The ability to
                        maintain visibility on larger milestones at all times keeps
                        everyone motivated.”
                    </p>
                </div>

                <div class="hidden flex-col items-center p-6 space-y-6 rounded-lg bg-veryLightGray md:flex md:w-1/3">
                    <!-- You can have negative margins -->
                    <img class="w-16 -mt-14" src="" alt="">
                    <h5 class="text-lg font-bold">
                        Anisha Li
                    </h5>
                    <p class="text-sm text-darkGrayishBlue">
                        “Manage has supercharged our team’s workflow. The ability to
                        maintain visibility on larger milestones at all times keeps
                        everyone motivated.”
                    </p>
                </div>
            </div>

            <div class="my-16">
                <a href="" class="p-3 px-6 pt-2 text-white bg-brightRed rounded-full baseline hover:bg-brightRedLight">Get
                    Started</a>
            </div>
        </div>
    </section>

    <section id="cta" class="bg-brightRed">
        <div
            class="container flex flex-col items-center justify-between px-6 py-24 mx-auto space-y-12 md:py-12 md:flex-row md:space-y-0">
            <!-- Leading changes line height -->
            <h2 class="text-5xl font-bold leading-tight text-center text-white md:text-4xl md:max-w-xl md:text-left">
                Simplify how your team works today
            </h2>
            <a href="" class="p-3 px-6 pt-2 text-brightRed bg-white rounded-full shadow-2xl baseline hover:bg-gray-900">Get
                Started</a>
        </div>
    </section>

    <footer class="bg-veryDarkBlue">
        <div class="container flex flex-col-reverse justify-between px-6 py-10 mx-auto soace-y-8 md:flex-row md:space-y-0">
            <div
                class="flex flex-col-reverse items-center justify-between space-y-12 md:flex-col md:space-y-0 md:items-start">

                <div class="mx-auto my-6 text-center text-white md:hidden">
                    Copyright &copy; 2022, All Rights Reserved
                </div>

                <div>
                    <img class="h-8" src="" alt="">
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="#">
                        <img class="h-8" src="" alt="">
                    </a>
                    <a href="#">
                        <img class="h-8" src="" alt="">
                    </a>
                    <a href="#">
                        <img class="h-8" src="" alt="">
                    </a>
                    <a href="#">
                        <img class="h-8" src="" alt="">
                    </a>
                    <a href="#">
                        <img class="h-8" src="" alt="">
                    </a>
                </div>

            </div>

            <!-- List container -->
            <div class="flex justify-around space-x-32">
                <div class="flex flex-col space-y-3 text-white">
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                </div>

                <div class="flex flex-col space-y-3 text-white">
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                    <a href="" class="hover:text-brightRed">Hi</a>
                </div>
            </div>

            <div class="flex flex-col justify-between">
                <form action="">
                    <div class="flex space-x-3">
                        <input class="flex-1 px-4 rounded-full focus:outline-none" placeholder="Updated in your inbox">
                        <button
                            class="px-6 py-2 text-white rounded-full bg-brightRed hover:bg-brightRedLight focus:outline-none">Go</button>
                    </div>
                </form>

                <div class="hidden text-white md:block">
                    Copyright &copy; 2022, All Rights Reserved
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    layout: null,
};
</script>