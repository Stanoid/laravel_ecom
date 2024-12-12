
<x-layout>

{{-- @auth
    <div>Logged</div>
@endauth

@guest
    <div>Guest</div>
@endguest --}}

<div class="grid grid-cols-2 gap-4">

@foreach ($posts as $post)
<x-post-card :post="$post" />
@endforeach

</div>
<div class=" my-5 " >
    {{$posts->links()}}
</div>
</x-layout>
