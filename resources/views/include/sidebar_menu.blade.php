<?php 
$page_name =  basename($_SERVER['PHP_SELF']);
?>
<header>
    <a href="{{route('index')}}" class="logo">
        <img src="{{asset('images/logo.svg')}}" class="img-fluid" alt="VioGraf" />
    </a>
    <div class="navBlock">
        <div class="navbarBtn d-xm-none">
            <button type="button" id="sidebarCollapse" class="btn btnjs">
                <span></span>
                <span></span>
                    <span></span>
            </button>
        </div>
        <nav class="navbarLink navbar-expand-md" id="sidebar">
            <div class="navi_mobile">
                <a href="#" id="dismiss" class="hideclose d-xm-none "><i
                        class="fa fa-times"></i></a>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="{{Request::is('home') ?'active':'' }}" >
                            <a href="{{route('index')}}" >
                                <span>
                                    @if (Request::is('home'))
                                        <img src="{{asset('images/menuicon/homeactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/home.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Home
                            </a>
                        </li>

                        <li class="{{Request::is('family-feed') ?'active':'' }}">
                            <a href="{{route('familyfeed.index')}}">
                                <span>
                                    @if((Request::is('family-feed')))
                                        <img src="{{asset('images/menuicon/familyfeedactive.svg')}}" class="img-fluid"
                                        alt="Home page icon" />
                                    @else
                                        <img src="{{asset('images/menuicon/family_feed.svg')}}" class="img-fluid"
                                        alt="Home page icon" />
                                    @endif
                                </span>
                                Family Feed
                            </a>
                        </li>
                        <li class="{{Request::is('family-tree') ?'active':'' }}">
                            <a href="{{route('familytree.index')}}">
                                <span>
                                    @if (Request::is('family-tree'))
                                    <img src="{{asset('images/menuicon/familytreeactive.svg')}}" class="img-fluid"
                                        alt="Home page icon" />
                                    @else
                                        <img src="{{asset('images/menuicon/family_tree.svg')}}" class="img-fluid"
                                        alt="Home page icon" />
                                    @endif
                                    
                                </span>
                                Family Tree
                            </a>
                        </li>
                        <li class="{{Request::is('myself') || Request::is('myself/edit') || Request::is('myself/insert') ||Request::is('education') || Request::is('education/edit/*') || Request::is('education/add_education') || Request::is('education/insert') || Request::is('education/information/*') ||Request::is('career') || Request::is('career/edit/*') || Request::is('career/add_career') || Request::is('career/insert') ||  Request::is('career/information/*') ||Request::is('my-networth') || Request::is('assets/edit-asset/*') || Request::is('assets/add-assets') || Request::is('assets/assets-insert') ||  Request::is('assets/asset-information/*') ||Request::is('liability/edit-liability/*') || Request::is('liability/add-liability') || Request::is('liability/liability-insert') ||  Request::is('liability/liability-information/*') || Request::is('nominee') || Request::is('nominee/myself_added')?'active':'' }}">
                            <a href="{{route('myself.index')}}">
                                <span>
                                    @if (Request::is('myself') || Request::is('myself/edit') ||Request::is('myself/insert') ||Request::is('education') || Request::is('education/edit/*') || Request::is('education/add_education') || Request::is('education/insert') || Request::is('education/information/*') ||Request::is('career') || Request::is('career/edit/*') || Request::is('career/add_career') || Request::is('career/insert') || Request::is('career/information/*') ||Request::is('my-networth') || Request::is('assets/edit-asset/*') || Request::is('assets/add-asset') || Request::is('assets/assets-insert') ||  Request::is('assets/asset-information/*')||Request::is('liability/edit-liability/*') || Request::is('liability/add-liability') || Request::is('liability/liability-insert') ||  Request::is('liability/liability-information/*') || Request::is('nominee') || Request::is('nominee/myself_added') )
                                        <img src="{{asset('images/menuicon/myselfactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/myself.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Myself
                            </a>
                        </li>
                        <li class="{{Request::is('special-moments') || Request::is('special-moments/add') ||  Request::is('special-moments/insert') ||Request::is('special-moments/information/*') || Request::is('special-moments/edit/*')   ?'active':'' }}">
                            <a href="{{route('moments.index')}}">
                                <span>
                                    @if (Request::is('special-moments') || Request::is('special-moments/add') ||Request::is('special-moments/information/*') || Request::is('special-moments/edit/*'))
                                        <img src="{{asset('images/menuicon/momentsactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/moments.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Special Moments
                            </a>
                        </li>
                        <li class="{{Request::is('diary') || Request::is('diary/add') ||Request::is('diary/information/*') || Request::is('diary/edit/*')  ?'active':'' }}">
                            <a href="{{route('diary.index')}}">
                                <span>
                                    @if (Request::is('diary') || Request::is('diary/add') ||Request::is('diary/information/*') || Request::is('diary/edit/*'))
                                        <img src="{{asset('images/menuicon/diaryactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/diary.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Diary
                            </a>
                        </li>
                        <li class="{{Request::is('experience') || Request::is('experience/add') ||Request::is('experience/information/*') || Request::is('experience/edit/*')  ?'active':'' }}">
                            <a href="{{route('experience.index')}}">
                                <span>
                                    @if (Request::is('experience') || Request::is('experience/add') ||Request::is('experience/information/*') || Request::is('experience/edit/*'))
                                        <img src="{{asset('images/menuicon/experienceactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/experiences.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Experiences
                            </a>
                        </li>
                        <li class="{{Request::is('wishlist') || Request::is('wishlist/add') ||Request::is('wishlist/information/*') || Request::is('wishlist/edit/*')  ?'active':'' }}">
                            <a href="{{route('wishlist.index')}}">
                                <span>
                                    @if (Request::is('wishlist') || Request::is('wishlist/add') ||Request::is('wishlist/information/*') || Request::is('wishlist/edit/*'))
                                        <img src="{{asset('images/menuicon/wishlistactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/wishlist.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Wishlist
                            </a>
                        </li>
                        <li class="{{Request::is('idea') || Request::is('idea/add') ||Request::is('idea/information/*') || Request::is('idea/edit/*')  ?'active':'' }}">
                            <a href="{{route('idea.index')}}">
                                <span>
                                    @if (Request::is('idea') || Request::is('idea/add') ||Request::is('idea/information/*') || Request::is('idea/edit/*'))
                                        <img src="{{asset('images/menuicon/ideasactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/ideas.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Ideas
                            </a>
                        </li>
                        <li class="{{Request::is('dream') || Request::is('dream/add') ||Request::is('dream/information/*') || Request::is('dream/edit/*')  ?'active':'' }}">
                            <a href="{{route('dream.index')}}">
                                <span>
                                    @if (Request::is('dream') || Request::is('dream/add') ||Request::is('dream/information/*') || Request::is('dream/edit/*'))
                                        <img src="{{asset('images/menuicon/dreamactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/dreams.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Dreams
                            </a>
                        </li>
                        <li class="{{Request::is('lifelesson') || Request::is('lifelesson/add') ||Request::is('lifelesson/information/*') || Request::is('lifelesson/edit/*')  ?'active':'' }}">
                            <a href="{{route('lifelesson.index')}}">
                                <span>
                                    @if (Request::is('lifelesson') || Request::is('lifelesson/add') ||Request::is('lifelesson/information/*') || Request::is('lifelesson/edit/*'))
                                        <img src="{{asset('images/menuicon/lifelessonsactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/life_lessons.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                </span>
                                Life Lessons
                            </a>
                        </li>
                        <li class="{{Request::is('first') || Request::is('first/add') ||Request::is('first/information/*') || Request::is('first/edit/*') || Request::is('last') || Request::is('last/add') ||Request::is('last/information/*') || Request::is('last/edit/*') ?'active':'' }}">
                            <a href="{{route('first.index')}}">
                                <span>
                                @if (Request::is('first') || Request::is('first/add') ||Request::is('first/information/*') || Request::is('first/edit/*') || Request::is('last') || Request::is('last/add') ||Request::is('last/information/*') || Request::is('last/edit/*'))
                                    <img src="{{asset('images/menuicon/firstlastactive.svg') }}" class="img-fluid"
                                        alt="Firsts & Lasts" />
                                @else
                                    <img src="{{asset('images/menuicon/firsts_lasts.svg') }}" class="img-fluid"
                                        alt="Firsts & Lasts" />
                                @endif
                                    
                                </span>
                                Firsts & Lasts
                            </a>
                        </li>
                        <li class="{{Request::is('spiritual-journey') || Request::is('spiritual-journey/add') ||Request::is('spiritual-journey/information/*') || Request::is('spiritual-journey/edit/*') ?'active':'' }}">
                            <a href="{{route('spiritual-journey.index')}}">
                                <span>
                                    @if (Request::is('spiritual-journey') || Request::is('spiritual-journey/add') ||Request::is('spiritual-journey/information/*') || Request::is('spiritual-journey/edit/*'))
                                        <img src="{{asset('images/menuicon/spiritualjourneyactive.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @else
                                        <img src="{{asset('images/menuicon/spiritual_journeys.svg')}}" class="img-fluid"
                                        alt="Spiritual Journeys" />
                                    @endif
                                   
                                </span>
                                Spiritual Journey
                            </a>
                        </li>
                        <li class="{{Request::is('milestone') || Request::is('milestone/add') ||Request::is('milestone/information/*') || Request::is('milestone/edit/*') ? 'active':'' }}">
                            <a href="{{route('milestone.index')}}">
                                <span>
                                    @if (Request::is('milestone') || Request::is('milestone/add') ||Request::is('milestone/information/*') || Request::is('milestone/edit/*'))
                                        <img src="{{asset('images/menuicon/milestoneactive.svg')}}" class="img-fluid"
                                        alt="MileStones" />
                                    @else
                                        <img src="{{asset('images/menuicon/milestone.svg')}}" class="img-fluid"
                                        alt="MileStones" />
                                    @endif
                                   
                                </span>
                                Milestones
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>