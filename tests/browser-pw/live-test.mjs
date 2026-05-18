import { chromium } from 'playwright';
import { mkdirSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';

const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const EMAIL = process.env.ADMIN_EMAIL || 'taba@admin.com';
const PASSWORD = process.env.ADMIN_PASSWORD || 'admin';
const OUT = join(process.cwd(), 'artifacts');
mkdirSync(OUT, { recursive: true });

const results = [];
const consoleLogs = [];
const networkFailures = [];
const pageErrors = [];

function step(name, ok, info = '') {
  const line = `${ok ? '✓' : '✗'} ${name}${info ? ' — ' + info : ''}`;
  console.log(line);
  results.push({ name, ok, info });
}

async function shoot(page, label) {
  const path = join(OUT, `${String(results.length).padStart(2, '0')}-${label}.png`);
  await page.screenshot({ path, fullPage: true });
  return path;
}

const browser = await chromium.launch({ headless: true });
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
const page = await ctx.newPage();

page.on('console', (msg) => {
  if (['error', 'warning'].includes(msg.type())) {
    consoleLogs.push({ type: msg.type(), text: msg.text(), url: page.url() });
  }
});
page.on('pageerror', (err) => pageErrors.push({ message: err.message, url: page.url() }));
page.on('requestfailed', (req) => {
  networkFailures.push({ url: req.url(), failure: req.failure()?.errorText, where: page.url() });
});
page.on('response', (res) => {
  if (res.status() >= 500) {
    networkFailures.push({ url: res.url(), status: res.status(), where: page.url() });
  }
});

try {
  // 1. Public root
  const rootResp = await page.goto(BASE + '/', { waitUntil: 'domcontentloaded', timeout: 30000 });
  step('GET /', rootResp.ok() || rootResp.status() < 400, `HTTP ${rootResp.status()}`);
  await shoot(page, 'root');

  // 2. Admin redirect to login (long timeout — cold Filament boot can take 60s+)
  const adminResp = await page.goto(BASE + '/admin', { waitUntil: 'domcontentloaded', timeout: 120000 });
  step('GET /admin', adminResp.status() < 500, `HTTP ${adminResp.status()} -> ${page.url()}`);
  await shoot(page, 'admin-login');

  // 3. Detect login form
  const emailField = page.locator('input[type="email"], input[name="email"], input[name="data.email"]').first();
  const passField = page.locator('input[type="password"], input[name="password"], input[name="data.password"]').first();
  const hasForm = (await emailField.count()) > 0 && (await passField.count()) > 0;
  step('login form rendered', hasForm);

  if (hasForm) {
    await emailField.fill(EMAIL);
    await passField.fill(PASSWORD);
    const submit = page.locator('button[type="submit"]').first();
    await Promise.all([
      page.waitForLoadState('networkidle', { timeout: 30000 }).catch(() => {}),
      submit.click(),
    ]);
    await page.waitForTimeout(1500);
    await shoot(page, 'post-login');

    const onAdmin = /\/admin(\/|$)/.test(page.url()) && !/login/.test(page.url());
    step('login succeeded', onAdmin, `landed on ${page.url()}`);

    if (onAdmin) {
      // 4. Sidebar / navigation present
      const navLinks = await page.locator('a[href*="/admin/"]').count();
      step('admin navigation links', navLinks > 0, `${navLinks} link(s)`);

      // 5. Click each unique top-level admin link (first 8)
      const hrefs = await page.locator('a[href*="/admin/"]').evaluateAll((els) =>
        Array.from(new Set(els.map((e) => e.getAttribute('href')).filter(Boolean)))
      );
      const visit = hrefs
        .filter((h) => !h.includes('logout') && !h.includes('login') && !h.includes('?'))
        .slice(0, 8);

      for (const href of visit) {
        const url = href.startsWith('http') ? href : BASE + href;
        try {
          const r = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
          const slug = href.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '').slice(0, 40);
          await page.waitForTimeout(500);
          await shoot(page, `nav-${slug}`);
          step(`visit ${href}`, r.status() < 500, `HTTP ${r.status()}`);
        } catch (e) {
          step(`visit ${href}`, false, e.message);
        }
      }
    }
  }
} catch (e) {
  step('fatal', false, e.message);
} finally {
  const report = {
    base: BASE,
    when: new Date().toISOString(),
    results,
    consoleLogs,
    pageErrors,
    networkFailures,
  };
  writeFileSync(join(OUT, 'report.json'), JSON.stringify(report, null, 2));
  await browser.close();
  const pass = results.filter((r) => r.ok).length;
  const fail = results.length - pass;
  console.log(`\n=== ${pass}/${results.length} passed, ${fail} failed ===`);
  console.log(`console (warn/err): ${consoleLogs.length}  pageerrors: ${pageErrors.length}  net failures: ${networkFailures.length}`);
  process.exit(fail > 0 ? 1 : 0);
}
