<?php echo $this->extend(config('Auth')->views['layout']); ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<x-unsplash>
    <div class="container d-flex justify-content-center p-5">
        <x-auth-card>
            <div class="card-body">
                <h5 class="card-title mb-5"><?= lang('Auth.emailEnterCode') ?></h5>

                <p><?= lang('Auth.emailConfirmCode') ?></p>

                <form action="<?= url_to('auth-action-verify') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Code -->
                    <div class="mb-2">
                        <input type="number" class="form-control" name="token" placeholder="000000"
                            inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" required>
                    </div>

                    <div class="d-grid col-8 mx-auto m-3">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.confirm') ?></button>
                    </div>

                </form>

                <p class="text-center"><a href="<?= site_url('/') ?>"><?= lang('Bonfire.goToFrontpage') ?></a></p>
            </div>
        </x-auth-card>
    </div>
</x-unsplash>

<?= $this->endSection() ?>