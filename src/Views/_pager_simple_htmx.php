<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(0);

if ($pager->getPageCount() > 1) : ?>
<nav>
    <ul class="pager" hx-target="#content-list">
        <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getPrevious() ?? '#' ?>" hx-get="<?= $pager->getPrevious() ?? '#' ?>" aria-label="<?= lang('Pager.previous') ?>" class="page-link">
                <span aria-hidden="true"><?= lang('Pager.newer') ?></span>
            </a>
        </li>
        <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
            <a href="<?= $pager->getNext() ?? '#' ?>" hx-get="<?= $pager->getNext() ?? '#' ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
                <span aria-hidden="true"><?= lang('Pager.older') ?></span>
            </a>
        </li>
    </ul>
</nav>
<?php endif ?>