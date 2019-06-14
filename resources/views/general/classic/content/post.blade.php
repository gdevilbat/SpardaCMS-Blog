@extends('blog::general.'.$theme_public->value.'.templates.parent')

@section('content')
    <section>
        <div class="container">
            <div class="row py-2">
                <div class="col-lg-7 bg-light py-2">
                    @if(!empty($post) && !empty($post->postMeta->where('meta_key', 'feature_image')->first()) && $post->postMeta->where('meta_key', 'feature_image')->first()->meta_value != null)
                        <img src="{{url('public/storage/'.$post->postMeta->where('meta_key', 'feature_image')->first()->meta_value)}}" alt=""> 
                    @endif
                    {!!$post->post_content!!}
                </div>
                <div class="col-lg-5">
                    @include('blog::general.'.$theme_public->value.'.partials.recent_post')
                    @include('blog::general.'.$theme_public->value.'.partials.category_widget')
                </div>
            </div>
        </div>
    </section>
@endsection