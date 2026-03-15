# WordPress Local Modern Starter

Starter per creare in pochi minuti un ambiente WordPress locale moderno con:

- Docker Compose
- installazione automatica via WP-CLI
- tema starter gia' pronto
- frontend con Vite + Sass

## A Cosa Serve

Questo progetto serve a:

- avviare velocemente WordPress in locale
- avere una base moderna per sviluppo tema custom
- evitare setup manuale da browser
- usare HMR in sviluppo e build versionata in produzione

## Requisiti

- Docker Desktop oppure Docker Engine con Compose v2
- Node.js recente
- npm

## Avvio Rapido

Apri un terminale nella root del progetto:

```bash
cd /Users/luca/Develop/2026/wpstarter-master
```

### 1. Configura WordPress

Duplica [docker-wps/.env.example](/Users/luca/Develop/2026/wpstarter-master/docker-wps/.env.example#L1) in `docker-wps/.env`, poi modifica i valori che ti servono.

Valori di default:

```env
WP_PORT=8081
PMA_PORT=8080
DB_PORT=3306
WP_SITE_URL=http://127.0.0.1:8081
WP_SITE_TITLE=WordPress Starter
WP_ADMIN_USER=admin
WP_ADMIN_PASSWORD=admin123!
WP_ADMIN_EMAIL=admin@example.test
WP_ACTIVE_THEME=your-theme
```

### 2. Avvia e installa il sito

Esegui questi comandi dalla root del progetto:

```bash
cd docker-wps
./bootstrap.sh
```

Oppure usa un solo comando, sempre dalla root:

```bash
./docker-wps/bootstrap.sh
```

Lo script:

- avvia i container
- aspetta database e WordPress
- installa WordPress se non e' gia' installato
- attiva il tema `your-theme`
- stampa gli URL finali del sito e dell'admin
- stampa username e password admin configurati nel file `.env`
- verifica anche `siteurl` e tema attivo via WP-CLI

### 3. Installa le dipendenze frontend

Torna nella root del progetto, poi esegui:

```bash
npm install
```

### 4. Avvia Vite in sviluppo

Sempre dalla root del progetto:

```bash
npm run dev
```

Lascia questo comando aperto mentre lavori agli asset frontend.

### 5. Build produzione

Sempre dalla root del progetto:

```bash
npm run build
```

## URL Utili

- sito: `http://127.0.0.1:8081`
- admin: `http://127.0.0.1:8081/wp-admin`
- phpMyAdmin: `http://127.0.0.1:8080`

## Credenziali Di Default

- username: `admin`
- password: `admin123!`

Cambiale in [docker-wps/.env](/Users/luca/Develop/2026/wpstarter-master/docker-wps/.env#L1) se vuoi usare altri valori.

## Frontend

Configurazione Vite in [starter.config.json](/Users/luca/Develop/2026/wpstarter-master/starter.config.json#L1):

```json
{
  "themeFolder": "your-theme",
  "vite": {
    "host": "0.0.0.0",
    "port": 5173,
    "publicHost": "127.0.0.1",
    "containerHost": "host.docker.internal"
  }
}
```

### Cosa Fa Vite

Vite in questo progetto non serve il sito WordPress completo.

Serve solo gli asset frontend del tema:

- JS
- CSS
- HMR / aggiornamento live durante lo sviluppo

Quindi:

- il sito WordPress si apre su `http://127.0.0.1:8081`
- Vite gira su `http://127.0.0.1:5173`
- `5173` non e' il sito: e' solo il server degli asset

WordPress usa Vite in sviluppo e gli asset buildati in produzione:

- se `npm run dev` e' attivo, WordPress carica JS e CSS dal server Vite
- se `npm run dev` non e' attivo, WordPress usa i file buildati dentro `assets`

Entry frontend:

- [source/theme/main.js](/Users/luca/Develop/2026/wpstarter-master/source/theme/main.js#L1)

Sorgenti:

- [source/theme/js](/Users/luca/Develop/2026/wpstarter-master/source/theme/js)
- [source/theme/sass](/Users/luca/Develop/2026/wpstarter-master/source/theme/sass)

Output build:

- [assets](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/assets)

Quando `npm run dev` e' attivo:

- Vite ascolta sul tuo Mac su `127.0.0.1:5173`
- WordPress continua a stare su `127.0.0.1:8081`
- il tema carica `@vite/client` e l'entry frontend da Vite

Quando Vite non e' attivo:

- WordPress usa il manifest buildato dentro `assets/.vite/manifest.json`
- carica i file statici generati con `npm run build`

Nota:

- apri sempre il sito WordPress su `http://127.0.0.1:8081`
- Vite gira su `http://127.0.0.1:5173` solo per servire gli asset
- non devi vedere il sito completo su `5173`

### Come Verificare Che Vite Funzioni

1. avvia WordPress con `./docker-wps/bootstrap.sh`
2. avvia Vite con `npm run dev`
3. apri `http://127.0.0.1:8081`
4. modifica un file in `source/theme/sass` oppure `source/theme/js`
5. verifica che il browser si aggiorni e che la modifica si veda nel sito

Esempio utile:

- modifica [all.scss](/Users/luca/Develop/2026/wpstarter-master/source/theme/sass/base/all.scss#L1)
- salva
- ricarica il sito su `8081`
- se la modifica appare subito, Vite e' collegato correttamente

## Tema Starter

Il tema si trova in [your-theme](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme).

File principali:

- [style.css](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/style.css)
- [functions.php](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/functions.php)
- [theme.json](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/theme.json)
- [front-page.php](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/front-page.php)
- [page.php](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/page.php)
- [single.php](/Users/luca/Develop/2026/wpstarter-master/webroot/wp-content/themes/your-theme/single.php)

## Reset Completo

`reset.sh` fa questo:

- ferma lo stack `docker-wps`
- rimuove i container del progetto
- rimuove la rete del progetto
- rimuove il volume database `docker-wps_db_data`

`reset.sh` non fa questo:

- non rimuove altri progetti Docker
- non rimuove tutte le immagini Docker del computer
- non cancella i file del repository
- non cancella `node_modules`

Per eseguire il reset completo del solo progetto `docker-wps`:

```bash
cd docker-wps
./reset.sh
```

Oppure dalla root:

```bash
./docker-wps/reset.sh
```

## Reset Hard

Se vuoi davvero ripartire da zero anche lato filesystem, usa `reset-hard.sh`.

`reset-hard.sh` fa questo:

- fa tutto quello che fa `reset.sh`
- pulisce i file WordPress generati dentro `webroot`
- rimuove core WordPress, `wp-admin`, `wp-includes` e file runtime
- svuota `uploads`, `plugins`, `mu-plugins`, `languages` e `upgrade`

`reset-hard.sh` preserva:

- la struttura minima versionata di `webroot/wp-content`
- il tema starter `webroot/wp-content/themes/your-theme`

Uso:

```bash
cd docker-wps
./reset-hard.sh
```

Oppure dalla root:

```bash
./docker-wps/reset-hard.sh
```

Dopo un hard reset puoi ripartire con:

```bash
./docker-wps/bootstrap.sh
```

## Struttura Repo

```text
.
├── docker-wps/
├── source/theme/
├── webroot/
├── starter.config.json
├── vite.config.mjs
└── package.json
```

## Troubleshooting Rapido

Se il sito non parte:

- controlla che Docker sia attivo
- rilancia `./bootstrap.sh`
- verifica che la porta `8081` o `8080` non siano occupate
- controlla i container con `docker compose ps` dentro `docker-wps`

Se gli asset non si aggiornano:

- controlla che `npm run dev` sia attivo
- verifica host e porta in [starter.config.json](/Users/luca/Develop/2026/wpstarter-master/starter.config.json#L1)
- fai una build con `npm run build`

## Setup In 30 Secondi

Se vuoi vedere il progetto partire senza leggere tutto:

1. apri il terminale nella root del progetto
2. esegui `./docker-wps/bootstrap.sh`
3. esegui `npm install`
4. esegui `npm run dev`
5. apri `http://127.0.0.1:8081`

## Note

Questo progetto e' pensato per sviluppo locale rapido. Non e' uno stack di deploy produzione.
