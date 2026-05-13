<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>

<?php
$user_permissions = $user_permissions ?? [];
$performances = $performances ?? [];
$classes = $classes ?? [];
$apprenants = $apprenants ?? [];
$selected_class = $selected_class ?? null;
$selected_class_id = $selected_class_id ?? null;
$open_add_modal = $open_add_modal ?? false;

function getPerformanceBadgeClass(string $decision): string
{
    return match ($decision) {
        'Inséré' => 'bg-success',
        'Passe' => 'bg-info',
        default => 'bg-danger',
    };
}
?>

<div class="content-page">
    <div class="container mt-4">
        <br><br><br>

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h3 class="mb-2">Performances des apprenants</h3>
                <p class="text-muted mb-0">Gestion des apprenants et performances.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApprenantModal">
                    <i class="fa fa-plus me-1"></i> Ajouter apprenant
                </button>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm rounded-4 border-0 mb-4">
            <div class="card-body">
                <form method="get" action="<?= base_url('apprenant') ?>" class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label class="form-label mb-0">Filtrer par classe</label>
                    </div>
                    <div class="col-auto">
                        <select name="classe_id" class="form-select">
                            <option value="">Toutes les classes</option>
                            <?php foreach ($classes as $classe): ?>
                                <option value="<?= esc($classe['id'], 'attr') ?>" <?= $selected_class_id == $classe['id'] ? 'selected' : '' ?>><?= esc($classe['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-secondary">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_class): ?>
            <div class="alert alert-info">
                Affichage des performances pour la classe : <strong><?= esc($selected_class) ?></strong>.
            </div>
        <?php endif; ?>

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Voir détail</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($performances)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Aucun apprenant trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($performances as $item): ?>
                                    <tr>
                                        <td><?= esc($item['eleve']) ?></td>
                                        <td><?= esc($item['classe'] ?: '-') ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info me-1 voirDetailBtn" title="Voir détail"
                                                data-id="<?= esc($item['id'], 'attr') ?>"
                                                data-details="<?= esc(json_encode([
                                                    'sexe' => $item['sexe'],
                                                    'date_naissance' => $item['date_naissance'],
                                                    'lieu_naissance' => $item['lieu_naissance'],
                                                    'situation' => $item['situation'],
                                                    'derniere_classe' => $item['derniere_classe'],
                                                    'handicap' => $item['handicap'],
                                                    'nom_parent' => $item['nom_parent'],
                                                    'telephone_parent' => $item['telephone_parent'],
                                                    'region' => $item['region'],
                                                    'departement' => $item['departement'],
                                                    'commune' => $item['commune'],
                                                    'cause_descolarisation' => $item['cause_descolarisation'],
                                                    'situation_familiale' => $item['situation_familiale'],
                                                ]), 'attr') ?>"
                                                data-devoir="<?= esc($item['devoir'], 'attr') ?>"
                                                data-note1="<?= esc($item['test'], 'attr') ?>"
                                                data-note2="<?= esc($item['evaluation'], 'attr') ?>"
                                                data-composition="<?= esc($item['composition'], 'attr') ?>"
                                                data-moyenne="<?= esc($item['moyenne'], 'attr') ?>"
                                                data-notes="<?= esc(json_encode($item['notes']), 'attr') ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1 editApprenantBtn"
                                                data-id="<?= esc($item['id'], 'attr') ?>"
                                                data-details="<?= esc(json_encode([
                                                    'prenom' => explode(' ', $item['eleve'])[0] ?? '',
                                                    'nom' => explode(' ', $item['eleve'], 2)[1] ?? '',
                                                    'sexe' => $item['sexe'],
                                                    'date_naissance' => $item['date_naissance'],
                                                    'lieu_naissance' => $item['lieu_naissance'],
                                                    'situation' => $item['situation'],
                                                    'classe' => $item['classe'],
                                                    'derniere_classe' => $item['derniere_classe'],
                                                    'handicap' => $item['handicap'],
                                                    'nom_parent' => $item['nom_parent'],
                                                    'telephone_parent' => $item['telephone_parent'],
                                                    'region' => $item['region'],
                                                    'departement' => $item['departement'],
                                                    'commune' => $item['commune'],
                                                    'cause_descolarisation' => $item['cause_descolarisation'],
                                                    'situation_familiale' => $item['situation_familiale'],
                                                ]), 'attr') ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger deleteApprenantBtn" data-id="<?= esc($item['id'], 'attr') ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <?= isset($pager) ? $pager->simpleLinks('apprenants', 'prev_next') : '' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addApprenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('apprenant/save') ?>">
                <div class="modal-header bg-gradient-primary text-white border-0 rounded-top">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Ajouter apprenant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= esc(old('nom')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexe</label>
                            <select name="sexe" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="M" <?= old('sexe') === 'M' ? 'selected' : '' ?>>M</option>
                                <option value="F" <?= old('sexe') === 'F' ? 'selected' : '' ?>>F</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control" value="<?= esc(old('date_naissance')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" class="form-control" value="<?= esc(old('lieu_naissance')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Situation</label>
                            <select name="situation" class="form-select">
                                <option value="">Sélectionner</option>
                                <option value="Descolarise" <?= old('situation') === 'Descolarise' ? 'selected' : '' ?>>Déscolarisé</option>
                                <option value="Non_scolarise" <?= old('situation') === 'Non_scolarise' ? 'selected' : '' ?>>Non scolarisé</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Classe</label>
                            <select name="classe_id" class="form-select">
                                <option value="">Choisir une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= esc($classe['id'], 'attr') ?>" <?= old('classe_id') == $classe['id'] ? 'selected' : '' ?>><?= esc($classe['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dernière classe</label>
                            <input type="text" name="derniere_classe" class="form-control" value="<?= esc(old('derniere_classe')) ?>" placeholder="Ex: CE1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Handicap</label>
                            <select name="handicap" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="oui" <?= old('handicap') === 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= old('handicap') === 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom du parent</label>
                            <input type="text" name="nom_parent" class="form-control" value="<?= esc(old('nom_parent')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Téléphone parent</label>
                            <input type="text" name="telephone_parent" class="form-control" value="<?= esc(old('telephone_parent')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Région</label>
                            <input type="text" name="region" class="form-control" value="<?= esc(old('region')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Département</label>
                            <input type="text" name="departement" class="form-control" value="<?= esc(old('departement')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commune</label>
                            <input type="text" name="commune" class="form-control" value="<?= esc(old('commune')) ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Cause de déscolarisation</label>
                            <textarea name="cause_descolarisation" class="form-control" rows="2"><?= esc(old('cause_descolarisation')) ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Situation familiale</label>
                            <input type="text" name="situation_familiale" class="form-control" value="<?= esc(old('situation_familiale')) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editApprenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('apprenant/update') ?>">
                <input type="hidden" name="id" id="edit_apprenant_id">
                <div class="modal-header bg-gradient-warning text-white border-0 rounded-top">
                    <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Modifier apprenant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" id="edit_prenom" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" id="edit_nom" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sexe</label>
                            <select name="sexe" id="edit_sexe" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="date_naissance" id="edit_date_naissance" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" id="edit_lieu_naissance" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Situation</label>
                            <select name="situation" id="edit_situation" class="form-select">
                                <option value="">Sélectionner</option>
                                <option value="Descolarise">Déscolarisé</option>
                                <option value="Non_scolarise">Non scolarisé</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Classe</label>
                            <select name="classe_id" id="edit_classe_id" class="form-select">
                                <option value="">Choisir une classe</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?= esc($classe['id'], 'attr') ?>"><?= esc($classe['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dernière classe</label>
                            <input type="text" name="derniere_classe" id="edit_derniere_classe" class="form-control" placeholder="Ex: CE1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Handicap</label>
                            <select name="handicap" id="edit_handicap" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nom du parent</label>
                            <input type="text" name="nom_parent" id="edit_nom_parent" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Téléphone parent</label>
                            <input type="text" name="telephone_parent" id="edit_telephone_parent" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Région</label>
                            <input type="text" name="region" id="edit_region" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Département</label>
                            <input type="text" name="departement" id="edit_departement" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commune</label>
                            <input type="text" name="commune" id="edit_commune" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Cause de déscolarisation</label>
                            <textarea name="cause_descolarisation" id="edit_cause_descolarisation" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Situation familiale</label>
                            <input type="text" name="situation_familiale" id="edit_situation_familiale" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editApprenantBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                const details = this.dataset.details ? JSON.parse(this.dataset.details) : {};

                document.getElementById('edit_apprenant_id').value = this.dataset.id;
                document.getElementById('edit_prenom').value = details.prenom || '';
                document.getElementById('edit_nom').value = details.nom || '';
                document.getElementById('edit_sexe').value = details.sexe || '';
                document.getElementById('edit_date_naissance').value = details.date_naissance || '';
                document.getElementById('edit_lieu_naissance').value = details.lieu_naissance || '';
                document.getElementById('edit_situation').value = details.situation || '';
                document.getElementById('edit_derniere_classe').value = details.derniere_classe || '';
                document.getElementById('edit_handicap').value = details.handicap || '';
                document.getElementById('edit_nom_parent').value = details.nom_parent || '';
                document.getElementById('edit_telephone_parent').value = details.telephone_parent || '';
                document.getElementById('edit_region').value = details.region || '';
                document.getElementById('edit_departement').value = details.departement || '';
                document.getElementById('edit_commune').value = details.commune || '';
                document.getElementById('edit_cause_descolarisation').value = details.cause_descolarisation || '';
                document.getElementById('edit_situation_familiale').value = details.situation_familiale || '';

                const classeSelect = document.getElementById('edit_classe_id');
                classeSelect.value = '';
                if (details.classe) {
                    for (let i = 0; i < classeSelect.options.length; i++) {
                        if (classeSelect.options[i].text === details.classe) {
                            classeSelect.value = classeSelect.options[i].value;
                            break;
                        }
                    }
                }

                const editModal = new bootstrap.Modal(document.getElementById('editApprenantModal'));
                editModal.show();
            });
        });

        document.querySelectorAll('.deleteApprenantBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                if (!confirm('Supprimer cet apprenant ?')) {
                    return;
                }
                window.location.href = '<?= base_url('apprenant/delete') ?>/' + this.dataset.id;
            });
        });

        <?php if ($open_add_modal): ?>
            const addModal = new bootstrap.Modal(document.getElementById('addApprenantModal'));
            addModal.show();
        <?php endif; ?>

        const currentDetailNotes = [];
        let currentDetailApprenantId = null;
        let currentEditNoteIndex = null;

        function renderDetailNotes() {
            const container = document.getElementById('detailNotesList');
            if (!container) {
                return;
            }

            if (currentDetailNotes.length === 0) {
                container.innerHTML = '<div class="alert alert-secondary mb-0">Aucune note enregistrée pour cet apprenant.</div>';
                return;
            }

            const rows = currentDetailNotes.map(function (note, index) {
                return '<tr>' +
                    '<td>' + note.devoir + '</td>' +
                    '<td>' + note.note1 + '</td>' +
                    '<td>' + note.note2 + '</td>' +
                    '<td>' + note.composition + '</td>' +
                    '<td>' + note.moyenne + '</td>' +
                    '<td class="text-nowrap">' +
                    '<button type="button" class="btn btn-sm btn-outline-primary me-1 edit-note-btn" data-index="' + index + '" title="Modifier"><i class="fa fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger delete-note-btn" data-index="' + index + '" title="Supprimer"><i class="fa fa-trash"></i></button>' +
                    '</td>' +
                    '</tr>';
            }).join('');

            container.innerHTML = '<div class="table-responsive"><table class="table table-sm table-bordered mb-0"><thead><tr><th>Devoir</th><th>Note 1</th><th>Note 2</th><th>Composition</th><th>Moyenne</th><th>Actions</th></tr></thead><tbody>' + rows + '</tbody></table></div>';

            container.querySelectorAll('.edit-note-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const index = parseInt(this.dataset.index, 10);
                    editNote(index);
                });
            });

            container.querySelectorAll('.delete-note-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const index = parseInt(this.dataset.index, 10);
                    deleteNote(index);
                });
            });
        }

        function openAddNoteModal(editMode) {
            currentEditNoteIndex = editMode ? currentEditNoteIndex : null;
            document.getElementById('addNoteModalLabel').textContent = editMode ? 'Modifier une note' : 'Ajouter une note';
            if (!editMode) {
                document.getElementById('addNoteForm').reset();
                document.getElementById('new_note_index').value = '';
            }
            document.getElementById('new_note_apprenant_id').value = currentDetailApprenantId || '';
            document.getElementById('delete_note_apprenant_id').value = currentDetailApprenantId || '';
            document.getElementById('note_classe_id').value = '<?= esc($selected_class_id, 'attr') ?>';
            document.getElementById('delete_note_classe_id').value = '<?= esc($selected_class_id, 'attr') ?>';
            const addNoteModal = new bootstrap.Modal(document.getElementById('addNoteModal'));
            addNoteModal.show();
        }

        function editNote(index) {
            const note = currentDetailNotes[index];
            if (!note) {
                return;
            }

            currentEditNoteIndex = index;
            document.getElementById('new_note_devoir').value = note.devoir;
            document.getElementById('new_note_note1').value = note.note1;
            document.getElementById('new_note_note2').value = note.note2;
            document.getElementById('new_note_composition').value = note.composition;
            document.getElementById('new_note_moyenne').value = note.moyenne;
            document.getElementById('new_note_index').value = index;
            openAddNoteModal(true);
        }

        function deleteNote(index) {
            document.getElementById('delete_note_index').value = index;
            document.getElementById('deleteNoteForm').submit();
        }

        document.querySelectorAll('.voirDetailBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                const apprenantId = this.dataset.id || null;
                currentDetailApprenantId = apprenantId;
                const notes = this.dataset.notes ? JSON.parse(this.dataset.notes) : [];
                currentDetailNotes.length = 0;
                currentDetailNotes.push.apply(currentDetailNotes, notes);
                const details = this.dataset.details ? JSON.parse(this.dataset.details) : {};
                document.getElementById('new_note_apprenant_id').value = apprenantId || '';
                document.getElementById('delete_note_apprenant_id').value = apprenantId || '';
                renderApprenantInfo(details);
                currentEditNoteIndex = null;
                renderDetailNotes();
                const detailModal = new bootstrap.Modal(document.getElementById('detailApprenantModal'));
                detailModal.show();
            });
        });

        document.getElementById('openAddNoteModalBtn')?.addEventListener('click', function () {
            currentEditNoteIndex = null;
            document.getElementById('new_note_index').value = '';
            openAddNoteModal(false);
        });

        function renderApprenantInfo(details) {
            const infoContainer = document.getElementById('detailApprenantInfo');
            if (!infoContainer) {
                return;
            }

            const fields = [
                ['Sexe', details.sexe],
                ['Date de naissance', details.date_naissance],
                ['Lieu de naissance', details.lieu_naissance],
                ['Situation', details.situation],
                ['Dernière classe', details.derniere_classe],
                ['Handicap', details.handicap],
                ['Nom du parent', details.nom_parent],
                ['Téléphone parent', details.telephone_parent],
                ['Région', details.region],
                ['Département', details.departement],
                ['Commune', details.commune],
                ['Cause de déscolarisation', details.cause_descolarisation],
                ['Situation familiale', details.situation_familiale],
            ];

            infoContainer.innerHTML = fields.map(function (field) {
                return '<div class="col-md-6"><strong>' + field[0] + ' :</strong><br>' + (field[1] || '-') + '</div>';
            }).join('');
        }

        <?php if (!empty($open_detail_id)): ?>
            const openDetailBtn = document.querySelector('.voirDetailBtn[data-id="<?= esc($open_detail_id, 'attr') ?>"]');
            if (openDetailBtn) {
                openDetailBtn.click();
            }
        <?php endif; ?>
    });
