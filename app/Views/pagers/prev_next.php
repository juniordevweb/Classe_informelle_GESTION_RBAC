<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(0);
?>
<?php if ($pager->hasPrevious() || $pager->hasNext()): ?>
    <style>
        .app-prev-next-pagination {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #e3ebf5;
            box-shadow: 0 12px 30px rgba(17, 41, 72, 0.08);
        }

        .app-prev-next-pagination .page-item .page-link {
            border: 0;
            min-width: 122px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-weight: 700;
            color: #1b4f8c;
            background: linear-gradient(180deg, #f7fbff 0%, #edf4fb 100%);
            box-shadow: inset 0 0 0 1px #d7e3f2;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease, color 0.18s ease;
        }

        .app-prev-next-pagination .page-item .page-link:hover {
            color: #123a68;
            background: linear-gradient(180deg, #ffffff 0%, #eaf2fb 100%);
            box-shadow: inset 0 0 0 1px #c7d9ed, 0 10px 22px rgba(31, 95, 170, 0.14);
            transform: translateY(-1px);
        }

        .app-prev-next-pagination .page-item.disabled .page-link {
            color: #96a6ba;
            background: #f3f6fa;
            box-shadow: inset 0 0 0 1px #e2e8f0;
            pointer-events: none;
        }

        .app-prev-next-pagination .page-status {
            min-width: 135px;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #16365c 0%, #1f5faa 100%);
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.01em;
            box-shadow: 0 12px 24px rgba(17, 53, 100, 0.18);
        }

        @media (max-width: 575.98px) {
            .app-prev-next-pagination {
                width: 100%;
                border-radius: 22px;
                padding: 0.85rem;
                flex-direction: column;
            }

            .app-prev-next-pagination .page-item,
            .app-prev-next-pagination .page-item .page-link,
            .app-prev-next-pagination .page-status {
                width: 100%;
            }
        }
    </style>
    <nav aria-label="Pagination">
        <ul class="pagination app-prev-next-pagination justify-content-center align-items-center mb-0">
            <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
                <a class="page-link rounded-pill px-3" href="<?= $pager->hasPrevious() ? $pager->getPrevious() : '#' ?>">
                    <span>&lsaquo;&nbsp; Precedente</span>
                </a>
            </li>
            <li class="page-item disabled">
                <span class="page-link page-status">
                    Page <?= esc($pager->getCurrentPageNumber()) ?> / <?= esc($pager->getPageCount()) ?>
                </span>
            </li>
            <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
                <a class="page-link rounded-pill px-3" href="<?= $pager->hasNext() ? $pager->getNext() : '#' ?>">
                    <span>Suivante &nbsp;&rsaquo;</span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
