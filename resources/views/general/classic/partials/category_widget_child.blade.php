@foreach ($navbars as $navbar)
	@if(isset($navbar->children))
		<li class="dropdown-item dropdown-submenu bg-transparent">
			<a href="{{url($navbar->slug)}}" title="{{$navbar->title}}" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">{{$navbar->text}}</a>
			<ul class="dropdown-menu bg-transparent border-0">
				@include('blog::general.'.$theme_public->value.'.partials.category_widget_child', ['navbars' => $navbar->children])
			</ul>
		</li>
	@else
		<li class="dropdown-item"><a href="{{url($navbar->slug)}}" title="{{$navbar->title}}">{{$navbar->text}}</a></li>
	@endif
@endforeach