#!/usr/bin/env python3
"""Repository-level audit for the Laravel CMS. Does not replace PHP runtime tests."""
from __future__ import annotations

import re
import subprocess
import sys
import shutil
from collections import Counter
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
errors: list[str] = []
warnings: list[str] = []
checks = 0


def text(path: Path) -> str:
    return path.read_text(encoding="utf-8", errors="ignore")


def check(condition: bool, message: str) -> None:
    global checks
    checks += 1
    if not condition:
        errors.append(message)


def app_class_path(class_name: str) -> Path:
    return ROOT / "app" / (class_name.removeprefix("App\\").replace("\\", "/") + ".php")


# Git hygiene.
tracked = set(subprocess.check_output(["git", "ls-files"], cwd=ROOT, text=True).splitlines())
for sensitive in (".env", "database/database.sqlite"):
    check(sensitive not in tracked, f"Sensitive/local file is tracked: {sensitive}")
check(not any(item.startswith("vendor/") for item in tracked), "Composer vendor directory is tracked")
check(not any(item.startswith("public/build/") for item in tracked), "Generated Vite build is tracked")

# App imports and literal view references.
php_files = list((ROOT / "app").rglob("*.php")) + list((ROOT / "routes").glob("*.php")) + list((ROOT / "config").glob("*.php"))
for path in php_files:
    source = text(path)
    for imported in re.findall(r"^use (App\\[^;]+);", source, re.M):
        imported = imported.split(" as ")[0]
        check(app_class_path(imported).exists(), f"Missing imported class {imported} in {path.relative_to(ROOT)}")
    for view in re.findall(r"(?<!Route::)view\(\s*['\"]([^'\"]+)", source):
        if view.startswith("/"):
            continue
        target = ROOT / "resources/views" / (view.replace(".", "/") + ".blade.php")
        check(target.exists(), f"Missing view {view} referenced by {path.relative_to(ROOT)}")

# Blade inheritance, includes and components.
blade_files = list((ROOT / "resources/views").rglob("*.blade.php"))
for path in blade_files:
    source = text(path)
    for _, view in re.findall(r"@(extends|include)\(\s*['\"]([^'\"]+)", source):
        target = ROOT / "resources/views" / (view.replace(".", "/") + ".blade.php")
        check(target.exists(), f"Missing Blade view {view} in {path.relative_to(ROOT)}")
    for component in re.findall(r"<x-([\w.-]+)", source):
        if component == "slot":
            continue
        target = ROOT / "resources/views/components" / (component.replace(".", "/") + ".blade.php")
        check(target.exists(), f"Missing Blade component {component} in {path.relative_to(ROOT)}")

# Explicit route controller methods.
routes = text(ROOT / "routes/web.php")
imports: dict[str, str] = {}
for statement in re.findall(r"^use ([^;]+);", routes, re.M):
    parts = statement.split(" as ")
    full = parts[0]
    imports[parts[1] if len(parts) > 1 else full.split("\\")[-1]] = full
for controller, method in re.findall(r"\[([A-Za-z_]\w*)::class,\s*['\"](\w+)['\"]\]", routes):
    full = imports.get(controller, "")
    if not full.startswith("App\\"):
        continue
    path = app_class_path(full)
    check(path.exists(), f"Missing route controller {full}")
    if path.exists():
        check(bool(re.search(rf"function\s+{re.escape(method)}\s*\(", text(path))), f"Missing route method {controller}::{method}")

# Widget contracts and templates.
for path in (ROOT / "app/Widgets").glob("*Widget.php"):
    source = text(path)
    inherited = "use LegacyWidgetAdapter;" in source
    for method in ("getId", "getName", "getIcon", "getFields", "render"):
        check(inherited or f"function {method}" in source, f"Widget {path.name} misses {method}()")
    for view in re.findall(r"view\(['\"]([^'\"]+)", source):
        target = ROOT / "resources/views" / (view.replace(".", "/") + ".blade.php")
        check(target.exists(), f"Widget {path.name} references missing view {view}")

