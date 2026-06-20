 <header class="header">   
      <nav class="navbar navbar-expand-lg">
        <div class="search-panel">
          <div class="search-inner d-flex align-items-center justify-content-center">
            <div class="close-btn">Close <i class="fa fa-close"></i></div>
            <form id="searchForm" action="#">
              <div class="form-group">
                <input type="search" name="search" placeholder="What are you searching for...">
                <button type="submit" class="submit">Search</button>
              </div>
            </form>
          </div>
        </div>
        <div class="container-fluid d-flex align-items-center justify-content-between">
          <div class="navbar-header">
            <a href="{{ url('home') }}" class="navbar-brand">
              <div class="brand-text brand-big visible text-uppercase"><strong class="text-primary">Dark</strong><strong>Admin</strong></div>
              <div class="brand-text brand-sm"><strong class="text-primary">D</strong><strong>A</strong></div></a>
            <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
          </div>
          <div class="right-menu list-inline no-margin-bottom">    
            <div class="list-inline-item"><a href="#" class="search-open nav-link"><i class="icon-magnifying-glass-browser"></i></a></div>

            {{-- User messages --}}
            <div class="list-inline-item dropdown">
              <div class="d-flex align-items-center">
                <a href="{{ url('messages') }}" class="nav-link messages-toggle py-0 pl-3 pr-1" title="{{ __('admin.see_all_messages') }}">
                  <i class="icon-email"></i>
                  @if($navbar_message_count > 0)
                  <span class="badge dashbg-1">{{ $navbar_message_count }}</span>
                  @endif
                </a>
                <a id="navbarDropdownMenuLink1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link py-0 px-1">
                  <i class="fa fa-angle-down" style="font-size:10px;"></i>
                </a>
              </div>
              <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages">
                @forelse($navbar_messages as $msg)
                <a href="{{ url('messages') }}" class="dropdown-item message d-flex align-items-center">
                  <div class="profile"><i class="icon-mail fa-2x"></i></div>
                  <div class="content">
                    <strong class="d-block">{{ $msg->name }}</strong>
                    <span class="d-block">{{ Str::limit($msg->message, 50) }}</span>
                    <small class="date d-block">{{ $msg->created_at->diffForHumans() }}</small>
                  </div>
                </a>
                @empty
                <div class="dropdown-item text-muted">{{ __('admin.no_messages') }}</div>
                @endforelse
                <a href="{{ url('messages') }}" class="dropdown-item text-center message">
                  <strong>{{ __('admin.see_all_messages') }} <i class="fa fa-angle-right"></i></strong>
                </a>
              </div>
            </div>

            {{-- Available tasks --}}
            <div class="list-inline-item dropdown">
              <div class="d-flex align-items-center">
                <a href="{{ url('bookings') }}" class="nav-link tasks-toggle py-0 pl-3 pr-1" title="{{ __('admin.see_all_tasks') }}">
                  <i class="icon-new-file"></i>
                  @if($navbar_task_count > 0)
                  <span class="badge dashbg-3">{{ $navbar_task_count }}</span>
                  @endif
                </a>
                <a id="navbarDropdownMenuLink2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link py-0 px-1">
                  <i class="fa fa-angle-down" style="font-size:10px;"></i>
                </a>
              </div>
              <div aria-labelledby="navbarDropdownMenuLink2" class="dropdown-menu tasks-list">
                @forelse($navbar_tasks as $task)
                <a href="{{ $task['url'] }}" class="dropdown-item">
                  <div class="text d-flex justify-content-between">
                    <strong>{{ $task['title'] }}</strong>
                    <span>{{ $task['subtitle'] }}</span>
                  </div>
                  <div class="progress">
                    <div role="progressbar" style="width: {{ $task['percent'] }}%" aria-valuenow="{{ $task['percent'] }}" aria-valuemin="0" aria-valuemax="100" class="progress-bar {{ $task['bar'] }}"></div>
                  </div>
                </a>
                @empty
                <div class="dropdown-item text-muted">{{ __('admin.no_tasks') }}</div>
                @endforelse
                <a href="{{ url('bookings') }}" class="dropdown-item text-center">
                  <strong>{{ __('admin.see_all_tasks') }} <i class="fa fa-angle-right"></i></strong>
                </a>
              </div>
            </div>

            <!-- Megamenu-->
            <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
              <div class="dropdown-menu megamenu">
                <div class="row">
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                </div>
                <div class="row megamenu-buttons text-center">
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-1"><i class="fa fa-clock-o"></i><strong>Demo 1</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-2"><i class="fa fa-clock-o"></i><strong>Demo 2</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-3"><i class="fa fa-clock-o"></i><strong>Demo 3</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-4"><i class="fa fa-clock-o"></i><strong>Demo 4</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link bg-danger"><i class="fa fa-clock-o"></i><strong>Demo 5</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link bg-info"><i class="fa fa-clock-o"></i><strong>Demo 6</strong></a></div>
                </div>
              </div>
            </div>

            {{-- Languages: English, French, Spanish --}}
            <div class="list-inline-item dropdown">
              <a id="languages" rel="nofollow" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                <img src="admin/img/flags/16/{{ $current_locale_meta['flag'] }}" alt="{{ $current_locale_meta['label'] }}">
                <span class="d-none d-sm-inline-block">{{ $current_locale_meta['label'] }}</span>
              </a>
              <div aria-labelledby="languages" class="dropdown-menu">
                @foreach($locales as $code => $meta)
                <a rel="nofollow" href="{{ route('locale.switch', $code) }}" class="dropdown-item {{ $current_locale === $code ? 'active' : '' }}">
                  <img src="admin/img/flags/16/{{ $meta['flag'] }}" alt="{{ $meta['label'] }}" class="mr-2">
                  <span>{{ $meta['label'] }}</span>
                </a>
                @endforeach
              </div>
            </div>

            <div class="list-inline-item logout" style="padding-left: 10px;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <a class="btn btn-danger btn-sm" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </a>
                </form>
            </div>
          </div>
        </div>
      </nav>
    </header>
