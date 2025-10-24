// --- Глобальные константы (должны совпадать с теми, что в sw.js) ---
const API_BASE_URL = 'http://localhost:8000';
const LATEST_NEWS_URL = API_BASE_URL + '/api/news/latest';
const CHECK_NEW_URL = API_BASE_URL + '/api/news/check_new';
const NEWS_STORAGE_KEY = 'latest_news_data';
const LAST_ARTICLE_ID_KEY = 'last_article_id';
const POLLING_INTERVAL = 10000; // 10 секунд

// --- 1. Вспомогательная функция: Рендеринг новостей ---
function renderNews(news) {
    const newsList = document.getElementById('news-list');
    const loadingMessage = document.getElementById('loading-message');

    if (loadingMessage) loadingMessage.remove();

    if (!news || news.length === 0) {
        newsList.innerHTML = '<div class="text-center p-8 text-gray-500">Нет доступных новостей.</div>';
        return;
    }

    // Сохраняем данные и ID последней статьи в localStorage для оффлайн-резерва
    localStorage.setItem(NEWS_STORAGE_KEY, JSON.stringify(news));
    localStorage.setItem(LAST_ARTICLE_ID_KEY, news[0].id);

    // Отображаем список
    newsList.innerHTML = news.map(article => `
        <div class="article-card bg-white p-4 shadow rounded-lg flex items-start space-x-4">
            <img src="${article.image || 'https://placehold.co/100x100/CCCCCC/666666?text=No+Img'}" 
                 alt="${article.title}" 
                 class="w-24 h-24 object-cover rounded-md flex-shrink-0">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">${article.title}</h2>
                <p class="text-sm text-gray-500 mt-1">ID: ${article.id}</p>
            </div>
        </div>
    `).join('');
}


// --- 2. Основная функция: Получение и отображение данных ---
async function fetchAndDisplayNews() {
    let news;
    let isOnline = navigator.onLine;

    try {
        // Требование: Если онлайн, всегда идем в сеть.
        if (isOnline) {
            const response = await fetch(LATEST_NEWS_URL);
            if (!response.ok) {
                // Если сеть есть, но API вернул ошибку, переходим к кэшу (через SW) или localStorage.
                throw new Error("Network response not OK.");
            }
            news = await response.json();
            document.getElementById('status-indicator').style.display = 'none';
        } else {
            // Если оффлайн, принудительно используем резервный механизм
            throw new Error("Offline mode.");
        }

        renderNews(news);

    } catch (error) {
        console.error("Fetch failed (offline or network error). Trying localStorage.", error);

        // Требование: Отображаются последние загруженные новости (названия)
        const cachedData = localStorage.getItem(NEWS_STORAGE_KEY);
        if (cachedData) {
            document.getElementById('status-indicator').style.display = 'block';
            renderNews(JSON.parse(cachedData));
        } else {
            document.getElementById('news-list').innerHTML = '<div class="text-center p-8 text-red-500">Не удалось загрузить новости. Проверьте подключение.</div>';
        }
    }
}


// --- 3. Polling и Уведомления ---
async function checkNewArticles() {
    const lastKnownId = localStorage.getItem(LAST_ARTICLE_ID_KEY);

    // Если мы в автономном режиме, не опрашиваем
    if (!navigator.onLine) {
        console.log("Polling skipped: Offline.");
        return;
    }

    try {
        const response = await fetch(CHECK_NEW_URL);
        if (!response.ok) throw new Error("Check API failed.");

        const data = await response.json();
        const latestArticleId = data.latestId;

        // Сравнение ID: Проверка на новую статью
        if (latestArticleId > lastKnownId) {
            console.log(`[Polling] Найдена новая статья ID: ${latestArticleId}`);

            // Требование: Уведомление показывается, только если приложение не видно
            if (document.visibilityState === 'hidden') {

                // Проверяем разрешение на уведомления
                if (Notification.permission === 'default') {
                    await Notification.requestPermission();
                }

                if (Notification.permission === 'granted') {
                    // Используем Service Worker для отправки уведомления
                    const swReg = await navigator.serviceWorker.ready;

                    // Опционально: fetch the title of the new article for the body
                    // Мы делаем простой запрос, чтобы не тратить время
                    const newArticleTitle = `ID: ${latestArticleId} (Проверьте последние новости!)`;

                    swReg.showNotification('Обновление новостей!', {
                        body: newArticleTitle,
                        icon: 'https://placehold.co/192x192/000000/FFFFFF?text=AI',
                        tag: 'new-ai-news-update'
                    });
                }
            } else {
                // Если приложение видно, просто обновляем список без уведомления
                fetchAndDisplayNews();
            }
        }
    } catch (error) {
        console.error("Polling error:", error);
    }
}

// --- 4. Запуск приложения ---
window.addEventListener('load', () => {
    // 1. Инициализируем и отображаем новости
    fetchAndDisplayNews();

    // 2. Устанавливаем Polling каждые 10 секунд
    setInterval(checkNewArticles, POLLING_INTERVAL);

    // 3. Дополнительная логика: если пользователь возвращается на вкладку, обновляем данные
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetchAndDisplayNews();
        }
    });
})