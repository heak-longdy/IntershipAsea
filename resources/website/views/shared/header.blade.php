{{-- @section('title') | {{ $header_name }} @stop --}}
{{-- <div class="wb-header">
    <div class="header nav-menu">
        <div class="h-left">
            <div class="logo">
                <i class='bx bxl-unity'></i>
                <label>ECO</label>
            </div>
            <div class="h-item">
                <i class='bx bxs-game yellow'></i>
                <label>Game</label>
            </div>
            <div class="h-item">
                <i class='bx bx-grid-alt blue'></i>
                <label>App</label>
            </div>
            <div class="h-item">
                <i class='bx bxs-award red'></i>
                <label>Top 100</label>
            </div>
        </div>
        <div class="h-right">
            <i class='bx bx-dots-horizontal-rounded'></i>
        </div>
        <i class='bx bx-menu toggle-menu' ></i>
    </div>
    
</div>


<script>
    const nav = document.querySelector('.wb-header');
    const navMenu = document.querySelector('.wb-header .nav-menu');
    const toggleMenu = document.querySelector('.toggle-menu');
    const container = document.querySelector('.wb-container');



    if(window.scrollY > 20) {
        nav.classList.add('active');
    }
    console.log(window.scrollY,'ss');

    toggleMenu.addEventListener('click', function () {
	navMenu.classList.toggle('show');

	if(navMenu.classList.contains('show')) {
		nav.classList.add('active');
	} else {
		if(window.scrollY < 20) {
			nav.classList.remove('active');
		}
	}
})
</script> --}}

{{-- style --}}

