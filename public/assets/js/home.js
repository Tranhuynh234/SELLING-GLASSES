document.addEventListener('DOMContentLoaded', function() {
    // Bật cuộn mượt cho toàn bộ trang
    document.documentElement.style.scrollBehavior = "smooth";

    function highlightCards(type) {
        const cards = document.querySelectorAll('#danh-muc .group > div'); // áp glow trực tiếp lên div bg-white

        // Xóa hiệu ứng cũ
        cards.forEach(card => {
            card.classList.remove(
                'scale-105',
                'border-amber-500',
                'z-10',
                'relative',
                'transition-all',
                'duration-500',
                'shadow-[0_0_20px_rgba(245,158,11,0.7)]'
            );
        });

        let targetCards = [];
        if (type === 'gong') targetCards = [cards[0], cards[1], cards[2]];
        else if (type === 'trong') targetCards = [cards[3], cards[4], cards[5]];

        targetCards.forEach(card => {
            card.classList.add(
                'scale-105',
                'border-amber-500',
                'z-10',
                'relative',
                'transition-all',
                'duration-500',
                'shadow-[0_0_20px_rgba(245,158,11,0.7)]',
                'rounded-[32px]' 
            );

            // Tắt glow sau 3s
            setTimeout(() => {
                card.classList.remove(
                    'scale-105',
                    'border-amber-500',
                    'z-10',
                    'relative',
                    'shadow-[0_0_20px_rgba(245,158,11,0.7)]'
                );
            }, 3000);
        });
    }

    window.highlightCards = highlightCards;

    // XỬ LÝ THANH TÌM KIẾM TRÊN HEADER
    const searchToggleBtn = document.getElementById('search-toggle-btn');
    const searchBar = document.getElementById('search-bar');
    const searchInput = document.getElementById('search-input');
    const searchIcon = document.getElementById('search-icon');

    searchToggleBtn.addEventListener('click', () => {
        const isOpen = searchBar.classList.contains('w-48') || searchBar.classList.contains('w-64');

        if (!isOpen) {
            // Mở thanh tìm kiếm
            searchBar.classList.remove('w-0', 'opacity-0');
            searchBar.classList.add('w-48','md:w-64','opacity-100');
            searchIcon.classList.replace('fa-search', 'fa-times'); // Đổi icon kính lúp thành dấu X
            searchInput.focus();
        } else {
            // Đóng thanh tìm kiếm
            closeSearch();
        }
    });

    function closeSearch() {
        searchBar.classList.add('w-0', 'opacity-0');
        searchBar.classList.remove('w-48', 'md:w-64', 'opacity-100', 'border', 'border-stone-200');
        searchIcon.classList.replace('fa-times', 'fa-search');
        searchInput.value = '';
    }

    // Đóng khi click ra ngoài
    document.addEventListener('click', (e) => {
        if (!searchBar.contains(e.target) && !searchToggleBtn.contains(e.target)) {
            closeSearch();
        }
    });

    // Xử lý khi nhấn Enter để tìm kiếm
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const query = searchInput.value.trim().toLowerCase();
            if (query) {
                // Tìm tất cả các tiêu đề sản phẩm trên trang
                const productNames = document.querySelectorAll('h3');
                let found = false;

                productNames.forEach(name => {
                    if (name.innerText.toLowerCase().includes(query)) {
                        // Cuộn đến sản phẩm đó
                        name.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Hiệu ứng nháy vàng để nhận biết
                        name.parentElement.classList.add('ring-2', 'ring-amber-500', 'duration-500');
                        setTimeout(() => name.parentElement.classList.remove('ring-2', 'ring-amber-500'), 3000);
                        found = true;
                    }
                });

                if (!found) {
                    alert('Dạ EYESGLASS không tìm thấy sản phẩm: ' + query);
                }
                closeSearch();
            }
        }
    });
});