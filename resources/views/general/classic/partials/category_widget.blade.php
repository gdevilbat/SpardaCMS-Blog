<div class="blog-lists bg-light p-2 shadow-sm my-2">
	<div class="d-flex">
	    <h5 class="m-0">Categories</h5>
	</div>
    <hr class="w-75 ml-1">
	<ul class="nav navbar-nav ml-3">
      @foreach ($category_widget as $navbar)
          @if(isset($navbar->children))
            <li class="dropdown">
              <a href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}" class="dropdown-toggle nav-link" aria-haspopup="true" aria-expanded="false">
                {{$navbar->text}}
              </a>
              <ul class="bg-transparent border-0 pl-1" aria-labelledby="navbarDropdownMenuLink">
                @include('blog::general.'.$theme_public->value.'.partials.category_widget_child', ['navbars' => $navbar->children])
              </ul>
            </li>
          @else
            <li class="border-bottom">
              <a class="nav-link" href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}">{{$navbar->text}}</a>
            </li>
          @endif
        @endforeach
    </ul>
</div>