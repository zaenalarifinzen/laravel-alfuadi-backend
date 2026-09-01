import * as Sentry from "@sentry/browser";
import axios from 'axios';

Sentry.init({
  dsn: import.meta.env.VITE_SENTRY_DSN,
  environment: import.meta.env.VITE_SENTRY_ENV,
  tracesSampleRate: 0.1,
  dataCollection: {
    // To disable sending user data and HTTP bodies, uncomment the lines below. For more info visit:
    // https://docs.sentry.io/platforms/javascript/configuration/options/#dataCollection
    // userInfo: false,
    // httpBodies: [],
  },
});

window.Sentry = Sentry;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const migrateBootstrap4DataAttributes = () => {
  const attributeMap = {
    'data-toggle': 'data-bs-toggle',
    'data-target': 'data-bs-target',
    'data-dismiss': 'data-bs-dismiss',
    'data-parent': 'data-bs-parent',
    'data-ride': 'data-bs-ride',
    'data-slide': 'data-bs-slide',
    'data-offset': 'data-bs-offset',
    'data-placement': 'data-bs-placement',
  };

  Object.entries(attributeMap).forEach(([oldName, newName]) => {
    document.querySelectorAll(`[${oldName}]`).forEach((element) => {
      if (!element.hasAttribute(newName)) {
        const value = element.getAttribute(oldName);
        element.setAttribute(newName, value);
      }
      element.removeAttribute(oldName);
    });
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', migrateBootstrap4DataAttributes);
} else {
  migrateBootstrap4DataAttributes();
}
