import { readFile, readdir } from 'node:fs/promises';
import { join } from 'node:path';

async function files(dir) {
  const entries = await readdir(dir, { withFileTypes: true });
  return (await Promise.all(entries.map((entry) => entry.isDirectory()
    ? files(join(dir, entry.name))
    : [join(dir, entry.name)]))).flat();
}

const publicViews = (await files('resources/views/public')).filter((file) => file.endsWith('.blade.php'));
const allViews = (await files('resources/views')).filter((file) => file.endsWith('.blade.php'));
const failures = [];
const warnings = [];

for (const file of publicViews) {
  const source = await readFile(file, 'utf8');
  for (const match of source.matchAll(/<img\b[^>]*>/gs)) {
    const tag = match[0];
    if (!/\balt=/.test(tag)) failures.push(`${file}: image is missing alt text`);
    if (!/\bwidth=/.test(tag) || !/\bheight=/.test(tag)) failures.push(`${file}: image is missing dimensions`);
  }
  if (/target=["']_blank["']/.test(source) && !/rel=["'][^"']*noopener/.test(source)) {
    failures.push(`${file}: new-tab link is missing rel="noopener"`);
  }
  if (/href=["']#["']/.test(source)) warnings.push(`${file}: contains a placeholder # link`);
}

const combined = (await Promise.all(allViews.map((file) => readFile(file, 'utf8')))).join('\n');
for (const requirement of [
  ['canonical metadata', /rel=["']canonical/],
  ['Open Graph metadata', /property=["']og:title/],
  ['Twitter card metadata', /name=["']twitter:card/],
  ['JSON-LD', /application\/ld\+json/],
]) {
  if (!requirement[1].test(combined)) failures.push(`Missing ${requirement[0]}`);
}

warnings.forEach((warning) => console.warn(`WARN: ${warning}`));
if (failures.length) {
  failures.forEach((failure) => console.error(`FAIL: ${failure}`));
  process.exit(1);
}
console.log(`SEO static audit passed (${publicViews.length} public templates checked).`);
