# Security Policy

## Reporting

Report suspected vulnerabilities privately to the project maintainers. Do not include credentials, protected health information, access tokens, or exploit payloads in public issues.

## Production requirements

- Serve the application only over HTTPS and enable `SESSION_SECURE_COOKIE=true`.
- Keep `APP_ENV=production` and `APP_DEBUG=false`.
- Generate `APP_KEY` at deployment and store it in a secrets manager, never Git.
- Rotate `APP_KEY` only with a migration plan for encrypted data.
- Rotate database, mail, cloud, and third-party credentials regularly.
- Use separate least-privilege database and service accounts in each environment.
- Use Redis-backed cache and rate limiting when running multiple application instances.
- Restrict database and Redis network access to application hosts.
- Back up encrypted data and test restoration procedures.
- Run `composer audit`, `npm run security:audit`, and the test suite before deployment.
- Review Dependabot alerts and security workflow failures promptly.
- Forward application and security logs to access-controlled, tamper-resistant storage with defined retention.

## Sensitive data

Marketing lead email, phone, message, and preferred contact time use Laravel encrypted casts. Never collect PHI through public marketing forms. Logs record identifiers and one-way IP hashes, not request bodies, passwords, tokens, or lead content.

## Historical secret notice

The `.env` file was formerly tracked. Treat every value that ever appeared in it as potentially exposed and rotate production credentials. Removing a file from the current tree does not remove it from Git history.
