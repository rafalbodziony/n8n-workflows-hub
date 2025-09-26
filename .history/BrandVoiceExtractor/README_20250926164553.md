# 🎙️ BrandVoiceExtractor - Analizator Tonu Marki w n8n

Ten projekt zawiera workflow dla platformy [n8n](https://n8n.io/), który automatycznie analizuje dowolny tekst w języku polskim i wyodrębnia jego kluczowe cechy komunikacyjne, takie jak ton, styl, słownictwo i techniki perswazji. Wynik jest zwracany w ustrukturyzowanym formacie JSON, gotowym do dalszego wykorzystania.

## 🚀 Co robi ten workflow?

Głównym zadaniem `BrandVoiceExtractor` jest przeprowadzenie dogłębnej analizy lingwistycznej dostarczonego tekstu. Działa jak ekspert od komunikacji marki, który rozkłada tekst na czynniki pierwsze.

Po wklejeniu tekstu do okna czatu, workflow:

1.  Analizuje go pod kątem 15 różnych kategorii komunikacyjnych.
2.  Dla każdej kategorii tworzy zwięzły opis analityczny.
3.  Dołącza dokładny cytat z tekstu źródłowego, który ilustruje daną cechę.
4.  Zwraca kompletną analizę w postaci przejrzystego obiektu JSON.

To idealne narzędzie do **szybkiego zrozumienia stylu komunikacji konkurencji**, weryfikacji spójności własnych treści lub tworzenia wytycznych dla copywriterów.

## Wymagania

* n8n uruchomione lokalnie lub w chmurze
* Klucz API OpenAI
* Plik workflow: BrandVoiceExtractor.json (do importu w n8n). 
-----

## ⚙️ Jak to działa? Krok po kroku

Workflow jest zbudowany w n8n i wykorzystuje model językowy OpenAI (GPT-4) do przeprowadzenia analizy. Oto poszczególne etapy jego działania:

1.  **Odbiór wiadomości (`When chat message received`)**: Workflow uruchamia się, gdy użytkownik wyśle wiadomość w interfejsie czatu n8n.
2.  **Konfiguracja (`Config`)**: Tekst wprowadzony przez użytkownika jest przechwytywany i przygotowywany do dalszego przetwarzania.
3.  **Mózg operacji - Ekstraktor (`Ekstraktor`)**:
      * To główny węzeł, który wysyła tekst do modelu AI (w tym przypadku GPT-4.1).
      * Zawiera on bardzo szczegółowy **prompt**, który instruuje model, aby wcielił się w rolę eksperta ds. analizy komunikacji.
      * Prompt precyzyjnie definiuje 15 kategorii do analizy i określa, jak ma wyglądać opis oraz cytat dla każdej z nich.
4.  **Parser odpowiedzi (`Structured Output Parser`)**: Ten węzeł dba o to, aby odpowiedź z modelu AI miała zawsze poprawną strukturę JSON. Dzięki niemu wynik jest przewidywalny i łatwy do przetworzenia maszynowego.
5.  **Pamięć czatu (`Simple Memory`)**: Węzeł zapamiętuje historię konwersacji (opcjonalnie, przydatne przy rozbudowie).
6.  **Wysłanie odpowiedzi (`Respond to Chat`)**: Gotowy, sformatowany wynik analizy w formacie JSON jest odsyłany z powrotem do użytkownika w oknie czatu.

### Kategorie analizy

Workflow analizuje tekst pod kątem następujących cech:

  * **Ton i Głos** (`toneOfVoice`) - określ charakter tonu (formalność, emocje, autorytet, nastawienie do odbiorcy); wskaż dominujące przymiotniki tonu.
  * **Język i Słownictwo** (`languageAndVocabulary`) - wskaź typ słownictwa (techniczne/proste/mieszane), zakres żargonu i jego przykłady.
  * **Struktura Zdań** (`sentenceStructure`) - opisz długość i złożoność zdań, obecność wyliczeń, pytań retorycznych, konstrukcji warunkowych.
  * **Styl Narracji** (`narrativeStyle`) - ustal dominujący sposób narracji (bezpośrednia/opisowa/storytelling/informacyjna) oraz jej konsekwencję.
  * **Wartości i Przekaz Marki** (`brandValuesAndMessaging`) - wyodrębnij misję, wartości, obietnice oraz główny przekaz.
  * **Hak (Hook)** (`hook`) - wskaź mechanizm otwarcia (pytanie, teza, statystyka) i jego intencję perswazyjną.
  * **Podton Emocjonalny** (`emotionalUndertone`) - nazwij podton emocjonalny (np. uspokajający, motywujący, alarmujący) i uzasadnij go sygnałami z tekstu.
  * **Tempo i Rytm** (`pacingAndRhythm`) - opisz tempo (dynamiczne/spokojne/mieszane), rytm (krótkie piki vs. długie okresy), obecność pauz/akapityzacji.
  * **Perspektywa (Punkt Widzenia)** (`pointOfView`) - określ osobę narracji (1./2./3.), stopień bezpośredniości i częstotliwość zwrotów do odbiorcy.
  * **Poziom Formalności** (`formalityLevel`) - oszacuj formalność (formalny/półformalny/potoczny) i wskaż sygnały językowe tej klasyfikacji.
  * **Techniki Perswazji** (`persuasiveTechniques`) - zidentyfikuj techniki (statystyki, CTA, autorytet, społeczny dowód słuszności, storytelling, niedobór/pilność).
  * **Tagowanie Treści** (`contentTagging`) - wskaż przykładowe fragmenty dla tagów (INFO, PERSW, EMO, TECH, CTA, EVD).
  * **Analiza Segmentów** (`segmentAnalysis`) - podaj zarys różnic tonu/stylu między segmentami (np. „wstęp – perswazyjny, środek – informacyjny, zakończenie – CTA”).
  * **Motywy Dominujące** (`dominantMotifs`) - wypisz 2–5 motywów (powtarzalne frazy, metafory, obrazy, konstrukcje).
  * **Sprawdzenie Spójności** (`consistencyCheck`) - oceń spójność (wysoka/średnia/niska) i wskaż miejsca zmian wraz z krótkim uzasadnieniem.

-----

## 🛠️ Instalacja i uruchomienie

Aby skorzystać z workflow, potrzebujesz działającej instancji n8n (może być w chmurze lub self-hosted).

### Kroki instalacji:

1.  **Pobierz plik**: Pobierz plik `BrandVoiceExtractor.json` z tego repozytorium.
2.  **Zaimportuj workflow**:
      * W swoim interfejsie n8n, kliknij `Import from File`.
      * Wybierz pobrany plik `BrandVoiceExtractor.json`.
3.  **Skonfiguruj dane uwierzytelniające**:
      * Workflow wymaga połączenia z API OpenAI.
      * Znajdź węzeł o nazwie **OpenAI Chat Model**.
      * W jego ustawieniach, w sekcji `Credential to connect with`, wybierz swoje dane dostępowe do OpenAI lub utwórz nowe, podając swój klucz API.
4.  **Zapisz i aktywuj**: Zapisz zmiany i aktywuj workflow za pomocą przełącznika w prawym górnym rogu.

-----

### Jak używać:

1.  Po aktywacji workflow, na dole ekranu, pośrodku, znajdziesz przycisk **"Open Chat"**.
2.  Kliknij go. Na dole ekranu otworzy się okno czatu.
3.  Wklej w nim dowolny tekst, który chcesz przeanalizować i naciśnij Enter.
4.  Poczekaj chwilę, aż model przetworzy zapytanie.
5.  W odpowiedzi otrzymasz pełną analizę w formacie JSON.

### Przykład użycia

**Twoje wejście (w oknie czatu):**

```
Odkryj przyszłość z naszym nowym produktem! Stworzyliśmy go z myślą o profesjonalistach takich jak Ty, którzy cenią sobie niezawodność i innowacyjne rozwiązania. Nie czekaj, dołącz do liderów rynku już dziś!
```

**Przykładowy fragment odpowiedzi (w oknie czatu):**

```json
{
  "toneOfVoice": {
    "keyMeaning": "Ogólny charakter tonu, poziom formalności, emocje, autorytet, nastawienie.",
    "description": "Ton jest dynamiczny, motywujący i budujący poczucie profesjonalizmu. Nastawienie do odbiorcy jest bezpośrednie i pełne entuzjazmu, z wyraźnym naciskiem na innowacyjność i korzyści.",
    "example": "Odkryj przyszłość z naszym nowym produktem!"
  },
  "persuasiveTechniques": {
    "keyMeaning": "Techniki perswazji użyte w tekście.",
    "description": "W tekście zastosowano bezpośrednie wezwanie do działania (CTA) oraz odwołanie do grupy aspiracyjnej ('liderzy rynku'), co stanowi formę dowodu społecznego.",
    "example": "Nie czekaj, dołącz do liderów rynku już dziś!"
  },
  "...": "..."
}
```

-----

## 🔧 Modyfikacje i Rozbudowa

Ten workflow to świetna baza do dalszej automatyzacji. Możesz go łatwo zmodyfikować, aby dopasować do swoich potrzeb.

### Zmiana sposobu uruchamiania i odpowiedzi:

  * **Uruchamianie z formularza**: Zamiast czatu, możesz użyć węzła `Webhook` i połączyć go z formularzem na stronie internetowej.
  * **Wysyłanie wyniku na e-mail**: Dodaj węzeł do wysyłki e-maili (np. `Send Email`), aby otrzymać analizę bezpośrednio na swoją skrzynkę.
  * **Wykorzystanie jako Sub-Workflow**: Ten workflow może działać jako "podprogram" dla innego, bardziej złożonego procesu. Wystarczy uruchomić go za pomocą węzła `Execute Workflow`.

### Zaawansowane: Wymuszanie struktury za pomocą JSON Schema

Aby zagwarantować, że model AI **zawsze** zwróci odpowiedź w oczekiwanym formacie, możesz zdefiniować `JSON Schema` w węźle **Structured Output Parser**. Dzięki temu, jeśli model spróbuje odpowiedzieć w inny sposób, parser automatycznie poprawi jego odpowiedź lub zgłosi błąd.

Możesz tworzyć i walidować własne schematy za pomocą narzędzi online, takich jak:

  * **Walidator schematów JSON**: [https://www.jsonschemavalidator.net/](https://www.jsonschemavalidator.net/)

Gotowy schemat JSON do "Structured Output Parser" (Pamiętaj, żeby przestawić opcję "Schema Type" na: **Define using JSON Schema**):

```json 
{
    "$schema": "http://json-schema.org/draft-07/schema#",
    "$id": "http://json-schema.org/draft-07/schema#",
    "title": "Core schema meta-schema",
    "definitions": {
        "schemaArray": {
            "type": "array",
            "minItems": 1,
            "items": { "$ref": "#" }
        },
        "nonNegativeInteger": {
            "type": "integer",
            "minimum": 0
        },
        "nonNegativeIntegerDefault0": {
            "allOf": [
                { "$ref": "#/definitions/nonNegativeInteger" },
                { "default": 0 }
            ]
        },
        "simpleTypes": {
            "enum": [
                "array",
                "boolean",
                "integer",
                "null",
                "number",
                "object",
                "string"
            ]
        },
        "stringArray": {
            "type": "array",
            "items": { "type": "string" },
            "uniqueItems": true,
            "default": []
        }
    },
    "type": ["object", "boolean"],
    "properties": {
        "$id": {
            "type": "string",
            "format": "uri-reference"
        },
        "$schema": {
            "type": "string",
            "format": "uri"
        },
        "$ref": {
            "type": "string",
            "format": "uri-reference"
        },
        "$comment": {
            "type": "string"
        },
        "title": {
            "type": "string"
        },
        "description": {
            "type": "string"
        },
        "default": true,
        "readOnly": {
            "type": "boolean",
            "default": false
        },
        "examples": {
            "type": "array",
            "items": true
        },
        "multipleOf": {
            "type": "number",
            "exclusiveMinimum": 0
        },
        "maximum": {
            "type": "number"
        },
        "exclusiveMaximum": {
            "type": "number"
        },
        "minimum": {
            "type": "number"
        },
        "exclusiveMinimum": {
            "type": "number"
        },
        "maxLength": { "$ref": "#/definitions/nonNegativeInteger" },
        "minLength": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },
        "pattern": {
            "type": "string",
            "format": "regex"
        },
        "additionalItems": { "$ref": "#" },
        "items": {
            "anyOf": [
                { "$ref": "#" },
                { "$ref": "#/definitions/schemaArray" }
            ],
            "default": true
        },
        "maxItems": { "$ref": "#/definitions/nonNegativeInteger" },
        "minItems": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },
        "uniqueItems": {
            "type": "boolean",
            "default": false
        },
        "contains": { "$ref": "#" },
        "maxProperties": { "$ref": "#/definitions/nonNegativeInteger" },
        "minProperties": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },
        "required": { "$ref": "#/definitions/stringArray" },
        "additionalProperties": { "$ref": "#" },
        "definitions": {
            "type": "object",
            "additionalProperties": { "$ref": "#" },
            "default": {}
        },
        "properties": {
            "type": "object",
            "additionalProperties": { "$ref": "#" },
            "default": {}
        },
        "patternProperties": {
            "type": "object",
            "additionalProperties": { "$ref": "#" },
            "propertyNames": { "format": "regex" },
            "default": {}
        },
        "dependencies": {
            "type": "object",
            "additionalProperties": {
                "anyOf": [
                    { "$ref": "#" },
                    { "$ref": "#/definitions/stringArray" }
                ]
            }
        },
        "propertyNames": { "$ref": "#" },
        "const": true,
        "enum": {
            "type": "array",
            "items": true,
            "minItems": 1,
            "uniqueItems": true
        },
        "type": {
            "anyOf": [
                { "$ref": "#/definitions/simpleTypes" },
                {
                    "type": "array",
                    "items": { "$ref": "#/definitions/simpleTypes" },
                    "minItems": 1,
                    "uniqueItems": true
                }
            ]
        },
        "format": { "type": "string" },
        "contentMediaType": { "type": "string" },
        "contentEncoding": { "type": "string" },
        "if": {"$ref": "#"},
        "then": {"$ref": "#"},
        "else": {"$ref": "#"},
        "allOf": { "$ref": "#/definitions/schemaArray" },
        "anyOf": { "$ref": "#/definitions/schemaArray" },
        "oneOf": { "$ref": "#/definitions/schemaArray" },
        "not": { "$ref": "#" }
    },
    "default": true
}