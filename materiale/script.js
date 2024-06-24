document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            const spinner = document.getElementById('loading-spinner');

            function showContent() {
                document.getElementById('header').classList.remove('hidden');
                document.getElementById('content-container').classList.remove('hidden');
                overlay.style.display = 'none';
            }

            function fetchHeader() {
                return fetch('../templates/header.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('header').innerHTML = data;
                    })
                    .catch(error => console.error('Eroare la încărcarea header-ului:', error));
            }      
            Promise.all([fetchHeader()]).then(showContent);
        });