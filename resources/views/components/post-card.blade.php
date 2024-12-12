@props(['post','isfull'=>false])

@if ($isfull)
<div class="px-4 flex py-6 shadow-lg rounded-md  flex-col-reverse  justify-between  bg-white  " >

@else
<div class="px-4 flex py-6 shadow-lg rounded-md  flex-row  justify-between  bg-white  " >

@endif




<div>
    <div class="text-gray-700 text-lg font-bold  " >
        {{$post->title}}
    </div>



    <div class="flex gap-2" >

        <div class="text-gray-500 " >
            {{$post->created_at->diffForHumans()}}
        </div>


    <a href="{{route('posts.user',$post->user)}}">
        <div class="cursor-pointer font-bold    text-teal-500  " >
            {{$post->user->name}} @if ($isfull)
                {{$post->user->email}}
            @endif
        </div>
    </div>
</a>

@if ($isfull)
<p class="text-gray-700 text-justify " >
   {{$post->body}}

</p>

{{-- <a href="{{route('posts.show',$post)}}">
    Read more {{$isfull}}
</a> --}}

@else

<p class="text-gray-700 mb-2 text-justify " >
    {{Str::words($post->body,10)}}
</p>

<a class="nav_links " href="{{route('posts.show',$post)}}">
    Read more {{$isfull}}
</a>


@endif

<div>
    {{$slot}}
</div>

</div>

<div class={{$isfull?"mx-auto":""}} >
    <img class="rounded-md" width={{$isfull?400:100}} src="{{ asset('storage/' . $post->img) }}" alt="{{ $post->title }}">

</div>
</div>
