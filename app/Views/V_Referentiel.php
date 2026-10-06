<?= $this->extend('templates/index') ?>
<?= $this->section('content') ?>
<?php
$canAddReferentiel = (bool) ($canAddReferentiel ?? false);
$canEditReferentiel = (bool) ($canEditReferentiel ?? false);
$canDeleteReferentiel = (bool) ($canDeleteReferentiel ?? false);
?>

<style>
    .configuration-header {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pull-left page-title"><?= esc($config['title']) ?></h3>
                    <ol class="breadcrumb pull-right">
                        <li><a href="<?= base_url('dashboard') ?>">Accueil</a></li>
                        <li><a href="<?= base_url('parametres/configuration-structures') ?>">Paramètres</a></li>
                        <li class="active"><?= esc($config['title']) ?></li>
                    </ol>
                </div>
            </div>

            <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $type => $alertClass): ?>
                <?php if ($message = session()->getFlashdata($type)): ?>
                    <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                        <?= esc($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1">Gestion du référentiel</h5>
                        <small class="text-muted"><?= count($items) ?> élément(s)</small>
                    </div>
                    <?php if ($canAddReferentiel): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#referentielModal">
                            <i class="fa fa-plus me-1"></i> Ajouter
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <?php foreach ($config['fields'] as $field): ?>
                                        <th><?= esc($config['labels'][$field]) ?></th>
                                    <?php endforeach; ?>
                                    <?php if ($canEditReferentiel || $canDeleteReferentiel): ?>
                                        <th class="text-end">Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($items === []): ?>
                                    <tr><td colspan="<?= count($config['fields']) + 1 ?>" class="text-center text-muted py-4">Aucun élément enregistré.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php foreach ($config['fields'] as $field): ?>
                                            <td>
                                                <?php if (in_array(($config['table'] ?? ''), ['regles_codification', 'nomenclatures'], true) && $field === 'type_offre_id'): ?>
                                                    <?php
                                                    $typeName = '';
                                                    foreach (($offerTypes ?? []) as $offerType) {
                                                        if ((int) $offerType['id'] === (int) ($item[$field] ?? 0)) {
                                                            $typeName = (string) $offerType['libelle'];
                                                            break;
                                                        }
                                                    }
                                                    ?>
                                                    <?= esc($typeName ?: '—') ?>
                                                <?php elseif ($field === 'actif'): ?>
                                                    <span class="badge bg-<?= (int) ($item[$field] ?? 0) === 1 ? 'success' : 'secondary' ?>">
                                                        <?= (int) ($item[$field] ?? 0) === 1 ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                <?php else: ?>
                                                    <?= esc((string) ($item[$field] ?? '')) ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <?php if ($canEditReferentiel || $canDeleteReferentiel): ?>
                                            <td class="text-end text-nowrap">
                                                <?php if ($canEditReferentiel): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-referentiel" data-bs-toggle="modal" data-bs-target="#referentielModal" data-item='<?= esc(json_encode($item, JSON_UNESCAPED_UNICODE), 'attr') ?>' title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($canDeleteReferentiel): ?>
                                                    <form method="post" action="<?= base_url('configuration/' . $slug . '/delete/' . $item['id']) ?>" class="d-inline delete-referentiel-form" data-name="<?= esc((string) ($item[$config['label']] ?? ''), 'attr') ?>">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (($referentielPageCount ?? 1) > 1): ?>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <?php $previousPage = (int) $referentielPage - 1; ?>
                            <?php $nextPage = (int) $referentielPage + 1; ?>
                            <a class="btn btn-outline-secondary <?= $previousPage < 1 ? 'disabled' : '' ?>" href="<?= $previousPage >= 1 ? base_url('configuration/' . $slug . '?page_referentiel=' . $previousPage) : '#' ?>" aria-label="Page précédente">&lt;</a>
                            <a class="btn btn-outline-secondary <?= $nextPage > (int) $referentielPageCount ? 'disabled' : '' ?>" href="<?= $nextPage <= (int) $referentielPageCount ? base_url('configuration/' . $slug . '?page_referentiel=' . $nextPage) : '#' ?>" aria-label="Page suivante">&gt;</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddReferentiel || $canEditReferentiel): ?>
