$(document).ready(function() {
    // Gestion du changement de classe
    $('#classe').change(function() {
        var classeId = $(this).val();
        if (classeId) {
            window.location.href = window.location.pathname + '?classe=' + classeId;
        }
    });

    // Gestion de la recherche en temps réel
    $('#recherche').on('input', function() {
        var searchValue = $(this).val().toLowerCase();

        $('#effectifs-table tbody tr').each(function() {
            var row = $(this);
            var nomPrenom = row.find('td:eq(0)').text().toLowerCase();
            var sexe = row.find('td:eq(1)').text().toLowerCase();
            var telephone = row.find('td:eq(2)').text().toLowerCase();
            var email = row.find('td:eq(3)').text().toLowerCase();
            var parent = row.find('td:eq(4)').text().toLowerCase();

            if (nomPrenom.includes(searchValue) ||
                sexe.includes(searchValue) ||
                telephone.includes(searchValue) ||
                email.includes(searchValue) ||
                parent.includes(searchValue)) {
                row.show();
            } else {
                row.hide();
            }
        });
    });
});
