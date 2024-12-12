
<x-layout>

<div class="font-bold mt-4 text-teal-800 text-xl" >
    {{$user->name}}'s Posts: <span class="font-extrabold" > ({{$posts->total()}}) </span>

</div>
<div class="mb-4" >
    {{$user->email}}
</div>


<div class="grid grid-cols-2 gap-4" >
    @foreach ($posts as $post)

    <x-post-card :post="$post" />
    @endforeach

</div>

<div class=" my-5 " >
    {{$posts->links()}}
</div>


</x-layout>