<style>
    /*
===============
Fonts
===============
*/
    @import url("https://fonts.googleapis.com/css?family=Open+Sans|Roboto:400,700&display=swap");

    /*
===============
Variables
===============
*/

    :root {
        /* dark shades of primary color*/
        --clr-primary-1: hsl(205, 86%, 17%);
        --clr-primary-2: hsl(205, 77%, 27%);
        --clr-primary-3: hsl(205, 72%, 37%);
        --clr-primary-4: hsl(205, 63%, 48%);
        /* primary/main color */
        --clr-primary-5: hsl(205, 78%, 60%);
        /* lighter shades of primary color */
        --clr-primary-6: hsl(205, 89%, 70%);
        --clr-primary-7: hsl(205, 90%, 76%);
        --clr-primary-8: hsl(205, 86%, 81%);
        --clr-primary-9: hsl(205, 90%, 88%);
        --clr-primary-10: hsl(205, 100%, 96%);
        /* darkest grey - used for headings */
        --clr-grey-1: hsl(209, 61%, 16%);
        --clr-grey-2: hsl(211, 39%, 23%);
        --clr-grey-3: hsl(209, 34%, 30%);
        --clr-grey-4: hsl(209, 28%, 39%);
        /* grey used for paragraphs */
        --clr-grey-5: hsl(210, 22%, 49%);
        --clr-grey-6: hsl(209, 23%, 60%);
        --clr-grey-7: hsl(211, 27%, 70%);
        --clr-grey-8: hsl(210, 31%, 80%);
        --clr-grey-9: hsl(212, 33%, 89%);
        --clr-grey-10: hsl(210, 36%, 96%);
        --clr-white: #fff;
        --clr-red-dark: hsl(360, 67%, 44%);
        --clr-red-light: hsl(360, 71%, 66%);
        --clr-green-dark: hsl(125, 67%, 44%);
        --clr-green-light: hsl(125, 71%, 66%);
        --clr-black: #222;
        --ff-primary: "Roboto", sans-serif;
        --ff-secondary: "Open Sans", sans-serif;
        --transition: all 0.3s linear;
        --spacing: 0.1rem;
        --radius: 0.25rem;
        --light-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        --dark-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        --max-width: 1170px;
        --fixed-width: 620px;
    }

    /*
===============
Global Styles
===============
*/

    *,
    ::after,
    ::before {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: var(--ff-secondary);
        background: var(--clr-grey-10);
        color: var(--clr-grey-1);
        line-height: 1.5;
        font-size: 0.875rem;
    }

    ul {
        list-style-type: none;
    }

    a {
        text-decoration: none;
    }

    h1,
    h2,
    h3,
    h4 {
        letter-spacing: var(--spacing);
        text-transform: capitalize;
        line-height: 1.25;
        margin-bottom: 0.75rem;
        font-family: var(--ff-primary);
    }

    h1 {
        font-size: 3rem;
    }

    h2 {
        font-size: 2rem;
    }

    h3 {
        font-size: 1.25rem;
    }

    h4 {
        font-size: 0.875rem;
    }

    p {
        margin-bottom: 1.25rem;
        color: var(--clr-grey-5);
    }

    @media screen and (min-width: 800px) {
        h1 {
            font-size: 4rem;
        }

        h2 {
            font-size: 2.5rem;
        }

        h3 {
            font-size: 1.75rem;
        }

        h4 {
            font-size: 1rem;
        }

        body {
            font-size: 1rem;
        }

        h1,
        h2,
        h3,
        h4 {
            line-height: 1;
        }
    }

    /*  global classes */

    /* section */
    .section {
        padding: 5rem 0;
    }

    .section-center {
        width: 90vw;
        margin: 0 auto;
        max-width: 1170px;
    }

    @media screen and (min-width: 992px) {
        .section-center {
            width: 95vw;
        }
    }

    main {
        min-height: 100vh;
        display: grid;
        place-items: center;
    }

    /*
===============
Sidebar
===============
*/
    .sidebar-toggle {
        position: fixed;
        top: 2rem;
        right: 3rem;
        font-size: 2rem;
        background: transparent;
        border-color: transparent;
        color: var(--clr-primary-5);
        transition: var(--transition);
        cursor: pointer;
        animation: bounce 2s ease-in-out infinite;
    }

    .sidebar-toggle:hover {
        color: var(--clr-primary-7);
    }

    @keyframes bounce {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.5);
        }

        100% {
            transform: scale(1);
        }
    }

    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .close-btn {
        font-size: 1.75rem;
        background: transparent;
        border-color: transparent;
        color: var(--clr-primary-5);
        transition: var(--transition);
        cursor: pointer;
        color: var(--clr-red-dark);
    }

    .close-btn:hover {
        color: var(--clr-red-light);
        transform: rotate(360deg);
    }

    .logo {
        justify-self: center;
        height: 40px;
    }

    .links a {
        display: block;
        font-size: 1.5rem;
        text-transform: capitalize;
        padding: 1rem 1.5rem;
        color: var(--clr-grey-5);
        transition: var(--transition);
    }

    .links a:hover {
        background: var(--clr-primary-8);
        color: var(--clr-primary-5);
        padding-left: 1.75rem;
    }

    .social-icons {
        justify-self: center;
        display: flex;
        padding-bottom: 2rem;
    }

    .social-icons a {
        font-size: 1.5rem;
        margin: 0 0.5rem;
        color: var(--clr-primary-5);
        transition: var(--transition);
    }

    .social-icons a:hover {
        color: var(--clr-primary-1);
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--clr-white);
        display: grid;
        grid-template-rows: auto 1fr auto;
        row-gap: 1rem;
        box-shadow: var(--clr-red-dark);
        transition: var(--transition);
        transform: translate(-100%);
    }

    .show-sidebar {
        transform: translate(0);
    }

    @media screen and (min-width: 676px) {
        .sidebar {
            width: 400px;
        }
    }

    .ddd{
        /* overflow: hidden;
        height: 100vh !important; */
    }
    .bx-menu{
        width: 24px;
        height: 24px;
        border-radius: 7px;
        border: 1px solid #9e9e9e73;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

{{-- endStyle --}}



<div class="wb-header">
    <div class="header nav-menu">
        <div class="h-left">
            <div class="logo">
                <i class='bx bxl-unity'></i>
                <label>ECO</label>
            </div>
            <div class="h-item">
                <i class='bx bxs-game yellow'></i>
                <label>Game</label>
            </div>
            <div class="h-item">
                <i class='bx bx-grid-alt blue'></i>
                <label>App</label>
            </div>
            <div class="h-item">
                <i class='bx bxs-award red'></i>
                <label>Top 100</label>
            </div>
        </div>
        <div class="h-right">
            {{-- <i class='bx bx-dots-horizontal-rounded'></i> --}}
            <i class='bx bx-menu toggle-menu'></i>
        </div>
        {{-- <i class='bx bx-menu toggle-menu'></i> --}}

        {{-- navResponsive --}}
        <aside class="sidebar">
            <div class="sidebarGp">
                <div class="sidebar-header">
                    <img src="logo.svg" class="logo" alt="" />
                    <button class="close-btn"><i class="fas fa-times"></i>c</button>
                </div>
                <!-- links -->
                <ul class="links">
                    <li>
                        <a href="index.html">home</a>
                    </li>
                    <li>
                        <a href="about.html">about</a>
                    </li>
                    <li>
                        <a href="projects.html">projects</a>
                    </li>
                    <li>
                        <a href="contact.html">contact</a>
                    </li>
                </ul>
                <!-- social media -->
                <ul class="social-icons">
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="fab fa-behance"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com">
                            <i class="fab fa-sketch"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>


    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.querySelector('.wb-header');
        const navMenu = document.querySelector('.wb-header .nav-menu');
        const toggleMenu = document.querySelector('.toggle-menu');
        const sidebar = document.querySelector(".sidebar");
        const closeBtn = document.querySelector(".close-btn");
        const wbContainer = document.querySelector(".content");

        // Function to handle scrolling
        function handleScroll() {
            if (window.scrollY > 20) {
                nav.classList.add('active');
            } else {
                nav.classList.remove('active');
            }
        }

        // Initial check if the page is already scrolled
        handleScroll();

        // Add scroll event listener
        window.addEventListener('scroll', handleScroll);

        // Toggle menu visibility on click
        toggleMenu.addEventListener('click', function() {
            navMenu.classList.toggle('show');
            sidebar.classList.toggle("show-sidebar");
            wbContainer.classList.toggle("ddd");
            if (navMenu.classList.contains('show')) {
                nav.classList.add('active');
            } else {
                // Only remove the 'active' class if the window scroll is less than 20
                if (window.scrollY < 20) {
                    nav.classList.remove('active');
                }
            }
        });
        closeBtn.addEventListener("click", function() {
            sidebar.classList.remove("show-sidebar");
            navMenu.classList.remove("show");
            wbContainer.classList.remove("ddd");
        });
    });
</script>
