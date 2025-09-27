# Automatyczna aktualizacja meta danych w WordPress za pomocą n8n na przykładzie Yoast SEO

Repozytorium to zawiera kompletny zestaw narzędzi i instrukcji do automatycznej aktualizacji pól meta (tytuł i opis) oraz fragmentu (excerpt) z wtyczki **Yoast SEO** w WordPress, wykorzystując do tego **REST API** oraz platformę do automatyzacji **n8n**.

## 🎯 Cel

Celem tego projektu jest umożliwienie programistycznego zarządzania SEO dla wpisów (zarówno standardowych, jak i Custom Post Types) bez potrzeby ręcznej edycji w panelu WordPress. Jest to idealne rozwiązanie do masowych aktualizacji, integracji z zewnętrznymi systemami lub dynamicznego generowania meta danych na podstawie treści wpisu.

## ⚠️ Ważne uwagi

* **Charakter demonstracyjny**: Przedstawiony workflow i przykład mają jedynie na celu zaprezentowanie sposobu działania automatyzacji. **Nie jest to rozwiązanie gotowe do użytku produkcyjnego** bez odpowiednich modyfikacji i zabezpieczeń.
* **Kopiowanie kodu PHP**: Podczas kopiowania kodu z pliku `functions-snippet.php`, upewnij się, że pomijasz linijkę zawierającą komentarz `// --- IGNORE ---`. Służy ona jedynie do celów organizacyjnych w tym projekcie.

-----

## Zasada działania aktualizacji przez API

Sercem całego procesu jest ostatni krok, w którym n8n wysyła specjalnie przygotowany pakiet danych do Twojej strony WordPress za pośrednictwem **REST API**. Ten pakiet ma format **JSON** i działa jak cyfrowy formularz, który precyzyjnie instruuje WordPress, co i gdzie ma zaktualizować.

W praktyce workflow wysyła żądanie `POST` do unikalnego adresu URL konkretnego wpisu, a w jego treści znajduje się obiekt JSON o następującej strukturze:

```json
{
    "excerpt": "To jest nowy, wygenerowany przez AI fragment wpisu...",
    "meta": {
        "_yoast_wpseo_title": "Nowy meta tytuł o długości 60 znaków...",
        "_yoast_wpseo_metadesc": "Nowy meta opis, który ma od 150 do 160 znaków i opisuje zawartość..."
    }
}
```

**Co oznaczają poszczególne pola?**

  * `"excerpt"`: Ta wartość bezpośrednio aktualizuje pole **"Zajawka"** (Excerpt) dla danego wpisu w WordPressie.
  * `"meta"`: To specjalny obiekt, który przechowuje dodatkowe informacje (metadane) o wpisie.
      * `"_yoast_wpseo_title"`: To wewnętrzna nazwa, której **Yoast SEO** używa do przechowywania **meta tytułu**.
      * `"_yoast_wpseo_metadesc"`: To wewnętrzna nazwa dla **meta opisu** w Yoast SEO.

Gdy WordPress odbiera ten pakiet danych, jego API automatycznie rozpoznaje te pola (dzięki kodowi dodanemu wcześniej w pliku `functions.php`) i zapisuje nowe wartości w odpowiednich miejscach w bazie danych. W ten sposób cały proces ręcznej edycji SEO zostaje w pełni zautomatyzowany.

---

## 🛠️ Wymagania

Przed rozpoczęciem upewnij się, że posiadasz:
* Działającą stronę na **WordPress** z uprawnieniami administratora.
* Zainstalowaną i aktywną wtyczkę **Yoast SEO**.
* Dostęp do plików serwera (poprzez FTP lub edytor w panelu WP) w celu modyfikacji pliku `functions.php`.
* Aktywną instancję **n8n** (w chmurze lub self-hosted).
* Konto **OpenAI** z dostępem do API lub inny dostawca.

---

## 🚀 Instrukcja Krok po Kroku

Proces konfiguracji składa się z trzech głównych części: przygotowania WordPressa, wygenerowania klucza dostępowego i skonfigurowania przepływu pracy w n8n.

### 1. Przygotowanie WordPressa

Domyślnie WordPress nie pozwala na modyfikację pól Yoast SEO przez REST API. Musimy to włączyć, dodając dedykowany kod do naszej strony.

1.  Skopiuj zawartość pliku `functions-snippet.php` z tego repozytorium, **pomijając pierwszą linię z komentarzem `// --- IGNORE ---`**.
    * Uwaga: Jeśli chcesz dodać wsparcie dla innych niestandardowych typów wpisów (CPT), skopiuj i zmodyfikuj poniższy fragment kodu, zastępując `nckl_aktualnosci` nazwą swojego CPT.

