# Automatyczna Aktualizacja Yoast SEO w WordPress za pomocą n8n

Repozytorium to zawiera kompletny zestaw narzędzi i instrukcji do automatycznej aktualizacji pól meta (tytuł i opis) z wtyczki **Yoast SEO** w WordPress, wykorzystując do tego **REST API** oraz platformę do automatyzacji **n8n**.

## 🎯 Cel

Celem tego projektu jest umożliwienie programistycznego zarządzania SEO dla wpisów (zarówno standardowych, jak i Custom Post Types) bez potrzeby ręcznej edycji w panelu WordPress. Jest to idealne rozwiązanie do masowych aktualizacji, integracji z zewnętrznymi systemami (np. arkuszami kalkulacyjnymi, bazami danych) lub dynamicznego generowania meta danych.

## 🛠️ Wymagania

Przed rozpoczęciem upewnij się, że posiadasz:
* Działającą stronę na **WordPress** z uprawnieniami administratora.
* Zainstalowaną i aktywną wtyczkę **Yoast SEO**.
* Dostęp do plików serwera (poprzez FTP lub edytor w panelu WP) w celu modyfikacji pliku `functions.php`.
* Aktywną instancję **n8n** (w chmurze lub self-hosted).

---

## 🚀 Instrukcja Krok po Kroku

Proces konfiguracji składa się z trzech głównych części: przygotowania WordPressa, wygenerowania klucza dostępowego i skonfigurowania przepływu pracy w n8n.

### ### 1. Przygotowanie WordPressa

Domyślnie WordPress nie pozwala na modyfikację pól Yoast SEO przez REST API. Musimy to włączyć, dodając dedykowany kod do naszej strony.

1.  Skopiuj zawartość pliku `functions-snippet.php` z tego repozytorium.
2.  Wklej skopiowany kod na końcu pliku `functions.php` Twojego motywu.
    * **Zalecenie:** Użyj **motywu potomnego (child theme)**, aby Twoje zmiany nie zostały nadpisane podczas aktualizacji głównego motywu.
    * Plik ten znajdziesz w panelu WordPress w `Wygląd` → `Edytor plików motywu` → `functions.php`.
3.  Zapisz zmiany w pliku.

Kod zawarty w pliku `functions-snippet.php` rejestruje pola `_yoast_wpseo_title` oraz `_yoast_wpseo_metadesc` dla standardowych wpisów (`post`) oraz przykładowego typu CPT (`sk_aktualnosci`), udostępniając je w REST API.

### ### 2. Uwierzytelnianie - Hasła Aplikacji

Aby n8n mogło bezpiecznie łączyć się z Twoim WordPressem, stworzymy dedykowane hasło.

1.  W panelu WordPress przejdź do `Użytkownicy` → `Profil`.
2.  Przewiń stronę w dół do sekcji **"Hasła aplikacji"**.
3.  Wpisz nazwę dla nowego hasła (np. `n8n Automatyzacja`) i kliknij przycisk **"Dodaj nowe hasło aplikacji"**.
4.  **Skopiuj wygenerowane hasło** i zapisz je w bezpiecznym miejscu. Będzie ono potrzebne w następnym kroku. Po zamknięciu strony nie będzie można go ponownie wyświetlić!

### ### 3. Konfiguracja Workflow w n8n

Ostatnim krokiem jest import i konfiguracja gotowego przepływu pracy w n8n.

1.  Pobierz plik `n8n-workflow.json` z tego repozytorium.
2.  W swoim panelu n8n wybierz `Add` → `Import from file` i wskaż pobrany plik.
3.  Po zaimportowaniu musisz skonfigurować węzeł **"Aktualizuj Meta w WP"**:
    * **URL**: Zmień adres `https://twoja-domena.pl` na adres URL Twojej strony. Wyrażenia `{{...}}` pozostaw bez zmian.
    * **Authentication**: Upewnij się, że wybrane jest `Basic Auth`.
    * **Credentials**: Kliknij w pole `Credential for Basic Auth` i stwórz nowe (`Create New`):
        * **User**: Twoja nazwa użytkownika WordPress.
        * **Password**: Hasło aplikacji wygenerowane w kroku 2.
        * Zapisz dane uwierzytelniające.
4.  Przejdź do węzła **"Przykładowe dane"** i zmień wartości `postId`, `nowyTytulMeta` oraz `nowyOpisMeta`, aby przetestować działanie na wybranym wpisie.
5.  Uruchom workflow! Meta dane we wskazanym wpisie powinny zostać zaktualizowane.

---

## 📁 Pliki w Repozytorium

* **`functions-snippet.php`**: Fragment kodu PHP, który należy dodać do pliku `functions.php` motywu WordPress.
* **`n8n-workflow.json`**: Gotowy do importu przepływ pracy n8n, który demonstruje proces aktualizacji.
* **`README.md`**: Ta instrukcja.

## 🤝 Kontrybucje

Masz pomysł na ulepszenie tego rozwiązania? Znalazłeś błąd? Zapraszam do tworzenia Pull Requests lub zgłaszania Issues.