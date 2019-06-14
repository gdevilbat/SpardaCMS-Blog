<div class="blog-lists bg-light p-2 shadow-sm my-2">
	<div class="d-flex">
	    <h5 class="m-0">Latest Post</h5>
	</div>
    <hr class="w-75 ml-1">
	@foreach($recent_posts as $recent_post)
	    <div class="blog-list wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
	        <div class="blog-list-image d-flex">
	        	@if(!empty($recent_post) && !empty($recent_post->postMeta->where('meta_key', 'feature_image')->first()) && $recent_post->postMeta->where('meta_key', 'feature_image')->first()->meta_value != null)
	                <img src="{{url('public/storage/'.$recent_post->postMeta->where('meta_key', 'feature_image')->first()->meta_value)}}" class="align-self-center" alt=""> 
	            @endif
	        </div>
	        <h5><a href="{{url($recent_post->created_at->format('Y').'/'.$recent_post->created_at->format('m').'/'.$recent_post->post_slug.'.html')}}">{{$recent_post->post_title}}</a></h5>
	        <div class="blog-list-meta"> <i class="icofont icofont-ui-calendar"></i> {{$recent_post->created_at->format('d/M/Y')}}</div>
	        {!!$recent_post->post_excerpt!!}
	    </div>
    @endforeach
</div>