```php
        // Rejestracja pól dla niestandardowego typu wpisu np. 'nckl_aktualnosci'
        register_meta('nckl_aktualnosci', '_yoast_wpseo_title', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);

        register_meta('nckl_aktualnosci', '_yoast_wpseo_metadesc', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);
```

2.  Wklej skopiowany kod na końcu pliku `functions.php` Twojego motywu.
    * **Zalecenie:** Użyj **motywu potomnego (child theme)**, aby Twoje zmiany nie zostały nadpisane podczas aktualizacji głównego motywu.
    * Plik ten znajdziesz w panelu WordPress w `Wygląd` → `Edytor plików motywu` → `functions.php`.
3.  Zapisz zmiany w pliku.

Kod zawarty w pliku `functions-snippet.php` rejestruje pola `_yoast_wpseo_title` oraz `_yoast_wpseo_metadesc` dla standardowych wpisów (`post`) oraz przykładowego typu CPT (`sk_aktualnosci`), udostępniając je w REST API.

### 2. Uwierzytelnianie - Hasła Aplikacji

Aby n8n mogło bezpiecznie łączyć się z Twoim WordPressem, stworzymy dedykowane hasło.

1.  W panelu WordPress przejdź do `Użytkownicy` → `Profil`.
2.  Przewiń stronę w dół do sekcji **"Hasła aplikacji"**.
3.  Wpisz nazwę dla nowego hasła (np. `n8n Automatyzacja`) i kliknij przycisk **"Dodaj nowe hasło aplikacji"**.
4.  **Skopiuj wygenerowane hasło** i zapisz je w bezpiecznym miejscu. Będzie ono potrzebne w następnym kroku. **Po zamknięciu strony nie będzie można go ponownie wyświetlić!**

Dodatkowa instrukcja w dokumentacji n8n:
[https://docs.n8n.io/integrations/builtin/credentials/wordpress/#enable-two-step-authentication](https://docs.n8n.io/integrations/builtin/credentials/wordpress/#enable-two-step-authentication)

### 3. Konfiguracja Workflow w n8n

Ostatnim krokiem jest import i konfiguracja gotowego przepływu pracy w n8n.

1.  Pobierz plik `AllowWordpresMetaDataRestApi.json` z tego repozytorium.
2.  W swoim panelu n8n wybierz `Import` → `Import from file` i wskaż pobrany plik.
3.  Po zaimportowaniu musisz skonfigurować następujące węzły (nodes):
    * **Node `Config`**:
        * **URL**: Zmień wartość `https://twoja-domena.pl` na pełny adres URL Twojej strony.
        * **Language**: Ustaw język, w jakim AI ma generować treści (domyślnie "Polish").
    * **Node `GetManyPosts`**:
        * Węzeł jest wstępnie skonfigurowany do pobierania jednego ostatnio opublikowanego wpisu. Możesz to zmienić w polu `Limit`.
        * Zapozanj sie dokumnetacją węzła [WordPress node](https://docs.n8n.io/integrations/builtin/app-nodes/n8n-nodes-base.wordpress/)
        * Zapozanj się z dokumentacją n8n dotyczącą [WordPress](https://docs.n8n.io/integrations/builtin/credentials/wordpress/) aby poprawnie skonfigurować dane dostępowe.
        * **Credential to connect with**: Kliknij w pole `Wordpress API` i stwórz nowe dane dostępowe (`Create New`). Podaj adres URL Twojej strony, nazwę użytkownika oraz **hasło aplikacji** wygenerowane w kroku 2.
        * Zapisz dane uwierzytelniające.
    * **Node `OpenAI Chat Model1`**:
        * **Credentials**: Stwórz i wybierz swoje dane dostępowe do **OpenAI API**.
    * **Node `UpdatePost`**:
        * **Authentication**: Upewnij się, że wybrane jest `Predefinied Credentials Type`.
        * **Credentials Type**: Wybierz te same dane dostępowe do WordPress, które zostały skonfigurowane w nodzie `GetManyPosts`.
4.  Zapisz i aktywuj workflow. Po ręcznym uruchomieniu (`Test workflow`) meta dane dla ostatniego opublikowanego wpisu powinny zostać automatycznie wygenerowane i zaktualizowane.

---


#### 📜 Licencja
Copyright (c) 2025 Rafał Bodziony @ kingweb.pl, kingweb.me.