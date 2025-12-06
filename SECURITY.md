# Security Policy

This security policy describes how to report vulnerabilities in the
E-Baon_Online-Delivery-System project and how we handle and respond to
security reports.

## Supported Versions

- The `main` branch receives security fixes and updates.
- Tagged releases (if present) will receive critical security fixes for a
  limited period following release (generally 90 days). If you rely on a
  specific release, please notify maintainers when reporting a vulnerability.

If you are unsure whether your version is supported, open a private security
report via GitHub (recommended) or contact the maintainers (see below).

## Reporting a Vulnerability

Preferred reporting channels (in order):

- GitHub Security Advisories (preferred): use the repository security
  advisory flow on GitHub.
- Open a private issue and mark it `security` (do not include exploit
  details in a public issue).

If you cannot use GitHub's private reporting flow, send a secure email to
the maintainers. Replace the placeholder address below with the project
maintainer contact or use an encrypted message if available.

- Email (placeholder): `security@e-baon.local`
  (Please replace with an actual contact address before publishing.)

When reporting, please include:

- A short description of the issue and the impact.
- Affected version(s) or commit SHA.
- Clear, minimal steps to reproduce the issue (or a proof-of-concept).
- Any known mitigation or workaround.
- Your contact information and whether you request coordinated disclosure.

## How We Handle Reports

- Acknowledgement: We will acknowledge receipt of a valid security report
  within 3 business days.
- Triage: The report will be triaged and assigned a severity level within 7
  calendar days.
- Resolution timeline (target):
  - Critical: fix within 48 hours (or provide an official mitigation).
  - High: fix within 7 days.
  - Medium: fix within 30 days.
  - Low: fix within 90 days.

Timelines may vary depending on complexity and availability of resources.

## Coordinated Disclosure

We follow a coordinated disclosure process. We ask reporters to avoid public
disclosure until a fix or mitigation is available. Once a fix is ready, we
will:

- Notify the reporter and coordinate timing of public disclosure.
- Publish details in a security advisory and, if applicable, request a
  CVE identifier.

If the reporter prefers, we can arrange embargo periods to allow time for
downstream consumers to patch.

## PGP / GPG

If you need to send sensitive information (for example, exploit code) and
would like it encrypted, please request the maintainer's public key when
opening your report. We will publish a PGP key fingerprint here if one is
available.

## Responsible Disclosure Guidelines

- Do not publicly disclose details of a vulnerability until a fix is
  available or agreed disclosure timing has passed.
- Do not attempt to access, modify, or destroy data on production systems
  that you do not own while testing.
- Follow applicable laws in your jurisdiction when researching or reporting
  security issues.

## Contact

Report vulnerabilities via GitHub Security Advisories or open a private
security issue in this repository. If using email, replace the placeholder
address above with an encrypted/secure email to the maintainers.

For urgent or high-severity issues, please note "URGENT" in the subject
and provide a phone or secure contact method if immediate coordination is
required.

## Legal / Safe Harbor

We will not initiate legal action against good-faith security researchers
who follow this policy and act in accordance with the responsible disclosure
guidelines above.

---

If you'd like, I can also add a maintainer PGP key, an explicit contact
email, or adapt timelines to your team's SLA — tell me which you'd prefer.
