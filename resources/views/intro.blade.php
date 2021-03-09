@extends('layouts.app')

@section('content')


<div class="overlap">
    <div class="row center">
        @if($introSetting->settingValue == 0)
            <script>window.location = "/";</script>
        @else
            <div id="contact" class="col-md-6">
                @if(session()->has('message'))
                <div class="alert alert-primary">
                    {{ session()->get('message') }}
                </div>
                @endif

                @if($message ?? '' != null)
                    <div class="alert alert-primary">
                        {{ $message ?? '' }}
                    </div>
                @endif
                <form action="/intro/store" method="post">
                    @csrf
                    <br>
                    <h2 class="h2">Aanmelden voor de intro</h2>

                        <br>
                        <label for="voornaam">Voornaam</label>
                        <input class="form-control{{ $errors->has('firstName') ? ' is-invalid' : '' }}" value="{{ old('firstName') }}" type="text" id="firstName" name="firstName" placeholder="Voornaam...">

                        <br>
                        <label for="Tussenvoegsel">Tussenvoegsel</label>
                        <input class="form-control{{ $errors->has('insertion') ? ' is-invalid' : '' }}" value="{{ old('insertion') }}" type="text" id="insertion" name="insertion" placeholder="Tussenvoegsel...">

                        <br>
                        <label for="Achternaam">Achternaam</label>
                        <input class="form-control{{ $errors->has('lastName') ? ' is-invalid' : '' }}" value="{{ old('lastName') }}" type="text" id="lastName" name="lastName" placeholder="Achternaam...">

                        <br>
                        <label for="Geboortedatum">Geboortedatum</label>
                        <input class="form-control{{ $errors->has('birthday') ? ' is-invalid' : '' }}" value="{{ old('birthday') }}" type="date" id="birthday" name="birthday" placeholder="MM-DD-JJJJ...">

                        <br>
                        <label for="voornaamVoogd">Voornaam ouder/verzorger</label>
                        <input class="form-control{{ $errors->has('firstNameParent') ? ' is-invalid' : '' }}" value="{{ old('firstNameParent') }}" type="text" id="firstNameParent" name="firstNameParent" placeholder="Voornaam...">

                        <br>
                        <label for="achternaamVoogd">Achternaam ouder/verzorger</label>
                        <input class="form-control{{ $errors->has('lastNameParent') ? ' is-invalid' : '' }}" value="{{ old('lastNameParent') }}" type="text" id="lastNameParent" name="lastNameParent" placeholder="Achternaam...">
                        
                        <br>
                        <label for="adresVoogd">Adres ouder/verzorger</label>
                        <input class="form-control{{ $errors->has('adressParent') ? ' is-invalid' : '' }}" value="{{ old('adressParent') }}" type="text" id="adressParent" name="adressParent" placeholder="Adres...">


                        <br>
                        <label for="Email">E-mail</label>
                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" type="email" id="email" name="email" placeholder="E-mail...">

                        <br>
                        <label for="Telefoonnummer">Telefoon nummer</label>
                        <input class="form-control{{ $errors->has('phoneNumber') ? ' is-invalid' : '' }}" value="{{ old('phoneNumber') }}" type="phoneNumber" id="phoneNumber" name="phoneNumber" placeholder="Telefoon nummer...">

                        <br>
                        <input class="btn btn-primary" type="submit" value="Versturen">
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
