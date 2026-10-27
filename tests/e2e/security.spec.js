import { expect, test } from '@playwright/test';
import { expectNoPhpErrors } from './helpers.js';

// Hardening of the frave-peru plugin (includes/security.php).
test.describe('seguridad del sitio', () => {
  test('las páginas envían las cabeceras de seguridad y no muestran versiones', async ({ page }) => {
    const response = await page.goto('/');
    const headers = response.headers();
    expect(headers['x-content-type-options']).toBe('nosniff');
    expect(headers['x-frame-options']).toBe('SAMEORIGIN');
    expect(headers['referrer-policy']).toBe('strict-origin-when-cross-origin');
    expect(headers['permissions-policy']).toContain('camera=()');
    expect(headers['x-pingback']).toBeUndefined();
    await expect(page.locator('meta[name="generator"]')).toHaveCount(0);
    await expect(page.locator('link[rel="EditURI"]')).toHaveCount(0);

    const login = await page.goto('/wp-login.php');
    expect(login.headers()['x-frame-options']).toBe('SAMEORIGIN');
  });

  test('XML-RPC no ofrece ningún método', async ({ request }) => {
    const response = await request.post('/xmlrpc.php', {
      headers: { 'Content-Type': 'text/xml' },
      data: '<?xml version="1.0"?><methodCall><methodName>demo.sayHello</methodName></methodCall>',
    });
    const body = await response.text();
    expect(body).not.toContain('Hello!');
    expect(body).toContain('faultCode');
  });

  test('tras cinco intentos fallidos la dirección queda bloqueada, con un mensaje que no revela usuarios', async ({ page }) => {
    // The lock lasts 15 minutes for this address; no other test logs in.
    let message = '';
    for (let attempt = 1; attempt <= 6; attempt++) {
      await page.goto('/wp-login.php');
      await page.locator('#user_login').fill('e2e-no-existe');
      await page.locator('#user_pass').fill('clave-equivocada');
      await page.locator('#wp-submit').click();
      await expectNoPhpErrors(page);
      message = await page.locator('#login_error').innerText();
      if (/Demasiados intentos/.test(message)) break;
      // A wrong user gets the same message as a wrong password.
      expect(message).toContain('El usuario o la contraseña no son correctos.');
    }
    expect(message).toMatch(/Demasiados intentos fallidos\. Vuelve a intentarlo en \d+ minutos?\./);
  });
});
