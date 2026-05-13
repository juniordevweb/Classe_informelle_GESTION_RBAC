<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-plus"></i> Créer une Nouvelle Structure</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('structures/store') ?>" method="post">
                            <?= csrf_field() ?>

                            <!-- Informations générales -->
                            <h5 class="mb-3">Informations Générales</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nom_structure" class="form-label">Nom de la Structure *</label>
                                    <input type="text" class="form-control" id="nom_structure" name="nom_structure" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="langue_nationale" class="form-label">Langue Nationale *</label>
                                    <input type="text" class="form-control" id="langue_nationale" name="langue_nationale" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="operateur_id" class="form-label">Opérateur *</label>
                                <select class="form-select" id="operateur_id" name="operateur_id" required>
                                    <option value="">Sélectionner un opérateur</option>
                                    <?php foreach ($operateurs as $operateur): ?>
                                        <option value="<?= $operateur['id'] ?>"><?= esc($operateur['nom_organisation']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Localisation -->
                            <h5 class="mb-3">Localisation</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="region" class="form-label">Région *</label>
                                    <input type="text" class="form-control" id="region" name="region" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="departement" class="form-label">Département *</label>
                                    <input type="text" class="form-control" id="departement" name="departement" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="commune" class="form-label">Commune *</label>
                                    <input type="text" class="form-control" id="commune" name="commune" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="quartier" class="form-label">Quartier *</label>
                                    <input type="text" class="form-control" id="quartier" name="quartier" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ia" class="form-label">IA *</label>
                                    <input type="text" class="form-control" id="ia" name="ia" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="ief" class="form-label">IEF *</label>
                                    <input type="text" class="form-control" id="ief" name="ief" required>
                                </div>
                            </div>

                            <!-- Coordonnées GPS -->
                            <h5 class="mb-3">Coordonnées GPS</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Ex: 14.6937">
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Ex: -17.4441">
                                </div>
                            </div>

                            <!-- État -->
                            <h5 class="mb-3">État</h5>
                            <div class="mb-3">
                                <label for="etat" class="form-label">État *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="EN_ATTENTE">En attente</option>
                                    <option value="VALIDE">Validé</option>
                                    <option value="OUVERT">Ouvert</option>
                                    <option value="FERME">Fermé</option>
                                    <option value="GELE">Gelé</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('structures') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Créer la Structure
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>