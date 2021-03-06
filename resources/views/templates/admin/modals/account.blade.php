@extends('templates.admin.modals.modal', ['target' => 'accountModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Account instellingen</strong></h4>
@overwrite

@section('modal-body')
    <form action="#" class="form-horizontal" id="accountForm">
        <div class="alert form-message" role="alert" style="display: none;"></div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Voornaam</label>

            <div class="col-md-8">
                <input type="text" name="firstname" value="{{ Auth::user()->firstname }}" class="form-control" placeholder="Voornaam" required autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Achternaam</label>

            <div class="col-md-8">
                <input type="text" name="lastname" value="{{ Auth::user()->lastname }}" class="form-control" placeholder="Achternaam" required />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Email</label>

            <div class="col-md-8">
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" placeholder="Email" required />
            </div>
        </div>

        <hr>
        <h4>Wachtwoord wijzigen (optioneel):</h4>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Wachtwoord</label>

            <div class="col-md-8">
                <input type="password" name="password" class="form-control" placeholder="Wachtwoord" />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Wachtwoord herhalen</label>

            <div class="col-md-8">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Wachtwoord herhalen" />
            </div>
        </div>

    </form>
@overwrite

@section('modal-footer')
    <button type="submit" form="accountForm" class="btn btn-primary">Opslaan</button>
@overwrite