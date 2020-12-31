@extends('layouts.app')

@section('content')

<script src="js/scrollonload.js"></script>
<div class="overlap" id="navlink">
    <h2>Mijn account</h2>
    <p>Zier hier jou account gegevens, transacties & overige informatie bestemd voor Salve Mundi Leden.</p>
    <nav class='myAccount' >
        @if ($authorized == 1)
        <a href="/admin">
            <i class="fas fa-user-cog"></i>
            <b>Admin</b>
        </a>
        @endif
        <a class="tablinks" onclick="openTab(event, 'gegevens')" href="#navlink">
            <i class="fas fa-user"></i>
            <b>Gegevens</b>
        </a>
        <a class="tablinks" onclick="openTab(event, 'inschrijvingen')" href="#navlink">
            <i class="fa fa-credit-card"></i>
            <b>Inschrijvingen</b>
        </a>
        <a class="tablinks" onclick="openTab(event, 'whatsapp')" href="#navlink">
            <i class="fab fa-whatsapp"></i>
            <b>Whatsapp</b>
        </a>
        <a class="tablinks" onclick="openTab(event, 'regels')" href="#navlink">
            <i class="fas fa-heart"></i>
            <b>Regels</b>
        </a>
        <span></span>
    </nav>
    <div id="gegevens" class="tabcontent">
        <h2>Jouw gegevens:</h2>
        <form method="post" action="mijnAccount/store">
            @csrf
            @if($user->visibility == 1)
            <input class="inp-cbx" id="cbx" name="cbx" type="checkbox" checked style="display: none"/>
            @elseif($user->visibility == 0)
                <input class="inp-cbx" id="cbx" name="cbx" type="checkbox" style="display: none"/>
            @endif
            <label class="cbx" for="cbx"><span>
            <svg width="12px" height="10px" viewbox="0 0 12 10">
              <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
            </svg></span><span>Ik wil op de website komen als ik bij een commissie hoor.</span></label>
            <br><br>
            <p><b>Naam:</b> {{ $user->FirstName }} </p>
            <p><b>Achternaam:</b> {{ $user->LastName }} </p>
            <p><b>Email:</b> {{ $user->email }} </p>
            <p><b>Telefoonnummer:</b> {{ $user->PhoneNumber }} </p>
            <p><b>Profiel foto:</b></p>
            {!! '<img class="pfPhoto" src="storage/'.$user->ImgPath.'" />' !!}
            <br>
            <br>
            <input type="hidden" name="user_id" id="user_id" value="{{  $user->id  }}">
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>

    <div id="inschrijvingen" class="tabcontent">
        <h1>Transacties</h1>
        <table id="table"
               data-toggle="table"
               data-url="json/data1.json">
            <thead>
            <tr>
                <th data-field="toegekend">Toegekend aan</th>
                <th data-field="inschrijving">Inschrijving</th>
                <th data-field="paymentStatus">Betalings status</th>
                <th data-field="price">Totaal prijs</th>
                <th data-field="geldigheidsdatum">Geldig tot</th>
            </tr>
            </thead>
        </table>
    </div>

    <div id="whatsapp" class="tabcontent">
        <h1>Whatsapp</h1>
    </div>

    <div id="regels" class="tabcontent">
        <h1>Regels</h1>
    </div>
</div>
    <script>
        openTab(event, 'gegevens');
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
@endsection