</script>

<div class="modal fade" id="detailApprenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-gradient-info text-white border-0 rounded-top">
                <h5 class="modal-title"><i class="fa fa-info-circle me-2"></i> Détails du devoir</h5>
                <button type="button" class="btn btn-sm btn-light me-2" id="openAddNoteModalBtn">Ajouter des note</button>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <h6 class="mb-3">Informations apprenant</h6>
                    <div id="detailApprenantInfo" class="row g-3"></div>
                </div>
                <div class="mb-3 mt-4">
                    <h6 class="mb-3">Notes enregistrées</h6>
                    <div id="detailNotesList"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <form id="addNoteForm" method="post" action="<?= base_url('apprenant/notes/save') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="apprenant_id" id="new_note_apprenant_id" value="">
                <input type="hidden" name="note_index" id="new_note_index" value="">
                <input type="hidden" name="classe_id" id="note_classe_id" value="<?= esc($selected_class_id, 'attr') ?>">
                <div class="modal-header bg-gradient-primary text-white border-0 rounded-top">
                    <h5 id="addNoteModalLabel" class="modal-title"><i class="fa fa-plus-circle me-2"></i> Ajouter une note</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du devoir</label>
                            <input type="text" name="devoir" id="new_note_devoir" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Note 1</label>
                            <input type="number" step="0.01" name="note1" id="new_note_note1" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Note 2</label>
                            <input type="number" step="0.01" name="note2" id="new_note_note2" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Composition</label>
                            <input type="number" step="0.01" name="composition" id="new_note_composition" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Moyenne</label>
                            <input type="number" step="0.01" name="moyenne" id="new_note_moyenne" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteNoteForm" method="post" action="<?= base_url('apprenant/notes/delete') ?>" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="apprenant_id" id="delete_note_apprenant_id" value="">
    <input type="hidden" name="note_index" id="delete_note_index" value="">
    <input type="hidden" name="classe_id" id="delete_note_classe_id" value="<?= esc($selected_class_id, 'attr') ?>">
</form>

<?= $this->include('templates/footer') ?>
