import * as Sentry from "@sentry/browser";
import axios from 'axios';

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN,
  environment: import.meta.env.VITE_SENTRY_ENV,
  tracesSampleRate: 0.1,
});

window.Sentry = Sentry;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
