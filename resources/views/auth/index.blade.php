
<x-layout>

    <div class=" " >

        <div class=" text-xl font-medium mb-6 ">Login</div>

    <div class=" p-4 rounded-md shadow-md bg-teal-100 w-1/2 mx-auto px-6 ">
    <form action="{{route('login')}}" method="post" >
    @csrf


        <div class="mb-2">
            <label for="email"> Email </label>

            <input type="text" value="{{ old('email') }}" name="email" >
            @error('email')
            {{$message}}
        @enderror
        </div>


        <div class="mb-2">
            <label for="password"> Password </label>
            <input type="password" name="password" >
            @error('password')
            {{$message}}
        @enderror
        </div>

    @error('er')
        <div> {{$message}} </div>
    @enderror

        <div class="mb-2">
            <label for="remember"> Remember Me </label>
            <input type="checkbox" name="remember" >

        </div>



        <button type="submit" > Login</button>

    </form>
    </div>


    </div>

    </x-layout>
