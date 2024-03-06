document.addEventListener("DOMContentLoaded", function() {

    document.getElementById('clearAllPlayersSearchSearchInput').addEventListener('click', function() {
        document.getElementById('allPlayersSearch').value = '';
        filterList('allPlayers', this.value);
    });

    document.getElementById('clearSelectedPlayersSearchInput').addEventListener('click', function() {
        document.getElementById('selectedPlayersSearch').value = '';
        filterList('selectedPlayers', this.value);
    });

    document.getElementById('allPlayersSearch').addEventListener('keyup', function() {
        filterList('allPlayers', this.value);
    });

    document.getElementById('selectedPlayersSearch').addEventListener('keyup', function() {
        filterList('selectedPlayers', this.value);
    });

    function filterList(listId, searchTerm) {
        const list = document.getElementById(listId);
        const items = list.querySelectorAll('.list-group-item');
        console.log({
            list,
            items
        });

        items.forEach(item => {
            const name = item.textContent.toLowerCase();
            const isVisible = (searchTerm && name.includes(searchTerm.toLowerCase())) || !searchTerm;
            item.classList.remove('hidden-item');
        
            if (!isVisible) {
                item.classList.add('hidden-item');
            }
        });
        
    }

    updateSelectedPlayers();

    document.querySelectorAll('.list-group').forEach(list => {
        list.addEventListener('click', function(e) {
            if (e.target.classList.contains('move')) {
                const item = e.target.parentElement;

                const targetListId = e.target.getAttribute('data-target');
                const targetList = document.getElementById(targetListId);
                targetList.appendChild(item);

                toggleMoveButton(e.target);

                updateSelectedPlayers();

                sortList('allPlayers');
                sortList('selectedPlayers');
            }
        });
    });

    function toggleMoveButton(button) {
        if (button.textContent === 'Add') {
            button.textContent = 'Remove';
            button.classList.remove('btn-primary');
            button.classList.add('btn-danger');
            button.setAttribute('data-target', 'allPlayers');
        } else {
            button.textContent = 'Add';
            button.classList.remove('btn-danger');
            button.classList.add('btn-primary');
            button.setAttribute('data-target', 'selectedPlayers');
        }
    }

    function updateSelectedPlayers() {
        const selectedIds = [...document.querySelectorAll('#selectedPlayers .list-group-item')].map(item => item.getAttribute('data-id'));
        document.getElementById('selectedPlayerIds').value = selectedIds.join(',');
    }

    function sortList(listId) {
        const list = document.getElementById(listId);
        const items = Array.from(list.querySelectorAll('.list-group-item'));

        const sortedItems = items.sort((a, b) => {
            const goalieA = a.dataset.goalkeeper === 'true' ? 0 : 1;
            const goalieB = b.dataset.goalkeeper === 'true' ? 0 : 1;
            if (goalieA !== goalieB) return goalieA - goalieB; // Prioriza goleiros
            return a.textContent.localeCompare(b.textContent); // Ordena por nome
        });

        sortedItems.forEach(item => list.appendChild(item));
    }

    function showAlert(message) {
        document.querySelector('#alertModal .modal-body').textContent = message;
        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
          keyboard: false
        });
        alertModal.show();
    }
    
});
