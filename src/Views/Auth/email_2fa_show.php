<?php echo $this->extend(config('Auth')->views['layout']); ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<x-unsplash >
    <div class="container d-flex justify-content-center p-5">
        <x-auth-card>
            <div class="card-body">
                <h5 class="card-title mb-5"><?= lang('Auth.email2FATitle') ?></h5>

                <p><?= lang('Auth.confirmEmailAddress') ?></p>

                <form action="<?= url_to('auth-action-handle') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="mb-2">
                        <input type="email" class="form-control" name="email"
                            inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>"
                            <?php /** @var CodeIgniter\Shield\Entities\User $user */ ?>
                            value="<?= old('email', $user->email) ?>" required>
                    </div>

                    <div class="d-grid col-8 mx-auto m-3">
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                    </div>

                </form>

                <p class="text-center"><a href="<?= site_url('/') ?>"><?= lang('Bonfire.goToFrontpage') ?></a></p>
            </div>
        </x-auth-card>
    </div>
</x-unsplash>

<?= $this->endSection() ?>