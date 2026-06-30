# CLAUDE.md — Progetto Chirper (Laravel API + React SPA)

Contesto di progetto per Claude Code. Questo file riassume lo stack, le decisioni
architetturali già prese e come si lavora qui. Leggilo prima di proporre modifiche.

## Cos'è questo progetto

Un progetto di apprendimento: lo sviluppatore (frontend/fullstack, esperto di React/TS,
torna a Laravel/PHP dopo 5+ anni) sta ricostruendo l'app **Chirper** del bootcamp ufficiale
Laravel ("Getting Started with Laravel"), ma **adattata** a un'architettura disaccoppiata.

Il bootcamp è la spina dorsale **backend**. Le sue parti Blade, auth-via-sessione e deploy
su Laravel Cloud NON si seguono alla lettera: si traducono nei termini di questo stack.

> **Percorso del corso → vedi [COURSE.md](./COURSE.md)**
> Guida lezione-per-lezione del bootcamp, già adattata a questa architettura: per ogni
> lezione dice se seguirla (✅), tradurla (🔁) o saltarla (⏭️), e contiene le istruzioni
> pronte da applicare. Seguire SEMPRE le indicazioni di COURSE.md quando si lavora su una
> lezione del bootcamp — NON seguire il corso alla lettera.

## Architettura — decisa, non rimettere in discussione

- **Disaccoppiata**: Laravel = backend JSON puro. React = SPA separata. Due app indipendenti,
  comunicano via HTTP.
- **NIENTE Inertia.** Valutato e scartato deliberatamente: venendo da React, non servono le
  "rotelle". Si vuole React Router e un frontend indipendente.
- **NIENTE Blade.** `welcome.blade.php` è stato rimosso. Laravel risponde solo JSON.
- Laravel è l'**unica fonte di verità** per dati, validazione e auth.

## Stack

### Backend
- **Laravel 13** (PHP 8.4 nel container)
- **Eloquent** come ORM (active record; lo schema sta nelle migration, non nel model)
- **Migrations** per lo schema DB
- **PostgreSQL 16** (in Docker — vedi sotto)
- Validazione: **FormRequest** (autorevole, lato server)
- Auth: **Laravel Sanctum**, modalità **token** (Bearer) — da introdurre quando si arriva
  alle lezioni di auth del bootcamp. NON usare le sessioni Blade del bootcamp.

### Frontend (ancora da creare)
- **React 19**, app a sé
- **React Router 7** usato come **libreria di routing** (NON modalità framework, niente SSR,
  niente server Node — altrimenti si introduce un secondo backend, indesiderato)
- **Vite** (build SPA statica)
- **Zod** per la validazione UX; tipo TS derivato con `z.infer` (schema scritto una volta)
- Data fetching: fetch o TanStack Query (da decidere al momento del collegamento)

### Contratto tipi/validazione FE↔BE
- La validazione NON è condivisibile tra PHP e TS: due validatori distinti.
  PHP autorevole (sicurezza), Zod per la UX.
- **Per ora**: schema Zod scritto a mano sul FE, rispecchiando i FormRequest.
- **Dopo, se cresce**: generazione via OpenAPI — Scramble (Laravel) → Orval o Hey API (FE)
  per generare Zod + tipi e azzerare il drift. Direzione backend-first (PHP è la fonte).

## Ambiente di sviluppo — Docker, niente PHP/Composer sull'host

Tutto gira in Docker. NON installare PHP/Composer/Node sull'host.

- `compose.yaml`: due servizi, `app` (Laravel) e `db` (Postgres), su rete creata da Compose.
- `Dockerfile`: parte da `laravelsail/php84-composer:latest` e aggiunge il driver `pdo_pgsql`
  (l'immagine è Debian-based: `apt-get install libpq-dev` + `docker-php-ext-install pdo_pgsql`).
- `.env`: `DB_CONNECTION=pgsql`, `DB_HOST=db` (nome del servizio Compose), `DB_PORT=5432`,
  DB/USER/PASSWORD = `laravel`/`laravel`/`secret`.
- Il container `app` gira come utente `1000:1000` per evitare file di proprietà di root.
- Postgres esposto su host alla porta **5434** (solo per ispezione con GUI tipo TablePlus).

### NON usare Sail
Scelta esplicita: si vuole un mini-compose scritto a mano, trasparente, non il compose
gonfio generato da Sail. "Simplicity first."

### Comandi
```bash
docker compose up            # accende app + Postgres, migra, serve su :8000 — UN comando
docker compose up --build    # ricostruisce l'immagine (dopo modifiche al Dockerfile)
docker compose down          # spegne
docker compose down -v       # spegne e cancella i dati del DB

# Comandi artisan/composer one-off (mentre il server gira, da un altro terminale):
docker compose exec app php artisan <comando>
docker compose exec app composer <comando>
```

App su http://localhost:8000. Il DB Postgres del progetto è separato da `starter-rrv7-db`
(altro progetto reale dello sviluppatore — NON toccarlo).

## Preferenze di lavoro

- Modifiche al codice **minime e mirate**.
- Procedere **un passo alla volta**, spiegando il **perché** delle scelte, non solo i comandi.
- Feedback diretto quando una proposta non convince.
- Semplicità e reversibilità prima di tutto: è ancora una fase di "prova" di Laravel.
- Comunicazione in italiano.

## Stato attuale

- Progetto Laravel 13 scaffoldato, ambiente Docker (app + Postgres) funzionante.
- Migration di base (`users`, `cache`, `jobs`) applicate su Postgres.
- **Prossimo passo**: primo CRUD — model + migration di `Chirp`, poi primo endpoint API JSON.
  Seguire la sequenza e le istruzioni in [COURSE.md](./COURSE.md) (ordine consigliato:
  lezioni 5→6→7→9→10, poi auth Sanctum 11→12).
- Frontend React: ancora da creare.