<div class="modal fade" id="referentielModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('configuration/' . $slug . '/store') ?>" id="referentielForm">
                <?= csrf_field() ?>
                <div class="modal-header configuration-header border-0">
                    <h5 class="modal-title" id="referentielModalTitle">Ajouter un élément</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <?php foreach ($config['fields'] as $field): ?>
                            <?php if ($field === 'actif'): ?>
                                <div class="col-12 form-check form-switch ms-2">
                                    <input type="checkbox" class="form-check-input" name="actif" id="field-actif" value="1" checked>
                                    <label class="form-check-label" for="field-actif">Actif</label>
                                </div>
                            <?php elseif ($field === 'description'): ?>
                                <div class="col-12">
                                    <label class="form-label" for="field-<?= esc($field) ?>"><?= esc($config['labels'][$field]) ?></label>
                                    <textarea class="form-control" name="<?= esc($field) ?>" id="field-<?= esc($field) ?>" rows="3"></textarea>
                                </div>
                            <?php elseif (in_array(($config['table'] ?? ''), ['regles_codification', 'nomenclatures'], true) && $field === 'type_offre_id'): ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="field-type_offre_id">Type d'offre</label>
                                    <select class="form-select" id="field-type_offre_id" <?= ($config['table'] ?? '') === 'regles_codification' ? 'disabled' : '' ?> required>
                                        <option value="">Sélectionner un type d'offre</option>
                                        <?php foreach (($offerTypes ?? []) as $offerType): ?>
                                            <option value="<?= esc($offerType['id']) ?>" data-code="<?= esc($offerType['code']) ?>">
                                                <?= esc($offerType['libelle']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="type_offre_id" id="field-type_offre_id-value">
                                </div>
                            <?php elseif (($config['table'] ?? '') === 'regles_codification' && $field === 'numero_ordre'): ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="field-numero_ordre">Numéro d'ordre</label>
                                    <input type="number" class="form-control" name="numero_ordre" id="field-numero_ordre" readonly>
                                </div>
                            <?php elseif (in_array(($config['table'] ?? ''), ['regles_codification', 'nomenclatures'], true) && $field === 'code'): ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="field-code">Code de l’offre</label>
                                    <input type="text" class="form-control" name="code" id="field-code" readonly required placeholder="Généré automatiquement">
                                    <small class="text-muted">Le code commence par 4, contient 8 chiffres aléatoires et se termine par 1, 2, 3 ou 4.</small>
                                </div>
                            <?php elseif (($config['table'] ?? '') === 'regles_codification' && $field === 'nom_site'): ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="field-nom_site">Nom du site</label>
                                    <select class="form-select" name="nom_site" id="field-nom_site" required>
                                        <option value="">Sélectionner un site</option>
                                        <?php foreach (($sites ?? []) as $site): ?>
                                            <?php $linked = array_values(array_filter($codificationItems ?? [], static fn (array $item): bool => (string) ($item['nom_site'] ?? '') === (string) $site['libelle'])); ?>
                                            <option value="<?= esc($site['libelle']) ?>" data-type-offre="<?= esc($site['type_offre_id'] ?? ($linked[0]['type_offre_id'] ?? '')) ?>"><?= esc($site['libelle']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <div class="col-md-<?= count($config['fields']) > 4 ? '6' : '12' ?>">
                                    <label class="form-label" for="field-<?= esc($field) ?>"><?= esc($config['labels'][$field]) ?></label>
                                    <input type="<?= esc($config['types'][$field] ?? 'text') ?>" class="form-control" name="<?= esc($field) ?>" id="field-<?= esc($field) ?>" <?= $field === $config['label'] ? 'required' : '' ?><?= $slug === 'types-offre' && in_array($field, ['libelle', 'prefixe'], true) ? ' pattern="^[A-ZÀ-ÖØ-Þ].*$" title="Doit commencer par une majuscule."' : '' ?><?= $slug === 'types-offre' && $field === 'code' ? ' readonly placeholder="10 chiffres : 4XXXXXXXXX" title="Le code est généré automatiquement : 4, huit chiffres, puis le suffixe 1 à 4."' : '' ?>>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('referentielModal');
    const form = document.getElementById('referentielForm');
    const title = document.getElementById('referentielModalTitle');
    const baseAction = form.action;
    const offerLabel = document.getElementById('field-libelle');
    const offerCode = document.getElementById('field-code');
    const offerSelect = document.getElementById('field-type_offre_id');
    const siteSelect = document.getElementById('field-nom_site');
    const orderInput = document.getElementById('field-numero_ordre');
    const nextCodes = <?= json_encode($nextCodes ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const nextOrders = <?= json_encode($nextOrders ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const offerCodes = <?= json_encode(array_column($offerTypes ?? [], 'code', 'id')) ?>;

    const randomNomenclatureCode = (suffix) => {
        const middle = String(Math.floor(Math.random() * 100000000)).padStart(8, '0');
        return `4${middle}${suffix}`;
    };

    const offerSuffixFromLabel = (label) => {
        const normalized = String(label).toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        if (normalized.includes('CAF')) return '1';
        if (normalized.includes('ECB')) return '2';
        if (normalized.includes('PASSAREL') || normalized.includes('PASSEREL')) return '3';
        if (normalized.includes('DAARA')) return '4';
        return '';
    };

    if ('<?= esc($config['table']) ?>' === 'nomenclatures' && offerSelect && offerCode) {
        offerSelect.addEventListener('change', () => {
            document.getElementById('field-type_offre_id-value').value = offerSelect.value;
            const suffix = offerSuffixFromLabel(offerSelect.selectedOptions[0]?.textContent || '');
            offerCode.value = offerSelect.value && ['1', '2', '3', '4'].includes(suffix)
                ? randomNomenclatureCode(suffix)
                : '';
        });
    }

    if ('<?= esc($config['table']) ?>' === 'regles_codification' && siteSelect && offerSelect) {
        siteSelect.addEventListener('change', () => {
            const typeId = siteSelect.selectedOptions[0]?.dataset.typeOffre || '';
            offerSelect.value = typeId;
            document.getElementById('field-type_offre_id-value').value = typeId;
            const order = nextOrders[typeId] || (typeId ? 1 : '');
            if (orderInput) orderInput.value = order;
            if (offerCode) offerCode.value = typeId ? (offerCodes[typeId] || '') : '';
        });
    }

    if ('<?= esc($config['table']) ?>' === 'regles_codification' && offerSelect && offerCode) {
        offerSelect.addEventListener('change', () => {
            offerCode.value = offerCodes[offerSelect.value] || '';
        });
    }

    if ('<?= esc($slug) ?>' === 'types-offre' && offerLabel && offerCode) {
        offerCode.value = '';
        offerCode.title = 'Le code est généré automatiquement avec 10 chiffres : préfixe 4, huit chiffres aléatoires et suffixe de l’offre (1 à 4).';

        offerLabel.addEventListener('input', () => {
            const suffix = offerSuffixFromLabel(offerLabel.value);
            offerCode.value = suffix ? randomNomenclatureCode(suffix) : '';
        });
    }

    document.querySelectorAll('.edit-referentiel').forEach((button) => {
        button.addEventListener('click', () => {
            const item = JSON.parse(button.dataset.item);
            title.textContent = 'Modifier un élément';
            form.action = `${baseAction.replace('/store', '')}/update/${item.id}`;

            Object.keys(item).forEach((field) => {
                const input = document.getElementById(`field-${field}`);
                if (!input) return;
                if ('<?= esc($slug) ?>' === 'types-offre' && field === 'code') return;
                if (input.type === 'checkbox') input.checked = Number(item[field]) === 1;
                else input.value = item[field] ?? '';
            });

            if (offerSelect) {
                const typeValue = item.type_offre_id || '';
                offerSelect.value = typeValue;
                const typeHidden = document.getElementById('field-type_offre_id-value');
                if (typeHidden) typeHidden.value = typeValue;
            }

            if ('<?= esc($config['table']) ?>' === 'regles_codification' && offerSelect && offerCode) {
                offerCode.value = item.code || nextCodes[offerSelect.value] || '';
            }

        });
    });

    document.querySelectorAll('.delete-referentiel-form').forEach((deleteForm) => {
        deleteForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const name = deleteForm.dataset.name;

            Swal.fire({
                title: 'Voulez-vous vraiment supprimer ?',
                text: `« ${name} » .`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    });

    modal.addEventListener('hidden.bs.modal', () => {
        title.textContent = 'Ajouter un élément';
        form.action = baseAction;
        form.reset();
        const active = document.getElementById('field-actif');
        if (active) active.checked = true;
        if (offerCode && '<?= esc($config['table']) ?>' === 'regles_codification') offerCode.value = '';
    });
});
</script>
<?= $this->endSection() ?>
