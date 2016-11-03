<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Account instellingen</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <form action="#" class="form-horizontal" id="accountForm">
        <div class="alert form-message" role="alert" style="display: none;"></div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Voornaam</label>

            <div class="col-md-8">
                <input type="text" name="firstname" value="<?php echo e(Auth::user()->firstname); ?>" class="form-control" placeholder="Voornaam" required autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Achternaam</label>

            <div class="col-md-8">
                <input type="text" name="lastname" value="<?php echo e(Auth::user()->lastname); ?>" class="form-control" placeholder="Achternaam" required />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Gebruikersnaam</label>

            <div class="col-md-8">
                <input type="text" name="username" value="<?php echo e(Auth::user()->username); ?>" class="form-control" placeholder="Gebruikersnaam" required />
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Email</label>

            <div class="col-md-8">
                <input type="email" name="email" value="<?php echo e(Auth::user()->email); ?>" class="form-control" placeholder="Email" required />
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
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" form="accountForm" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        var request = new Request('<?php echo e(cms_url('users/'.Auth::id())); ?>');
        request.setType('PATCH');
        request.setForm('#accountForm');

        request.addFields(['firstname', 'lastname', 'username', 'email']);
        request.addOptionalFields(['password', 'password_confirmation']);

        request.onSubmit(function(data) {
            $('#accountModal').modal('toggle');
            swal({
                title: 'Success!',
                text: 'Account is succesvol aangepast.',
                type: "success",
                timer: 2000
            });
            $('#userName').html(data.firstname + " " + data.lastname);
        });
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'accountModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>