<div class="card border-0 shadow-sm rounded-4 mt-4">
 <div class="card-header bg-white border-bottom d-flex justify-content-between"><h5>Règles de codification</h5><?php if ($canAddReferentiel): ?><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#codificationModal">Ajouter</button><?php endif; ?></div>
 <div class="card-body"><div class="table-responsive"><table class="table table-striped"><thead><tr><th>Type d'offre</th><th>N° d'ordre</th><th>Code de classe</th><th>Nom du site</th><th>Description</th><th>Statut</th></tr></thead><tbody>
 <?php foreach ($items as $item): ?><tr><td><?php foreach ($offerTypes as $offerType) { if ((int) $offerType['id'] === (int) $item['type_offre_id']) { echo esc($offerType['libelle']); break; } } ?></td><td><?= esc($item['numero_ordre'] ?? '') ?></td><td><?= esc($item['code'] ?? '') ?></td><td><?= esc($item['nom_site'] ?? '') ?></td><td><?= esc($item['description'] ?? '') ?></td><td><?= (int) ($item['actif'] ?? 0) === 1 ? 'Actif' : 'Inactif' ?></td></tr><?php endforeach; ?>
 </tbody></table></div></div>
</div>
<?php if ($canAddReferentiel || $canEditReferentiel): ?>
<div class="modal fade" id="codificationModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><form method="post" action="<?= base_url('configuration/codification/store') ?>" id="codificationForm">
 <?= csrf_field() ?><div class="modal-header"><h5>Ajouter une règle</h5></div><div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label">Nom du site</label><select class="form-select" name="nom_site" id="codification-site" required><option value="">Sélectionner</option><?php foreach ($sites as $site): ?><?php $linked = array_values(array_filter($items, static fn (array $item): bool => (string) ($item['nom_site'] ?? '') === (string) $site['libelle'])); ?><option value="<?= esc($site['libelle']) ?>" data-type="<?= esc($linked[0]['type_offre_id'] ?? '') ?>"><?= esc($site['libelle']) ?></option><?php endforeach; ?></select></div>
  <div class="col-md-6"><label class="form-label">Type d'offre</label><input class="form-control" id="codification-type-label" readonly><input type="hidden" name="type_offre_id" id="codification-type"></div>
  <div class="col-md-6"><label class="form-label">Numéro d'ordre</label><input class="form-control" name="numero_ordre" id="codification-order" readonly></div>
  <div class="col-md-6"><label class="form-label">Code de classe</label><input class="form-control" name="code" id="codification-code" readonly></div>
  <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description"></textarea></div><div class="form-check"><input class="form-check-input" type="checkbox" name="actif" value="1" checked> Actif</div>
 </div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Enregistrer</button></div></form></div></div></div>
<script>
document.addEventListener('DOMContentLoaded',()=>{const s=document.getElementById('codification-site'),t=document.getElementById('codification-type'),tl=document.getElementById('codification-type-label'),o=document.getElementById('codification-order'),c=document.getElementById('codification-code');const names=<?= json_encode(array_column($offerTypes,'libelle','id'),JSON_UNESCAPED_UNICODE) ?>,codes=<?= json_encode(array_column($offerTypes,'code','id')) ?>,last=<?= json_encode($nextCodes) ?>;s.addEventListener('change',()=>{t.value=s.selectedOptions[0]?.dataset.type||'';tl.value=names[t.value]||'';o.value=t.value&&last[t.value]?Number(last[t.value].slice(1,-1))+1:(t.value?1:'');c.value=t.value?(codes[t.value]||'')+o.value:'';});});
</script>
<?php endif; ?>
