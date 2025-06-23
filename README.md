# Állatkerti Nyilvántartó Rendszer

Ez a projekt egy egyszerű állatkerti nyilvántartó rendszert valósít meg Laravel keretrendszer használatával. Lehetővé teszi a kifutók és állatok kezelését, gondozók hozzárendelését, valamint az archivált állatok nyilvántartását.

## Tartalomjegyzék

  * [Jellemzők](#jellemzők)
  * [Technológiák](#technológiák)
  * [Telepítés és futtatás](#telepítés-és-futtatás)
  * [Alkalmazás használata](#alkalmazás-használata)
  * [Adatbázis séma](#adatbázis-séma)

## Jellemzők

  * **Felhasználókezelés:** Gondozói és adminisztrátori szerepkörök (Laravel Breeze alapokkal).
  * **Kifutók kezelése:**
      * Kifutók listázása limit, aktuális állatszám és etetési idő szerint.
      * Kifutók létrehozása, szerkesztése (gondozók hozzárendelésével) és törlése (csak üres kifutók esetén).
      * Kifutók részletes megjelenítése a bennük lévő állatokkal.
      * Ragadozó/nem ragadozó kifutók megkülönböztetése és figyelmeztetés.
  * **Állatok kezelése:**
      * Állatok létrehozása, szerkesztése (beleértve a kifutó módosítását).
      * Automatikus ellenőrzés ragadozó/nem ragadozó elhelyezési szabályra.
      * Képfeltöltés állatokhoz, placeholder kép, ha nincs feltöltött.
      * Soft delete alapú archiválás.
      * Archivált állatok listázása és visszaállításuk lehetősége.
  * **Főoldali Teendők:** A bejelentkezett gondozóhoz rendelt kifutók jövőbeli etetési időinek listázása.
  * **Adatvalidáció:** Szerveroldali validáció minden űrlapon.
  * **Állapottartás:** Hibás űrlapok adatainak visszatöltése és hibaüzenetek megjelenítése.

## Technológiák

  * **Keretrendszer:** Laravel 11/12
  * **Adatbázis:** SQLite
  * **Függőségkezelő (PHP):** Composer
  * **Függőségkezelő (JavaScript/CSS):** NPM
  * **Frontend:** HTML, CSS, JavaScript (Laravel Breeze alapokon)
  * **Dátumkezelés:** Carbon

## Telepítés és futtatás

Ez a projekt egy szabványos Laravel alkalmazás. A futtatáshoz szükséged lesz PHP, Composer, Node.js és NPM telepítésére a gépeden.

1.  **Klónozd a repót:**

    ```bash
    git clone https://github.com/laurailiescu/laravel_zoo_project/
    cd laravel_zoo_project
    ```

2.  **Függőségek telepítése:**

    ```bash
    composer install
    npm install
    npm run build
    ```

3.  **Környezeti változók beállítása:**
    Hozd létre a `.env` fájlt a `.env.example` alapján:

    ```bash
    cp .env.example .env
    ```

    Generálj alkalmazáskulcsot:

    ```bash
    php artisan key:generate
    ```

    Ellenőrizd, hogy az adatbázis beállítása SQLite-ra vonatkozik-e (alapértelmezett, ha nem változtattad):
    `DB_CONNECTION=sqlite`

4.  **Adatbázis inicializálása:**
    Futtasd a migrációkat és a seedert az adatbázis feltöltéséhez:

    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Futtatás:**
    Indítsd el a Laravel fejlesztői szerverét:

    ```bash
    php artisan serve
    ```

    Az alkalmazás alapértelmezetten a `http://127.0.0.1:8000` címen lesz elérhető.

### Inicializációs scriptek

A beadás részeként mellékelt `init.bat` (Windows) és `init.sh` (Linux/Mac) scriptek automatizálják a fenti telepítési és futtatási lépéseket.

  * **Windows-on:**
    ```bash
    init.bat
    ```
  * **Linux/Mac-en:**
    ```bash
    ./init.sh
    ```
    (Lehet, hogy előbb futtatási jogot kell adni: `chmod +x init.sh`)

## Alkalmazás használata

Az alkalmazás minden oldala csak bejelentkezés után érhető el.

**Alapértelmezett felhasználók a seederből:**

  * **Admin:**
      * Email: `q@q.hu`
      * Jelszó: `q`

Miután bejelentkeztél, navigálhatsz a főoldalon, ahol láthatod a teendőidet, vagy az oldalsávon keresztül elérheted a kifutók és állatok kezelőfelületeit (a jogosultságoknak megfelelően).

## Adatbázis séma

Az alkalmazás a következő modelleket és táblákat használja:

  * **User:**
      * `id`
      * `name`
      * `email`
      * `password`
      * `admin` (boolean, default: `false`)
      * `created_at`, `updated_at`
  * **Enclosure:**
      * `id`
      * `name` (string)
      * `limit` (integer)
      * `feeding_at` (time)
      * `created_at`, `updated_at`
  * **Animal:**
      * `id`
      * `name` (string)
      * `species` (string)
      * `is_predator` (boolean)
      * `born_at` (timestamp)
      * `image` (string, nullable) - a kép fájlneve
      * `enclosure_id` (foreign key to `enclosures.id`)
      * `deleted_at` (timestamp, for soft delete)
      * `created_at`, `updated_at`

**Kapcsolatok:**

  * `User` N:M `Enclosure` (Pivot tábla: `enclosure_user`)
  * `Enclosure` 1:N `Animal`
