const yearList = document.getElementById('yearList');
const years = yearList.children;
const visibleYears = 7;
const itemWidth = 150;
let currentIndex = years.length - visibleYears;

function updateButtons() {
    document.getElementById('prevButton').disabled = currentIndex <= 0;
    document.getElementById('nextButton').disabled = currentIndex >= years.length - visibleYears;
}

function prevPage() {
    if (currentIndex > 0) {
        currentIndex--;
        yearList.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        updateButtons();
    }
}

function nextPage() {
    if (currentIndex < years.length - visibleYears) {
        currentIndex++;
        yearList.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        updateButtons();
    }
}

function selectYear(event) {
    const selectedYear = document.querySelector('.c-pager__year-item.--selected');
    if (selectedYear) {
        selectedYear.classList.remove('--selected');
    }
    event.target.classList.add('--selected');
}

Array.from(years).forEach(year => {
    year.addEventListener('click', selectYear);
});

// 初期状態でサンプルなので今は2024年を右端に表示
yearList.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
updateButtons();
