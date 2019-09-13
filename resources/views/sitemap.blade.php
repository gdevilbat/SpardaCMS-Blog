<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>{{url('/')}}</loc>
		<priority>1</priority>
	</url>
	@foreach ($pages as $page)
		<url>
			<loc>{{url($page->post_slug)}}</loc>
			<lastmod>{{$page->updated_at->toAtomString()}}</lastmod>
			<priority>0.5</priority>
		</url>
	@endforeach
	@foreach ($posts as $post)
		<url>
			<loc>{{url($post->created_at->format('Y').'/'.$post->created_at->format('m').'/'.$post->post_slug.'.html')}}</loc>
			<lastmod>{{$post->updated_at->toAtomString()}}</lastmod>
			<priority>0.5</priority>
		</url>
	@endforeach
</urlset>