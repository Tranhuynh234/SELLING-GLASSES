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
                'rounded-[32px]' // bo góc sát card
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

    // ========== CATEGORY FILTERING ==========
    window.filterProductsByCategory = function(categoryId, categoryName, element) {
        console.log('Filtering by category ID:', categoryId, 'Name:', categoryName);
        
        // Add visual feedback
        if (element) {
            element.style.opacity = '0.7';
            element.style.transform = 'scale(0.95)';
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'scale(1)';
            }, 200);
        }

        // Redirect to filtered products page using categoryId
        window.location.href = '/SELLING-GLASSES/public/get-all-products?categoryId=' + categoryId;
    };

    // XỬ LÝ THANH TÌM KIẾM TRÊN HEADER
    const searchToggleBtn = document.getElementById('search-toggle-btn');
    const searchBar = document.getElementById('search-bar');
    const searchInput = document.getElementById('search-input');
    const searchIcon = document.getElementById('search-icon');

    if (searchToggleBtn && searchBar && searchInput && searchIcon) {
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
                const query = searchInput.value.trim();
                if (query) {
                    // Điều hướng đến trang tìm kiếm
                    window.location.href = `/SELLING-GLASSES/public/search-products?q=${encodeURIComponent(query)}`;
                } else {
                    alert('Vui lòng nhập từ khóa tìm kiếm');
                }
            }
        });
    }
});