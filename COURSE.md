# Percorso bootcamp Laravel — adattato (API JSON + React, niente Blade)

Guida operativa per seguire il bootcamp ufficiale "Getting Started with Laravel"
(app Chirper) nella nostra architettura disaccoppiata. Per ogni lezione: cosa insegna,
cosa farne nel nostro caso, e come istruire Claude Code.

Base URL del corso: https://laravel.com/learn/getting-started-with-laravel/<slug>

Legenda:
- ✅ SEGUI — vale 1:1, è Laravel puro
- 🔁 TRADUCI — concetti utili, ma implementazione diversa (no Blade / Sanctum)
- ⏭️ SALTA — non riguarda la nostra architettura

---

## 1. What are we building?
slug: what-are-we-building · 1m · ⏭️ SALTA (o sfoglia)
- COSA FA: presenta Chirper, il clone di Twitter che si costruisce.
- NOI: solo contesto, niente codice. Utile sapere che il dominio è "chirp" = micro-post.

## 2. Setting up your Laravel project
slug: setting-up-your-laravel-project · 6m · ⏭️ SALTA
- COSA FA: installa PHP + Laravel sull'host, crea il progetto, parla del design AI-friendly.
- NOI: GIÀ FATTO a modo nostro (Docker + compose, niente PHP sull'host). Salta la procedura.
  Eventualmente leggi solo le note sul design "AI-friendly" per curiosità.

## 3. Your first route
slug: your-first-route · 8m · 🔁 TRADUCI (parziale)
- COSA FA: prima route + prima view Blade per una homepage.
- NOI: la SINTASSI delle route serve (in routes/web.php o routes/api.php). La parte "view Blade"
  NO. Per noi una route restituisce JSON, non una view.
- A CLAUDE CODE: "Mostrami come si definiscono le route in Laravel 13 e dove vivono
  (web.php vs api.php). Niente Blade: le nostre route restituiscono JSON."

## 4. Deploying your app
slug: deploying-your-app · 8m · ⏭️ SALTA
- COSA FA: deploy su Laravel Cloud + basi di Git.
- NOI: fuori scopo (stiamo in locale su Docker). Salta del tutto.

## 5. What is MVC?
slug: what-is-mvc · 11m · ✅ SEGUI
- COSA FA: spiega Model-View-Controller, crea il primo controller.
- NOI: vale, con una nota: la "V" (View) per noi è la risposta JSON. Controller e Model identici.
- A CLAUDE CODE: "Implementa il controller della lezione MVC, ma che restituisca JSON
  invece di una view Blade."

## 6. Working with the database
slug: working-with-the-database · 13m · ✅ SEGUI
- COSA FA: migration, creazione tabelle a codice, strumenti DB di Laravel.
- NOI: vale 1:1. Unica differenza: siamo su Postgres (non SQLite), ma il codice delle
  migration NON cambia. Qui nasce la migration di `chirps`.
- A CLAUDE CODE: "Segui questa lezione e crea la migration per la tabella chirps.
  Gira la migration con: docker compose exec app php artisan migrate."

## 7. Our first model
slug: our-first-model · 12m · ✅ SEGUI — CUORE DEL CORSO
- COSA FA: primo model Eloquent, come i model rappresentano le tabelle come oggetti PHP.
- NOI: tutta utile. È il pezzo Eloquent (l'equivalente di Drizzle, ma active record).
  Qui si definiscono relazioni, $fillable, cast.
- A CLAUDE CODE: "Crea il model Chirp con Eloquent come da lezione, inclusa la relazione
  con User e il $fillable."

## 8. Showing the feed
slug: showing-the-feed · 5m · ⏭️ SALTA
- COSA FA: Blade components + formattazione timestamp per il feed UI.
- NOI: interamente presentazione Blade. Il "feed" lo farà React consumando l'API.
  L'unico concetto da portare via: come si SERIALIZZA un model in JSON (API Resource).
- A CLAUDE CODE (in sostituzione): "Invece del feed Blade, crea un endpoint
  GET /api/chirps che restituisce i chirp in JSON. Valuta un'API Resource per la
  serializzazione."

## 9. Creating and storing Chirps
slug: creating-and-storing-chirps · 13m · 🔁 TRADUCI (backend SÌ, form NO)
- COSA FA: form per creare contenuti, gestione form, VALIDATION, mass assignment protection.
- NOI: la parte BACKEND è oro — validazione (FormRequest), $fillable, salvataggio con Eloquent.
  La parte "form Blade" si ignora: l'input arriverà come POST JSON da React.
- A CLAUDE CODE: "Implementa la creazione dei chirp come endpoint POST /api/chirps con
  validazione via FormRequest. Niente form Blade. Restituisci il chirp creato in JSON."

## 10. Edit and delete Chirps
slug: edit-and-delete-chirps · 15m · 🔁 TRADUCI (routing SÌ, view NO)
- COSA FA: completa il CRUD, routing RESTful, edit/delete.
- NOI: routing RESTful e controller validi pieni. Le view edit/delete diventano
  endpoint PUT/PATCH e DELETE. Qui entra anche l'autorizzazione (solo l'autore modifica).
- A CLAUDE CODE: "Aggiungi update e delete come PUT /api/chirps/{id} e
  DELETE /api/chirps/{id}, con policy/authorization così solo l'autore può modificare.
  Niente view, solo JSON."

## 11. Basic authentication: Registration
slug: basic-authentication-registration · 14m · 🔁 TRADUCI (concetti SÌ, impl. Sanctum)
- COSA FA: registrazione utente, hashing password, login automatico — con sessioni + form Blade.
- NOI: i CONCETTI valgono (hashing, creazione utente). Ma NON l'approccio sessioni/Blade.
  Implementiamo con LARAVEL SANCTUM in modalità token.
- A CLAUDE CODE: "NON seguire l'approccio a sessioni/Blade della lezione. Implementa la
  registrazione come endpoint API che crea l'utente e restituisce un token Sanctum.
  Installa e configura Sanctum se non presente."
- ⚠️ Questa è la traduzione più delicata. Se vuoi, ragiona prima l'impostazione Sanctum
  (in chat normale) e poi falla implementare di là.

## 12. Basic authentication: Login/Logout
slug: basic-authentication-loginlogout · 12m · 🔁 TRADUCI (concetti SÌ, impl. Sanctum)
- COSA FA: login/logout, sessioni, middleware, protezione delle rotte.
- NOI: concetto di "proteggere le rotte" validissimo. Implementazione via Sanctum:
  login = endpoint che emette token; logout = revoca token; rotte protette dal
  middleware auth:sanctum.
- A CLAUDE CODE: "Login = POST /api/login che valida le credenziali e restituisce un token
  Sanctum. Logout = revoca del token. Proteggi le rotte chirp con middleware auth:sanctum."

## 13. What's Next?
slug: whats-next · 2m · ⏭️ SALTA (o sfoglia)
- COSA FA: idee per estendere Chirper.
- NOI: spunti facoltativi. Niente di obbligatorio.

---

## Ordine consigliato

Backend "diritto" prima, in sequenza (si costruiscono uno sull'altro):
5 (MVC) → 6 (database) → 7 (model) → 9 (create/store) → 10 (edit/delete)
+ in mezzo, dopo la 7/8, l'endpoint GET /api/chirps (sostituto della lezione 8).

Poi l'auth (Sanctum) per ultima, quando il CRUD gira:
11 (registration) → 12 (login/logout).

Le route le metti in routes/api.php (non web.php), così sono già sotto il prefisso /api
e senza stato di sessione — coerente con l'architettura disaccoppiata.

## Promemoria comandi (nel container)

    docker compose up                          # accende tutto
    docker compose exec app php artisan ...     # comandi artisan
    docker compose exec app composer ...        # comandi composer

## Da NON fare (vincoli architetturali)

- Niente Blade, niente view server-rendered.
- Niente Inertia.
- Niente Sail.
- Auth solo via Sanctum token (mai sessioni/CSRF dei form Blade).
- Le route delle API in routes/api.php.
