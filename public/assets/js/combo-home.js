document.addEventListener('DOMContentLoaded', function () {
    var comboSection = document.querySelector('.combo-section');
    if (!comboSection) return;

    comboSection.querySelectorAll('.combo-card').forEach(function (card) {
        card.addEventListener('click', function () {
            var id = this.dataset.comboId || this.getAttribute('data-combo-id');
            if (id) {
                window.location.href = '/SELLING-GLASSES/public/detail?comboId=' + encodeURIComponent(id);
            }
        });
    });
});