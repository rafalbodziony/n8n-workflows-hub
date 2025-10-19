# ChatModelCustomImplementation

![ChatModelCustomImplementation](https://raw.githubusercontent.com/rafalbodziony/n8n-workflows-hub/refs/heads/main/ChatModelCustomImplementation/assets/ChatModelCustomImplementation-intr.png)

Workflow demonstrujący sposób obejścia problemu *„Request timed out”* w węźle **OpenAI Chat Model** w n8n poprzez użycie niestandardowego połączenia z modelem OpenAI w węźle **LangChainCode**.

![ChatModelCustomImplementation-n8n-workflow](https://raw.githubusercontent.com/rafalbodziony/n8n-workflows-hub/refs/heads/main/ChatModelCustomImplementation/assets/zdjecie-problem-w-n8n-brak-odpowiedzi-z-openai.png)

Repozytorium: [n8n-workflows-hub / ChatModelCustomImplementation](https://github.com/rafalbodziony/n8n-workflows-hub/tree/main/ChatModelCustomImplementation)

Artykuł z pełnym opisem rozwiązania: [Rozwiązanie problemu Request Timed Out w selektorze modeli n8n](https://kingweb.me/blog/poradniki/rozwiazanie-problemu-request-timed-out-w-selektorze-modeli-n8n)

---

## Opis działania

Workflow tworzy własny węzeł połączenia z modelem OpenAI (`LangChainCodeModelConnector`), który zastępuje standardowy węzeł selektora modeli OpenAI.
Dzięki temu omija ograniczenie czasowe n8n (ok. 180 sekund), umożliwiając obsługę dłuższych zapytań i pełne zwracanie odpowiedzi z API OpenAI.


---

## Struktura workflow

| Węzeł                                | Typ                  | Opis                                                                               |
| ------------------------------------ | -------------------- | ---------------------------------------------------------------------------------- |
| **When clicking ‘Execute workflow’** | Manual Trigger       | Uruchamia workflow ręcznie z n8n                                                   |
| **LangChainCodeModelConnector**      | LangChain Code Node  | Tworzy instancję `ChatOpenAI` z pakietu `@langchain/openai` i zwraca obiekt LLM    |
| **Basic LLM Chain**                  | LangChain Chain Node | Wysyła prompt do modelu OpenAI przez niestandardowy konektor i wyświetla odpowiedź |

---

## Dane do uzupełnienia

W węźle **LangChainCodeModelConnector**:

```javascript
const OPENAI_API_KEY = ""; // ← wprowadź swój klucz API OpenAI
const MODEL_NAME = "gpt-4.1-mini";
const TEMPERATURE = 0.8;
```

* **OPENAI_API_KEY** – Twój klucz z [OpenAI Platform](https://platform.openai.com/).
* **MODEL_NAME** – Możesz zmienić na dowolny obsługiwany model (np. `gpt-4.1`, `gpt-4o`, `gpt-3.5-turbo`).
* **TEMPERATURE** – Steruje kreatywnością odpowiedzi (0.0–1.0).

---

## Działanie

1. Uruchom workflow ręcznie w n8n.
2. Węzeł **LangChainCodeModelConnector** inicjalizuje połączenie z modelem OpenAI.
3. Węzeł **Basic LLM Chain** wysyła przykładowy prompt „Ile to 2+5?” i odbiera odpowiedź.
4. Wynik wyświetla się w oknie podglądu węzła.

---

## Zastosowanie

* Eliminacja błędu `Request timed out` w selektorze modeli OpenAI.
* Możliwość pełnej kontroli parametrów połączenia (`temperature`, `model`, `maxTokens` itp.).
* Użycie w bardziej złożonych procesach: ranking, zapytania do baz wektorowych, generowanie treści.

---

## Linki

* [Artykuł opisujący rozwiązanie](https://kingweb.me/blog/poradniki/rozwiazanie-problemu-request-timed-out-w-selektorze-modeli-n8n)
* [Repozytorium z workflow](https://github.com/rafalbodziony/n8n-workflows-hub/tree/main/ChatModelCustomImplementation)

---

Aby uruchomić:

1. Zaimportuj plik `ChatModelCustomImplementation.json` do n8n.
2. Otwórz węzeł **LangChainCodeModelConnector**.
3. Wklej swój **OPENAI_API_KEY**.
4. Uruchom workflow przyciskiem *Execute workflow*.

--- 
#### 📜 Licencja

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)


Copyright (c) 2025 Rafał Bodziony @ kingweb.pl, kingweb.me.

