<div class="container">
    <h1>IPT102 Project Prelim</h1>

    <div>
        <h3>Welcome {{$user->name}}!</h3>

        <p>
            Click the link below for verification, Thank you and Godbless
        </p>
    </div>
    <p>
        <a href="{{url('/verification/' . $user->id . '/' . $user->remember_token)}}" class="btn btn-primary form-control">
            Click here..
        </a>
    </p> 
</div>