# Duplicate table creation and basic migration ordering.
tables: list[str] = []
for path in sorted((ROOT / "database/migrations").glob("*.php")):
    tables.extend(re.findall(r"Schema::create\(['\"]([^'\"]+)", text(path)))
for table, count in Counter(tables).items():
    check(count == 1, f"Table {table} is created {count} times")

# Static public asset references and web manifest icons.
asset_sources = list((ROOT / "resources").rglob("*")) + list((ROOT / "app").rglob("*.php")) + list((ROOT / "database").rglob("*.php"))
for path in asset_sources:
    if not path.is_file():
        continue
    for asset in re.findall(r"asset\(\s*['\"]([^'\"]+)", text(path)):
        if "$" in asset or "{{" in asset or asset.startswith("storage/"):
            continue
        check((ROOT / "public" / asset).exists(), f"Missing static asset {asset} referenced by {path.relative_to(ROOT)}")
manifest_path = ROOT / "public/site.webmanifest"
if manifest_path.exists():
    import json
    try:
        manifest = json.loads(text(manifest_path))
        for icon in manifest.get("icons", []):
            asset = str(icon.get("src", "")).lstrip("/")
            check(bool(asset) and (ROOT / "public" / asset).exists(), f"Missing web manifest icon: {asset}")
    except (json.JSONDecodeError, TypeError) as exc:
        errors.append(f"Invalid web manifest: {exc}")

# Dangerous source patterns.
patterns = {
    r"\beval\s*\(": "eval() usage",
    r"\b(?:shell_exec|passthru|proc_open|system)\s*\(": "shell execution",
    r"\bunserialize\s*\(": "unsafe unserialize()",
    r"DB::unprepared\s*\(": "unprepared SQL",
}
for path in php_files:
    source = text(path)
    for pattern, label in patterns.items():
        check(not re.search(pattern, source), f"{label} in {path.relative_to(ROOT)}")

# Ensure expected enterprise modules and migrations exist.
required = [
    "app/Services/PageRenderer.php", "app/Services/BlockStyleRenderer.php",
    "app/Models/PageRevision.php", "app/Models/PageVisit.php", "app/Models/Form.php",
    "app/Models/LegalPolicy.php", "app/Models/ContentEntry.php", "app/Models/NotificationDelivery.php",
    "app/Http/Middleware/SecureHeaders.php", "app/Http/Middleware/TrackPageVisits.php",
    "config/analytics.php", "config/notifications.php", "config/integrations.php", "config/queue.php",
]
for item in required:
    check((ROOT / item).exists(), f"Required module file missing: {item}")

# Configuration defaults that must fail safely.
env_example = text(ROOT / ".env.example")
for key in ("WHATSAPP_ACCESS_TOKEN", "WHATSAPP_APP_SECRET", "CRM_WEBHOOK_SECRET", "TURNSTILE_SECRET_KEY"):
    match = re.search(rf"^{key}=(.*)$", env_example, re.M)
    check(bool(match) and match.group(1).strip() == "", f"Secret placeholder {key} must exist and be empty")

# Informational warnings.
if not shutil.which("php"):
    warnings.append("PHP is unavailable; migrations, Artisan routes and PHPUnit were not executed.")
if not shutil.which("composer"):
    warnings.append("Composer is unavailable; composer validate/audit were not executed.")

print(f"Python project audit: {checks} checks, {len(errors)} errors, {len(warnings)} warnings")
for warning in warnings:
    print(f"WARN: {warning}")
for error in errors:
    print(f"FAIL: {error}")
print(f"Files: {len(php_files)} PHP, {len(blade_files)} Blade, {len(tables)} created tables, {len(list((ROOT / 'app/Widgets').glob('*Widget.php')))} widgets")
sys.exit(1 if errors else 0)
