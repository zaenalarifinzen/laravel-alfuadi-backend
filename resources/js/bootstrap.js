import * as Sentry from "@sentry/browser";
import axios from 'axios';

Sentry.init({
  dsn: "https://83731e66daafc69e939b25ccd84627b3@o4511772406906880.ingest.us.sentry.io/4511772465627136",
  environment: import.meta.env.MODE,
  tracesSampleRate: 0.1,
});

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
