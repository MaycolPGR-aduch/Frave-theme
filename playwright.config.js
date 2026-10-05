import { defineConfig, devices } from '@playwright/test';

/**
 * End-to-end tests of the main store journeys.
 * - Locally they run against the Laragon site (FRAVE_E2E_URL, default 127.0.0.1:8080)
 *   with the installed Chrome, so no browser download is needed.
 * - In CI they run against a disposable wp-env site seeded by tests/e2e/fixtures/seed.php.
 * One worker: the PHP built-in server handles one request at a time, and tests share
 * the store (orders, claim sheets).
 */
const isCI = Boolean(process.env.CI);

export default defineConfig({
  testDir: 'tests/e2e',
  timeout: 90_000,
  expect: { timeout: 15_000 },
  workers: 1,
  fullyParallel: false,
  retries: isCI ? 1 : 0,
  forbidOnly: isCI,
  reporter: isCI ? [['github'], ['html', { open: 'never' }]] : 'list',
  use: {
    baseURL: process.env.FRAVE_E2E_URL || 'http://127.0.0.1:8080',
    locale: 'es-PE',
    timezoneId: 'America/Lima',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    channel: isCI ? undefined : 'chrome',
  },
  projects: [
    {
      name: 'escritorio',
      use: { ...devices['Desktop Chrome'], channel: isCI ? undefined : 'chrome' },
      testIgnore: /mobile\.spec\.js/,
    },
    {
      name: 'movil',
      use: { ...devices['Pixel 7'], channel: isCI ? undefined : 'chrome' },
      testMatch: /mobile\.spec\.js/,
    },
  ],
});
