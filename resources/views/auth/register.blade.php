
<x-layout>

<div class=" " >

    <div class=" text-xl font-medium mb-6 ">Register an account</div>

<div class=" p-4 px-6 rounded-md shadow-md bg-teal-100 mx-auto w-1/2 ">
<form action="{{route('register')}}" method="post" >
@csrf
    <div class="mb-2">
        <label for="name"> username </label>
        <input value="{{ old('name') }}" type="text" name="name" >
        @error('name')
            {{$message}}
        @enderror
    </div>

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

    <div class="mb-2">
        <label for="password_confirmation"> Confirm password </label>
        <input type="password" name="password_confirmation" >
    </div>
    <button type="submit" > register</button>

</form>
</div>


</div>

</x-layout>
