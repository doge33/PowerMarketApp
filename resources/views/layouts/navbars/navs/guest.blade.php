<nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('argon') }}/img/brand/white.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
            <!-- Collapse header -->
            <div class="navbar-collapse-header">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navbar items -->
            <ul class="navbar-nav mr-auto">
                @auth()
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <span class="nav-link-inner--text">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @endauth
                <li class="nav-item">
                    <a href="{{ route('page.pricing') }}" class="nav-link">
                        <span class="nav-link-inner--text">Pricing</span>
                    </a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <span class="nav-link-inner--text">{{ __('Login') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <span class="nav-link-inner--text">{{ __('Register') }}</span>
                        </a>
                    </li>
                @endguest
                <!-- <li class="nav-item">
                    <a href="{{ route('page.lock') }}" class="nav-link">
                        <span class="nav-link-inner--text">Lock</span>
                    </a>
                </li> -->
                @auth()
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <span class="nav-link-inner--text">{{ __('Profile') }}</span>
                    </a>
                </li>
                @endauth
            </ul>
            <hr class="d-lg-none" />
            <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="https://www.linkedin.com/company/powermarketai" target="_blank" data-toggle="tooltip" title="" data-original-title="Follow us on LinkedIn">
                        <i class="fab fa-linkedin"></i>
                        <span class="nav-link-inner--text d-lg-none">LinkedIn</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="https://twitter.com/PowerMarketAI" target="_blank" data-toggle="tooltip" title="" data-original-title="Follow us on Twitter">
                        <i class="fab fa-twitter"></i>
                        <span class="nav-link-inner--text d-lg-none">Twitter</span>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link nav-link-icon" href="https://github.com/creativetimofficial" target="_blank" data-toggle="tooltip" title="" data-original-title="Star us on Github">
                        <i class="fab fa-crunchbase"></i>
                        <span class="nav-link-inner--text d-lg-none">Github</span>
                    </a>
                </li> -->

                <li class="nav-item d-none d-lg-block ml-lg-4">
                    @guest
                    <a href="{{ route('login') }}" class="mr-4 ml-4">
                      <!-- <span class="btn-inner--icon">
                        <i class="fas fa-shopping-cart mr-2"></i>
                      </span> -->
                      <span class="nav-link-inner--text" style="font-size: 14px;">Log In </span>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-neutral btn-icon">
                        <span class="nav-link-inner--text">Sign Up</span>
                    </a>
                    @endguest
                    @auth()
                        <form id="logout-form-guest" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="{{ route('logout') }}" class="btn btn-neutral btn-icon" onclick="event.preventDefault();
                                document.getElementById('logout-form-guest').submit();">
                            <span class="nav-link-inner--text">{{ __('Logout') }}</span>
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>
