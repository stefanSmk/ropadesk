# RopaDesk

Self-hosted **GDPR Art. 30 processing activities register** (Verarbeitungsverzeichnis / registre des activités).

EU companies with 250+ employees must maintain this. Smaller firms often need it too — for audits, B2B contracts, or DPO requests. Most teams track it in Excel. RopaDesk gives you a structured register on your own server.

Pairs well with [PrivaQuest](https://github.com/stefanSmk/privaquest) (data subject requests) — requests vs. what you actually process.

## Features

- CRUD API for processing activities
- Fields aligned with Art. 30: purpose, legal basis, data categories, recipients, retention, third-country transfers
- JSON export for audits
- SQLite, Docker, API key auth
- Works alongside any locale — document in EN/DE/FR

## Quick start

```bash
git clone https://github.com/stefanSmk/ropadesk.git
cd ropadesk
php bin/ropadesk serve
```

API: `http://127.0.0.1:8080`

### Create an activity

```bash
curl -X POST http://127.0.0.1:8080/api/activities \
  -H "Authorization: Bearer change-me" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Customer CRM",
    "purpose": "Manage customer accounts",
    "legal_basis": "Art. 6(1)(b) — contract",
    "data_categories": "name, email, address",
    "data_subjects": "customers",
    "recipients": "internal sales team",
    "retention": "3 years after contract end",
    "security_measures": "TLS, access control, backups"
  }'
```

### Export for audit

```bash
curl http://127.0.0.1:8080/api/export -H "Authorization: Bearer change-me"
# or
php bin/ropadesk export
```

## API

| Method | Path | Description |
|--------|------|-------------|
| GET | `/health` | Health check |
| GET | `/api/activities` | List all |
| POST | `/api/activities` | Create |
| GET | `/api/activities/{id}` | Get one |
| PUT | `/api/activities/{id}` | Update |
| DELETE | `/api/activities/{id}` | Delete |
| GET | `/api/export` | JSON export |

## Config

| Variable | Default | Description |
|----------|---------|-------------|
| `ROPADESK_API_KEY` | `change-me` | API auth |
| `ROPADESK_DB` | `ropadesk.db` | SQLite path |
| `ROPADESK_HOST` | `127.0.0.1` | Dev server host |
| `ROPADESK_PORT` | `8080` | Dev server port |

## Tests

```bash
php tests/smoke.php
```

## Not legal advice

Helps you **document** processing. You still need to determine lawful bases and retention yourself.

## Other languages

- [Deutsch](./README.de.md)
- [Français](./README.fr.md)

## License

MIT
