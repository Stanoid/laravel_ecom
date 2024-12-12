
<x-layout>

    <div class=" " >

        <div class=" text-xl font-medium mb-6 ">Create post</div>





@if (session('succ'))
<div>

    <x-toast-comp bg="{{'bg-green-200'}}"   msg="{{session('succ')}}" />
</div>
@endif


    <div class=" p-4 rounded-md shadow-md bg-teal-100 mx-auto w-1/2 ">
    <form action="{{route('posts.store')}}" method="post" enctype="multipart/form-data">
    @csrf
        <div class="mb-2">
            <label for="title"> Title </label>
            <input value="{{ old('title') }}"  type="text" name="title" >
            @error('title')
            {{$message}}
        @enderror
        </div>

        <div class="mb-2">
            <label for="body"> Post </label>

            <textarea rows="4" value="{{old('body') }}" name="body" >

            </textarea>
            @error('body')
            {{$message}}
        @enderror
        </div>

        <div class="mb-2">
            <label for="image"> image </label>

            <input type="file" name="image" id="image" >
            @error('image')
            {{$message}}
        @enderror

        </div>





    <div class="flex justify-center items-center" >
        <button type="submit" > Post</button>
    </div>


    </form>
    </div>

<div>
    <h2>Posts:</h2>

    @if (session('del'))
<div>

    <x-toast-comp bg="{{'bg-blue-200'}}"   msg="{{session('del')}}" />
</div>

@endif

    <div class="grid grid-cols-2 gap-4" >
        @foreach ($posts as $post)

        <x-post-card :post="$post" isdash >

           <div class="flex space-x-2">
            <form action="{{route('posts.destroy',$post)}}" method="post" class="mt-3">
                @csrf
                @method('DELETE')
                <button>edit</button>

            </form>
            <form action="{{route('posts.destroy',$post)}}" method="post" class="mt-3">
                @csrf
                @method('DELETE')
                <button>Delete</button>

            </form>
           </div>
        </x-post-card>

        @endforeach

    </div>

</div>

<div class=" my-5 " >
    {{$posts->links()}}
</div>


    </div>

    </x-layout>
