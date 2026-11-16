# Run Bahamas 2027 — demo

URL: `/run-bahamas/en` (solo inglese). `/run-bahamas/` reindirizza a `/en` perché la piattaforma tratta l'URL senza `/en` come locale `it`.
Solo pagamenti di prova Stripe; nessuna email in uscita. Non è il sito ufficiale (banner e footer lo dicono).

## Fonti
Sito ufficiale runbahamas.net (home, about, race information, percorsi, charity, hotels, sponsors, volunteer, contatti, privacy)
e pagina iscrizioni su RunSignup (prezzi letti a novembre 2026). Nessun brief su Brain per questo evento.

## Branding ripreso dal sito
- Colori: turchese (#29b2cd / #11b7d3), nero/inchiostro (#0b1d24), oro (#e2c556 dalla locandina). Sfondo chiaro sabbia per le sezioni contenuto.
- Titoli: condensed bold italic maiuscolo (come il logotipo "RUN BAHAMAS"), corpo Roboto (già usato sul sito).
- Tono: inglese energico e accogliente, "race-cation", "flat and fast", spirito delle isole, solidarietà.
- Immagini dal sito stesso: logo, locandina, vista aerea di Goodman's Bay, tramonto sul percorso. Escluse foto che sembrano di terzi (foto stampa Office of the Prime Minister, immagine Flickr, Margaritaville).

## Gap analysis: "Funzioni base mio template maratone" vs sito attuale
| Funzione del template | Sito attuale | Demo |
|---|---|---|
| Home con hero, data, luogo, CTA | Presente | Sì, con countdown funzionante |
| Dati sintetici (distanze) | Contatori fermi a "0 Miles", "Marathon Relay" doppio, manca la Maratona | 26.2 / 13.1 / 6.2 / 3.1 corretti |
| Contatore iscritti | Assente | No (richiede iscrizioni reali: da attivare) |
| Presentazione percorsi con link all'iscrizione precompilata | Pagine percorso senza link diretti; URL duplicati ("5k-copy-copy") | Scheda per ognuna delle 8 gare, iscrizione precompilata |
| Programma del weekend | Solo testo in pagina lunga | Timeline sabato/domenica |
| FAQ a fisarmonica | Assenti | 8 domande reali dalla pagina "Race Information" |
| Pagine info: contatti, partnership/sponsor, regolamento, privacy, cookie | Contatti, sponsor (modulo), privacy presenti. Regolamento/termini non trovati | Contatti, privacy, giving back (charity + sponsor + volontari). Regolamento e cookie: da fornire |
| Navbar responsive, CTA sempre visibile, footer completo | Menu con hamburger, CTA "Register Now" | Header fisso con CTA, menu mobile, footer |
| Gestione cookie | Non verificabile | Non inclusa (nessun tracking nella demo) |
| Modulo iscrizione | Esterno (RunSignup) | Modulo interno, 1–10 partecipanti, un solo pagamento |
| Prezzo dinamico da DB | Prezzi solo su RunSignup, + SignUp fee $4,50–$12,20 | Prezzi in pagina, modificabili da /admin |
| Partecipanti multipli | Gestiti su RunSignup | Sì (squadra staffetta da 4 o gruppo) |
| Dati partecipante | n/d | Nome, cognome, data di nascita, email, telefono, città, paese, navetta |
| Navetta | Descritta a testo (solo dal Warwick) | Opzione nel modulo, per le gare in presenza |
| Promo gruppi (ogni 11° gratis) | Assente | Non attiva: ordini di gruppo sì, regola promo non configurata nella piattaforma demo |
| Codici sconto (%/importo/prezzo finale) | n/d | Codice sconto per partecipante (importo); gestione da /admin |
| Supplemento non tesserati | Non applicabile | Non applicabile |
| Stripe + PayPal | RunSignup | Solo Stripe test (EUR) |
| Conferma, email, PDF biglietto | RunSignup | Pagina conferma e biglietto; email disabilitate nella demo |
| Admin: dashboard, filtri, modifica iscritti, CSV, import, creazione manuale | RunSignup | Admin condiviso della piattaforma, filtrato per demo |
| SEO: titoli, descrizioni, hreflang, dati strutturati | Titolo home "Home -", meta generica | Titolo e descrizione per pagina, hreflang IT/EN. Demo in noindex |
| Accessibilità | n/d | Skip link, focus visibili, FAQ con details/summary, ARIA su menu |
| Multilingua | Solo inglese | Solo inglese (evento locale) |

## Elenco migliorie rispetto al sito attuale
1. **Iscrizione dentro il brand**: oggi "Register" porta a RunSignup; qui modulo, ordini di gruppo e conferma restano su Run Bahamas.
2. **Prezzi visibili sul sito** (oggi si scoprono solo uscendo su RunSignup) e quota gara senza SignUp fee: su RunSignup la fee è aggiunta sopra la quota gara ($4,50–$12,20 per iscrizione).
3. **Contatori distanze funzionanti** e coerenti (ora 0 miglia, e Maratona assente).
4. **Weekend di gara in una schermata**: timeline e FAQ al posto di una pagina di testo lunga.
5. **URL e titoli puliti**: pagina Mezza Maratona su "5k-copy-copy", titolo home "Home -".
6. **Scheda per ogni gara** con distanza in miglia e km, partenza, quota, regole, tempo limite.
7. **Staffetta da 4** come ordine unico con quattro partecipanti.
8. **Pagina Travel** con codici hotel/volo in evidenza; **Giving back** con charity, livelli sponsor e volontari.
9. **Countdown vero** verso le 6:00 del 31 gennaio 2027 (UTC−5).
10. **Mobile first**: header fisso con CTA, form a una colonna, nessuno scroll orizzontale.
11. Correzioni di copy: "Powered **by** Four Seasons Race Management", "Word of **Mouth**".

## Segnaposto e limiti da verificare
- **Valuta**: il checkout condiviso è solo in EUR; i prezzi mostrati (€) sono i numeri in USD di RunSignup. In produzione servirebbe USD/BSD: richiede una modifica al codice condiviso (non fatta).
- **Prezzi**: quota gara "early" (prima del 1 gennaio 2027): 155 / 105 / 55 / 40 / 30 (student) e virtuali 105 / 155. Dopo l'1/1 salgono di 5. Staffetta: 170 per squadra, qui 42,50 a corridore (ipotesi, la piattaforma addebita per partecipante).
- **Navetta**: il partial condiviso preseleziona "Sì" (default mantenuto) e ripete i radio in ogni pannello gara con lo stesso name, il che può lasciare il gruppo vuoto: `site.js` lo corregge solo lato demo. Da sistemare nella piattaforma condivisa.
- **Staffetta**: il minimo di quattro corridori non è imposto dal modulo.
- **Gara 5K**: la pagina RunSignup ha anche una "Student 5K" ($30), inclusa.
- **Orari**: RunSignup indica 6:45 per 5K/10K, il sito dice "subito dopo" la partenza lunga. Usata la formula del sito.
- **Contatti**: la pagina Contatti mostra solo ragione sociale e indirizzo e rimanda al modulo ufficiale. Telefoni ed email pubblicati sul sito vanno confermati come recapiti ufficiali prima di inserirli.
- **Foto**: logo, locandina e foto provengono dal sito del cliente: confermare i diritti d'uso per il sito definitivo.
- **Regolamento, cookie, età minima, rimborsi**: non trovati sul sito, non inventati.
- **Contatore iscritti** e **promo ogni 11° biglietto gratis**: non presenti.
