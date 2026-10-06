<?php

use CodeIgniter\Pager\PagerRenderer;

/** @var PagerRenderer $pager */
?>
<?php if ($pager->hasPrevious() || $pager->hasNext()): ?>
    <nav aria-label="Pagination">
        <ul class="pagination mb-0">
            <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $pager->hasPrevious() ? $pager->getPrevious() : '#' ?>" aria-label="Page précédente">&lt;</a>
            </li>
            <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $pager->hasNext() ? $pager->getNext() : '#' ?>" aria-label="Page suivante">&gt;</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
