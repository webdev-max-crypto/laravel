// resources/js/bootstrap.js

// Default Laravel Axios setup (optional, for API requests)
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// You can add any global JS setup here
