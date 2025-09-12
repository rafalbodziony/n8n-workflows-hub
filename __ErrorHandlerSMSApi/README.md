# n8n: Powiadomienie o błędzie przez SMS

Czasami informowanie o błędach e-mailem to za mało. Workflow n8n uruchamiany przez `Error Trigger` wysyła zwięzły alert SMS przez SMSAPI oraz raport e-mail z pełnymi szczegółami błędu. Komunikat SMS jest budowany z użyciem wyrażeń JavaScript (ASCII fallback, twardy limit 160 znaków), a wysyłka odbywa się przez `HTTP Request` z autoryzacją Bearer i włączonym retry.

## Co to robi

* Gdy jakiś workflow ulegnie awarii ten workflow:
  * wyśle e-mail z detalami błędu,
  * wyśle krótki SMS z najważniejszymi informacjami (ID i nazwa workflow, ID wykonania, skrót błędu).

Plik: [\_\_ErrorHandlerSMSApi.json](./__ErrorHandlerSMSApi.json)

## Co jest potrzebne

* n8n self-hosted lub n8n.cloud.
* Konto w SMSAPI z tokenem (Bearer).
* Numer telefonu odbiorcy (np. 48xxxxxxxxx).
* Nadawca SMS (alfanumeryczny, do 11 znaków, np. `n8nError`).
* Konto Gmail (OAuth) lub inny do wysyłki e-maila.

## Szybka konfiguracja (jednorazowo)

1. Zaimportuj plik [\_\_ErrorHandlerSMSApi.json](./__ErrorHandlerSMSApi.json) do n8n.
2. Otwórz node `Config` i wpisz:

   * `fromName` – nazwa nadawcy SMS,
   * `toPhone` – numer odbiorcy.
3. Otwórz node `sendSMS` → ustaw poświadczenia `SMSApi` (Bearer token).
4. Otwórz node `Gmail` → ustaw połączenie z Twoim kontem.
5. Włącz workflow `__ErrorHandlerSMSApi`. Od teraz będzie działał, gdy inne workflow wyrzucą błąd.

## Jak wygląda SMS

Przykład:

```
CRIT WF:s9FYIslW... Backup workflows to github... EX:9331 ERR:The DNS server returned an error...
```

Treść jest automatycznie skracana do 160 znaków i upraszczana, aby uniknąć problemów z polskimi znakami.

## Co można zmienić

* **Odbiorca i nadawca**: w node `Config` (`toPhone`, `fromName`).
* **Treść SMS**: w node `msgSMSTemplate` (format komunikatu i limit znaków).
* **Tryb testowy SMS**: w node `sendSMS` ustaw `test=1`, aby nie wysyłać realnych wiadomości.
* **Powtórzenia przy błędzie SMS**: `retryOnFail` i `waitBetweenTries` są włączone w node `sendSMS`.

## Na co uważać

* Jeśli SMS nie dochodzi: sprawdź token, saldo w SMSAPI, poprawność numeru i logi node’a `sendSMS`.
* E-mail zawiera pełne szczegóły (link do wykonania, stos błędu) — SMS jest skrótem na dyżur.

## Dodatkowe informacje

* Dokumentacja SMSAPI (OpenAPI): [https://www.smsapi.pl/docs/#dokumentacja-openapi](https://www.smsapi.pl/docs/#dokumentacja-openapi)
* Endpoint pojedynczego SMS: `https://api.smsapi.pl/sms.do` (parametry: `from`, `to`, `message`, `format=json`, opcjonalnie `test`, `normalize`, `details`).

## Struktura projektu

```text
/
├─ README.md
├─ __ErrorHandlerSMSApi.json
```

* `__ErrorHandlerSMSApi.json` — eksport workflow z węzłami: `Error Trigger`, `Config`, `msgSMSTemplate`, `sendSMS`, `Gmail`.
