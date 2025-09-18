document.addEventListener('DOMContentLoaded', () => {
    const newsContainer = document.getElementById('news-list');
    let lastArticleId = null;

    if ('Notification' in window) {
        Notification.requestPermission();
    }

    let serverData = [
        { "id": 10, "title": "AI in Healthcare", "publicationDate": "2023-08-01T12:58:04Z", "author": "John Doe", "imageUrl": "http://localhost:3000/images/10.jpg" },
        { "id": 11, "title": "The Future of Quantum Computing", "publicationDate": "2023-08-02T13:15:20Z", "author": "Jane Smith", "imageUrl": "http://localhost:3000/images/11.jpg" },
        { "id": 12, "title": "Machine Learning Explained", "publicationDate": "2023-08-03T14:30:55Z", "author": "Alex Ray", "imageUrl": "http://localhost:3000/images/12.jpg" },
        { "id": 13, "title": "Robotics and AI Integration", "publicationDate": "2023-08-04T15:40:10Z", "author": "Eva Green", "imageUrl": "http://localhost:3000/images/13.jpg" },
        { "id": 14, "title": "Neural Networks for Beginners", "publicationDate": "2023-08-05T16:05:30Z", "author": "Peter Brown", "imageUrl": "http://localhost:3000/images/14.jpg" }
    ];

    const mockApiUrl = 'http://localhost:3000/api/news';
    const originalFetch = window.fetch;

    window.fetch = (url, options) => {
        if (url === mockApiUrl) {
            return new Promise(resolve => {
                setTimeout(() => {
                    const newId = serverData.length > 0 ? serverData[0].id + 1 : 1;
                    const newArticle = {
                        "id": newId,
                        "title": `New AI Breakthrough! ${new Date().toLocaleTimeString()}`,
                        "publicationDate": new Date().toISOString(),
                        "author": "AI Bot",
                        "imageUrl": "http://localhost:3000/images/new_article.jpg"
                    };

                    serverData.unshift(newArticle);
                    serverData = serverData.slice(0, 10);

                    resolve(new Response(JSON.stringify(serverData), {
                        status: 200,
                        headers: { 'Content-Type': 'application/json' }
                    }));
                }, 500);
            });
        }
        return originalFetch(url, options);
    };

    async function getAndDisplayNews() {
        try {
            const response = await fetch(mockApiUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const news = await response.json();
            displayNews(news);
            return news;
        } catch (error) {
            console.error('Failed to fetch news. Displaying cached data if available.', error);
            return null;
        }
    }

    function showNotification(article) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const options = {
                body: article.title,
                icon: 'images/app-icon.png'
            };
            const notification = new Notification('Новая статья!', options);
            notification.onclick = () => window.focus();
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    showNotification(article);
                }
            });
        }
    }

    setInterval(async () => {
        const news = await getAndDisplayNews();

        if (news) {
            if (lastArticleId === null) {
                lastArticleId = news[0].id;
            } else if (news[0].id > lastArticleId) {
               if (document.hidden) {
                    showNotification(news[0]);
                }
                lastArticleId = news[0].id;
            }
        }
    }, 10000);

    getAndDisplayNews();

    function displayNews(news) {
        newsContainer.innerHTML = '';
        news.forEach(article => {
            const newsItem = document.createElement('div');
            newsItem.className = 'news-item';
            newsItem.innerHTML = `
                <img src="${article.imageUrl}" alt="${article.title}" class="news-item-image">
                <div class="news-item-content">
                    <h2 class="news-item-title">${article.title}</h2>
                    <p class="news-item-meta">
                        Автор: ${article.author} | Дата: ${new Date(article.publicationDate).toLocaleDateString()}
                    </p>
                </div>
            `;
            newsContainer.appendChild(newsItem);
        });
    }

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js')
                .then(reg => console.log('Service Worker registered!'))
                .catch(err => console.error('Service Worker registration failed:', err));
        });
    }
});