# Web programozás-1 — Házi feladat

**Készítette:** Kovács Kristóf Buday Benedek
**Neptun kód:** XADNA6 OXQLH4
**Téma:** Magyar Feltalálók — adatbázis kezelő web-alkalmazás

---

## Tartalom

Hét fejezetből álló web-alkalmazás a `kutato.txt` adatbázis alapján (magyar feltalálók):

| # | Útvonal | Technológia |
|---|---|---|
| I.   | `/` / `index.html`        | Főoldal — látványos kezdőlap |
| II.  | `/javascript.html`        | Vanilla JavaScript CRUD (tömbben) |
| III. | `/react.html`             | React CRUD (useState, komponensek) |
| IV.  | `/spa.html`               | SPA — Számológép + Tic-Tac-Toe |
| V.   | `/fetchapi.html`          | Fetch API + PHP/MySQL szerver |
| VI.  | `/axios.html`             | React + Axios + PHP/MySQL szerver |
| VII. | `/oojs.html`              | OOJS rajzoló (class, extends, super) |

---

## Projekt szerkezet

```
beadando/
├── README.md
├── backend/                   ← PHP + MySQL szerver
│   ├── api.php                ← REST API (CRUD, PDO prepared statements)
│   ├── config.example.php     ← Adatbázis-hozzáférés sablon
│   ├── schema.sql             ← MySQL séma + seed adatok (30 feltaláló)
│   └── .htaccess              ← CORS fejlécek
└── frontend/                  ← React + statikus HTML
    ├── package.json
    ├── .env                   ← REACT_APP_API_URL
    ├── public/                ← Statikus HTML fájlok (JS, Fetch, OOJS, styles)
    ├── src/                   ← React forrás (Index, ReactPage, SpaPage, AxiosPage)
    └── dist/                  ← Build eredmény — EZ TÖLTHETŐ FEL TÁRHELYRE
        ├── *.html             ← Mindegyik fejezet
        ├── api.php            ← PHP backend
        ├── config.example.php
        ├── schema.sql
        ├── .htaccess
        ├── styles.css
        └── static/            ← React JS + CSS bundle
```

---

## Telepítés TÁRHELYRE (éles környezet)

### 1) Adatbázis előkészítése

A tárhely-szolgáltatód cPanel / phpMyAdmin felületén:

- Hozz létre egy új MySQL adatbázist (pl. `mydb_beadando`)
- Hozz létre egy MySQL felhasználót jelszóval és adj neki hozzáférést az adatbázishoz
- phpMyAdmin → nyisd meg az adatbázist → **Import** fülön töltsd fel a `schema.sql` fájlt
  (vagy másold be a tartalmát az SQL fülön és futtasd)

### 2) Konfigurációs fájl elkészítése

- Másold át a `dist/config.example.php` fájlt `dist/config.php` néven
- Nyisd meg szövegszerkesztővel és írd át a 4 változót a tárhely adataiddal:

```php
<?php
$db_host = 'localhost';              // a tárhely SQL szervere (gyakran 'localhost')
$db_name = 'mydb_beadando';          // az imént létrehozott adatbázis neve
$db_user = 'mydb_user';              // MySQL felhasználónév
$db_pass = 'titkos_jelszo';          // MySQL jelszó
```

### 3) Feltöltés FTP-vel

A `dist/` mappa **teljes tartalmát** töltsd fel a tárhely `public_html/` (vagy `www/`) mappájába.

Arra figyelj:
- Az `api.php`, `config.php`, `schema.sql` és az összes `.html`, a `styles.css`, a `static/` mappa mind a **gyökér webroot-ban** legyen (azaz a `public_html/` közvetlen gyermekei).
- A `.htaccess` is töltődjön fel (rejtett fájl, FTP kliensben engedélyezd a rejtett fájlok megjelenítését).

### 4) Ellenőrzés

Böngészőben nyisd meg:
- `https://a-domained.com/` — Főoldal
- `https://a-domained.com/fetchapi.html` — kell látnod a 30 feltalálót
- `https://a-domained.com/api.php` — JSON tömböt kell visszakapnod

Ha hiba van, nézd meg a `config.php` fájlban a hozzáférést.

---

## Lokális futtatás (fejlesztéshez)

### Szükséges szoftverek

- **PHP 7.4+** (ajánlott 8.x) — [https://www.php.net/downloads](https://www.php.net/downloads)
- **MySQL / MariaDB** — pl. XAMPP csomag részeként: [https://www.apachefriends.org/](https://www.apachefriends.org/)
- **Node.js 18+ és Yarn** — [https://nodejs.org](https://nodejs.org), `npm install -g yarn`

### 1) Adatbázis

- Indítsd el a MySQL-t (XAMPP-ban: Start a MySQL mellett)
- Nyisd meg a `http://localhost/phpmyadmin` címet
- Hozz létre egy adatbázist `beadando_db` néven
- Import fülön töltsd fel a `backend/schema.sql`-t

### 2) Config

- Másold át `backend/config.example.php` → `backend/config.php`
- Ha XAMPP-ot használsz, alapból `root` felhasználó, üres jelszó, `localhost` szerver — nem kell módosítani

### 3) PHP szerver indítása

```bash
cd beadando
php -S localhost:8000 -t backend
```

Ellenőrzés: nyisd meg a `http://localhost:8000/api.php` URL-t — JSON tömböt kell látnod.

### 4) Frontend (React dev szerver)

Új terminálban:
```bash
cd beadando/frontend
yarn install
yarn start
```

Az alkalmazás a `http://localhost:3000` címen nyílik meg. A `package.json`-ban van egy
`proxy` beállítás, ami az `/api.php` hívásokat a PHP szerverre (`localhost:8000`) irányítja.

### 5) Alternatíva: XAMPP Apache

Ha XAMPP Apache-ot használsz, másold a `dist/` tartalmát a `xampp/htdocs/beadando/` mappába,
majd nyisd meg a `http://localhost/beadando/` címet. Ez azonnal működik, nincs szükség
React dev szerverre.

### 6) Build (telepítés előtt)

```bash
cd frontend
yarn build
```

A `yarn build` a `dist/` mappába generálja a kész fájlokat. A `postbuild` script
automatikusan másolja az `index.html`-t `react.html`, `spa.html`, `axios.html` néven
is, hogy a React Router által kezelt oldalak közvetlenül elérhetők legyenek a tárhelyen.

---

## API dokumentáció

| Metódus | URL | Body | Válasz |
|---|---|---|---|
| GET | `api.php` | – | `[{fkod, nev, szul, meghal}, ...]` |
| GET | `api.php?id=5` | – | `{fkod: 5, nev: "...", szul: 1859, meghal: 1922}` |
| POST | `api.php` | `{nev, szul, meghal}` | új rekord `fkod`-dal |
| PUT | `api.php?id=5` | `{nev, szul, meghal}` | módosított rekord |
| DELETE | `api.php?id=5` | – | `{ok: true, deleted: 5}` |

---

## Felhasznált források

- React Tic-Tac-Toe oktatóanyag: [react.dev/learn/tutorial-tic-tac-toe](https://react.dev/learn/tutorial-tic-tac-toe)
- Az órán bemutatott Calculator alkalmazás (saját implementáció)
- PHP PDO dokumentáció: [php.net/manual/en/book.pdo.php](https://www.php.net/manual/en/book.pdo.php)
- Tipográfia: Lora & Cormorant Garamond (Google Fonts)

---

*Budapest · MMXXVI · Web Programozás-1*
