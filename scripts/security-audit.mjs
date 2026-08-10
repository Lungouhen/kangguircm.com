import { execFileSync } from 'node:child_process';
import { readFile, readdir } from 'node:fs/promises';
import { join } from 'node:path';

async function files(dir) {
  const entries = await readdir(dir, { withFileTypes: true });
  return (await Promise.all(entries.map((entry) => entry.isDirectory()
    ? files(join(dir, entry.name))
    : [join(dir, entry.name)]))).flat();
}

const failures = [];
const tracked = execFileSync('git', ['ls-files'], { encoding: 'utf8' }).split('\n');
if (tracked.includes('.env')) failures.push('.env must not be tracked by Git');

const sourceFiles = [
  ...(await files('app')),
  ...(await files('routes')),
  ...(await files('config')),
].filter((file) => file.endsWith('.php'));

const dangerous = [
  [/\beval\s*\(/, 'eval usage'],
  [/\b(shell_exec|passthru|proc_open|system)\s*\(/, 'shell execution'],
  [/\bunserialize\s*\(/, 'unsafe unserialize'],
  [/DB::(?:select|statement|unprepared)\([^)]*\$[a-z_]/i, 'potential dynamic raw SQL'],
];
for (const file of sourceFiles) {
  const source = await readFile(file, 'utf8');
  for (const [pattern, label] of dangerous) {
    if (pattern.test(source)) failures.push(`${file}: ${label}`);
  }
}

if (failures.length) {
  failures.forEach((failure) => console.error(`FAIL: ${failure}`));
  process.exit(1);
}
console.log(`Security static audit passed (${sourceFiles.length} PHP files checked).`);
