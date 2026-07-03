(function () {
    const searchButton = document.getElementById('btnSearchUser');
    const manualButton = document.getElementById('btnManualUser');
    const searchInput = document.getElementById('searchUser');

    if (!searchButton || !searchInput) {
        return;
    }

    const apiBase = typeof BASE_URL !== 'undefined' ? BASE_URL : '';

    function generatePassword(length = 12) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%';
        let password = '';

        for (let i = 0; i < length; i += 1) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return password;
    }

    function setFormState(user = null) {
        const btnSaveUser = document.getElementById('btnSaveUser');
        const btnSaveAndSendUser = document.getElementById('btnSaveAndSendUser');
        const fields = ['userNom', 'userPrenom', 'userIne', 'userEmail'];

        if (user) {
            fields.forEach((id) => {
                const field = document.getElementById(id);
                if (field) {
                    field.readOnly = false;
                }
            });

            document.getElementById('userNom').value = user.nom ?? '';
            document.getElementById('userPrenom').value = user.prenom ?? '';
            document.getElementById('userIne').value = user.ine ?? '';
            document.getElementById('userEmail').value = user.email ?? '';
            document.getElementById('userPassword').value = generatePassword();

            if (btnSaveUser) {
                btnSaveUser.disabled = false;
            }

            if (btnSaveAndSendUser) {
                btnSaveAndSendUser.disabled = false;
            }

            return;
        }

        fields.forEach((id) => {
            const field = document.getElementById(id);
            if (field) {
                field.value = '';
                field.readOnly = false;
            }
        });

        if (btnSaveUser) {
            btnSaveUser.disabled = false;
        }

        if (btnSaveAndSendUser) {
            btnSaveAndSendUser.disabled = false;
        }
    }

    function enableManualEntry(showNotice = false) {
        setFormState(null);

        if (!showNotice) {
            return;
        }

        Swal.fire({
            icon: 'info',
            title: 'Saisie manuelle activée',
            text: 'Vous pouvez renseigner les champs sans passer par l’API distante.',
        });
    }

    async function searchPersonnel() {
        const search = searchInput.value.trim();

        if (search === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez saisir un INE ou un mail professionnel.',
            });

            return;
        }

        searchButton.disabled = true;
        searchButton.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Recherche...';

        try {
            const response = await fetch(
                `${apiBase}users/search-personnel?q=${encodeURIComponent(search)}`
            );
            const data = await response.json();

            if (data.status === 'success' && data.user) {
                setFormState(data.user);

                Swal.fire({
                    icon: 'success',
                    title: 'Utilisateur trouvé',
                    text: 'Les informations ont été préremplies automatiquement.',
                    timer: 1800,
                    showConfirmButton: false,
                });

                return;
            }

            setFormState(null);

            const details = data.details ? `<br><br><small>Détails: ${data.details}</small>` : '';

            Swal.fire({
                icon: 'error',
                title: 'Utilisateur introuvable',
                html: (data.message ?? 'Aucune donnée ne correspond à cet INE ou mail professionnel.') + details,
            });
        } catch (error) {
            console.error(error);

            enableManualEntry(false);

            Swal.fire({
                icon: 'error',
                title: 'Erreur serveur',
                text: 'Impossible de contacter l’API de recherche. Vous pouvez saisir les informations manuellement.',
            });
        } finally {
            searchButton.disabled = false;
            searchButton.innerHTML = '<i class="fa fa-search me-1"></i> Rechercher';
        }
    }

    if (manualButton) {
        manualButton.addEventListener('click', function () {
            enableManualEntry(true);
        });
    }

    searchButton.addEventListener('click', searchPersonnel);

    searchInput.addEventListener('keyup', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchButton.click();
        }
    });
})();
