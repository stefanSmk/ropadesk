# RopaDesk

**Sprachen:** [English](./README.md) · Deutsch · [Français](./README.fr.md)

Self-hosted **DSGVO Art. 30 Verarbeitungsverzeichnis** (Processing Activities Register).

Pflicht für viele Unternehmen ab 250 MA — kleinere Firmen brauchen es oft für Audits und B2B-Verträge. Statt Excel: strukturiertes Register auf eigenem Server.

## Funktionen

- CRUD-API für Verarbeitungstätigkeiten
- Art. 30 Felder: Zweck, Rechtsgrundlage, Datenkategorien, Empfänger, Löschfristen, Drittlandtransfer
- JSON-Export für Audits
- SQLite, Docker, API-Key

## Start

```bash
php bin/ropadesk serve
```

```bash
curl -X POST http://127.0.0.1:8080/api/activities \
  -H "Authorization: Bearer change-me" \
  -H "Content-Type: application/json" \
  -d '{"name":"CRM","purpose":"Kundenverwaltung","legal_basis":"Art. 6 Abs. 1 lit. b DSGVO"}'
```

## Kein Rechtsrat

Dokumentation — Rechtsgrundlagen und Fristen müsst ihr selbst festlegen.

## Verwandte Projekte

- [PrivaQuest](https://github.com/stefanSmk/privaquest) — Betroffenenanfragen (Art. 12–17)
- [CookieAudit](https://github.com/stefanSmk/cookieaudit) — Cookie- und Tracker-Scanner

## Weitere Sprachen

- [English](./README.md)
- [Français](./README.fr.md)

## Lizenz

MIT
